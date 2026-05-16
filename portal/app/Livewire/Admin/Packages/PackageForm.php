<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Packages;

use App\Models\Package;
use App\Services\PackageService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PackageForm extends Component
{
    public ?Package $package = null;

    public bool $isEdit = false;

    #[Validate('required|string|max:190')]
    public string $title = '';

    #[Validate('required|string|max:180|regex:/^[a-z0-9-]+$/')]
    public string $slug = '';

    #[Validate('required|string|max:255')]
    public string $destinations = '';

    #[Validate('nullable|string|max:120')]
    public ?string $departure_city = null;

    #[Validate('required|integer|min:1|max:30')]
    public int $duration_days = 1;

    #[Validate('required|integer|min:0|max:29')]
    public int $duration_nights = 0;

    #[Validate('required|numeric|min:0')]
    public string $price_from_inr = '0';

    #[Validate('nullable|string|max:500')]
    public ?string $short_description = null;

    #[Validate('nullable|string')]
    public ?string $long_description = null;

    #[Validate('nullable|string')]
    public ?string $inclusions_text = null;

    #[Validate('nullable|string')]
    public ?string $exclusions_text = null;

    #[Validate('nullable|string')]
    public ?string $terms = null;

    #[Validate('nullable|string|max:190')]
    public ?string $seo_meta_title = null;

    #[Validate('nullable|string|max:255')]
    public ?string $seo_meta_description = null;

    #[Validate('in:draft,active')]
    public string $status = 'draft';

    #[Validate('nullable|string|max:255')]
    public ?string $hero_image_path = null;

    public function mount(?string $ulid = null): void
    {
        if ($ulid !== null) {
            $this->package = Package::withTrashed()->where('ulid', $ulid)->firstOrFail();
            $this->isEdit = true;
            abort_unless(auth()->user()?->can('update', $this->package), 403);

            $this->title = $this->package->title;
            $this->slug = $this->package->slug;
            $this->destinations = $this->package->destinations;
            $this->departure_city = $this->package->departure_city;
            $this->duration_days = $this->package->duration_days;
            $this->duration_nights = $this->package->duration_nights;
            $this->price_from_inr = (string) $this->package->price_from_inr->toRupees();
            $this->short_description = $this->package->short_description;
            $this->long_description = $this->package->long_description;
            $this->inclusions_text = implode("\n", $this->package->inclusions ?? []);
            $this->exclusions_text = implode("\n", $this->package->exclusions ?? []);
            $this->terms = $this->package->terms;
            $this->seo_meta_title = $this->package->seo_meta_title;
            $this->seo_meta_description = $this->package->seo_meta_description;
            $this->status = $this->package->status;
            $this->hero_image_path = $this->package->hero_image_path;
        } else {
            abort_unless(auth()->user()?->can('create', Package::class), 403);
        }
    }

    public function updatedTitle(string $value): void
    {
        if (! $this->isEdit) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(PackageService $service): void
    {
        $this->validate();

        $inclusions = array_values(array_filter(
            array_map('trim', explode("\n", $this->inclusions_text ?? '')),
        ));

        $exclusions = array_values(array_filter(
            array_map('trim', explode("\n", $this->exclusions_text ?? '')),
        ));

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'destinations' => $this->destinations,
            'departure_city' => $this->departure_city,
            'duration_days' => $this->duration_days,
            'duration_nights' => $this->duration_nights,
            'price_from_inr' => (float) $this->price_from_inr,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'inclusions' => $inclusions,
            'exclusions' => $exclusions,
            'terms' => $this->terms,
            'seo_meta_title' => $this->seo_meta_title,
            'seo_meta_description' => $this->seo_meta_description,
            'status' => $this->status,
            'hero_image_path' => $this->hero_image_path,
        ];

        if ($this->isEdit && $this->package) {
            $service->update($this->package, $data, auth()->user());
            session()->flash('status', 'Package updated.');
            $this->redirect(route('admin.packages.show', $this->package->ulid), navigate: true);
        } else {
            $package = $service->create($data, auth()->user());
            session()->flash('status', 'Package created.');
            $this->redirect(route('admin.packages.show', $package->ulid), navigate: true);
        }
    }

    public function render(): View
    {
        return view('livewire.admin.packages.package-form');
    }
}
