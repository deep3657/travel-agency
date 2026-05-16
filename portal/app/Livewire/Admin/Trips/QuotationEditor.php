<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Trips;

use App\Models\Quotation;
use App\Models\Vendor;
use App\Services\QuotationService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class QuotationEditor extends Component
{
    public Quotation $quotation;

    /** @var array<int, array<string, mixed>> */
    public array $lines = [];

    public string $discount_amount = '0';

    public string $service_type = 'package';

    public string $customer_state = '';

    public ?string $terms = null;

    public ?string $customer_notes = null;

    /** @var array<string, mixed> */
    public array $totals = [
        'subtotal' => '0.00',
        'discount_amount' => '0.00',
        'cgst' => '0.00',
        'sgst' => '0.00',
        'igst' => '0.00',
        'grand_total' => '0.00',
    ];

    public function mount(string $ulid): void
    {
        $this->quotation = Quotation::with(['trip.customer', 'currentVersion.lines'])->where('ulid', $ulid)->firstOrFail();
        abort_unless(auth()->user()?->can('update', $this->quotation), 403);

        $currentVersion = $this->quotation->currentVersion;
        if ($currentVersion) {
            foreach ($currentVersion->lines as $line) {
                $this->lines[] = [
                    'line_type' => $line->line_type,
                    'description' => $line->description,
                    'quantity' => (string) $line->quantity,
                    'unit_rate' => (string) $line->unit_rate->toRupees(),
                    'vendor_id' => $line->vendor_id,
                    'purchase_cost' => $line->purchase_cost ? (string) $line->purchase_cost->toRupees() : null,
                ];
            }
            $this->terms = $currentVersion->terms;
            $this->customer_notes = $currentVersion->customer_notes;
            $this->discount_amount = (string) $currentVersion->discount_amount->toRupees();
            $this->customer_state = $this->quotation->trip->customer->state ?? '';
        }

        if (empty($this->lines)) {
            $this->addLine();
        }

        $this->recalculate();
    }

    public function addLine(): void
    {
        $this->lines[] = [
            'line_type' => 'package',
            'description' => '',
            'quantity' => '1',
            'unit_rate' => '0',
            'vendor_id' => null,
            'purchase_cost' => null,
        ];
    }

    public function removeLine(int $index): void
    {
        array_splice($this->lines, $index, 1);
        $this->recalculate();
    }

    public function updatedLines(): void
    {
        $this->recalculate();
    }

    public function updatedDiscountAmount(): void
    {
        $this->recalculate();
    }

    public function recalculate(): void
    {
        $service = app(QuotationService::class);
        $totals = $service->computeTotals(
            array_map(fn ($l) => [
                'quantity' => (float) ($l['quantity'] ?? 1),
                'unit_rate' => (float) ($l['unit_rate'] ?? 0),
            ], $this->lines),
            [
                'discount_amount' => (float) $this->discount_amount,
                'service_type' => $this->service_type,
                'customer_state' => $this->customer_state ?: null,
            ],
        );

        $this->totals = [
            'subtotal' => $totals['subtotal']->toDecimalString(),
            'discount_amount' => $totals['discount_amount']->toDecimalString(),
            'cgst' => $totals['cgst']->toDecimalString(),
            'sgst' => $totals['sgst']->toDecimalString(),
            'igst' => $totals['igst']->toDecimalString(),
            'grand_total' => $totals['grand_total']->toDecimalString(),
        ];
    }

    public function save(QuotationService $service): void
    {
        abort_unless(auth()->user()?->can('update', $this->quotation), 403);

        $service->addVersion($this->quotation, $this->lines, [
            'discount_amount' => (float) $this->discount_amount,
            'service_type' => $this->service_type,
            'customer_state' => $this->customer_state ?: null,
            'terms' => $this->terms,
            'customer_notes' => $this->customer_notes,
        ], auth()->user());

        session()->flash('status', 'Quotation version saved.');
        $this->redirect(route('admin.trips.show', $this->quotation->trip->ulid), navigate: true);
    }

    public function saveAndSend(QuotationService $service): void
    {
        $this->save($service);

        $version = $this->quotation->refresh()->currentVersion;
        if ($version) {
            $service->markSent($version, auth()->user());
        }
    }

    /**
     * @return Collection<int, Vendor>
     */
    #[Computed]
    public function vendors(): Collection
    {
        return Vendor::query()->orderBy('name')->get();
    }

    public function render(): View
    {
        return view('livewire.admin.trips.quotation-editor');
    }
}
