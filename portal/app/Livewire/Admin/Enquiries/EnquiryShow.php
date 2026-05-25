<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Enquiries;

use App\Models\Enquiry;
use App\Models\EnquiryReplyTemplate;
use App\Models\User;
use App\Services\EnquiryService;
use App\Services\TripService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class EnquiryShow extends Component
{
    public Enquiry $enquiry;

    public string $newNote = '';

    public string $newStatus = '';

    public ?int $assignUserId = null;

    public ?int $selectedTemplateId = null;

    public function mount(string $ulid): void
    {
        $this->enquiry = Enquiry::with(['customer', 'assignedUser', 'notes.author', 'package', 'trip'])
            ->where('ulid', $ulid)
            ->firstOrFail();

        abort_unless(auth()->user()?->can('view', $this->enquiry), 403);

        $this->newStatus = $this->enquiry->status;
        $this->assignUserId = $this->enquiry->assigned_user_id;
    }

    public function addNote(EnquiryService $service): void
    {
        $this->validate(['newNote' => 'required|string|min:1']);

        $service->addNote($this->enquiry, $this->newNote, auth()->user());
        $this->newNote = '';
        $this->enquiry->refresh()->load('notes.author');
    }

    public function changeStatus(EnquiryService $service): void
    {
        $service->changeStatus($this->enquiry, $this->newStatus, auth()->user());
        $this->enquiry->refresh();
        session()->flash('status', 'Status updated.');
    }

    public function assignAgent(EnquiryService $service): void
    {
        if ($this->assignUserId) {
            $service->assign($this->enquiry, $this->assignUserId, auth()->user());
            $this->enquiry->refresh()->load('assignedUser');
            session()->flash('status', 'Agent assigned.');
        }
    }

    /**
     * Convert this enquiry into a Trip, pre-populated with details captured
     * during the enquiry stage. The admin can refine the trip on the next
     * screen (rename, attach quotations, change agent, etc.).
     */
    public function convertToTrip(TripService $service): mixed
    {
        $user = auth()->user();
        abort_unless($user?->can('update', $this->enquiry), 403);

        if ($this->enquiry->converted_to_trip_id !== null) {
            session()->flash('warning', 'This enquiry has already been converted to a trip.');

            return null;
        }

        $customer = $this->enquiry->customer;
        $destination = $this->enquiry->destination ?: 'New destination';
        $startLabel = $this->enquiry->travel_from?->format('M Y');
        $tripName = trim("{$customer->name} · {$destination}".($startLabel ? " · {$startLabel}" : ''));

        $notes = $this->enquiry->special_requirements
            ? "From enquiry {$this->enquiry->ulid}:\n\n{$this->enquiry->special_requirements}"
            : "Converted from enquiry {$this->enquiry->ulid}.";

        $trip = $service->convertFromEnquiry(
            $this->enquiry,
            [
                'name' => mb_substr($tripName, 0, 190),
                'primary_destination' => $this->enquiry->destination,
                'travel_start' => $this->enquiry->travel_from,
                'travel_end' => $this->enquiry->travel_to,
                'assigned_user_id' => $this->enquiry->assigned_user_id,
                'status' => 'planning',
                'notes' => $notes,
            ],
            $user,
        );

        // Move the enquiry along so it's clear it's been actioned.
        if (! in_array($this->enquiry->status, ['quoted', 'closed'], true)) {
            $this->enquiry->update(['status' => 'in_progress']);
        }

        session()->flash('status', "Trip created: {$trip->name}");

        return $this->redirectRoute('admin.trips.show', ['ulid' => $trip->ulid], navigate: false);
    }

    /**
     * @return Collection<int, User>
     */
    public function getAgentsProperty(): Collection
    {
        return User::query()
            ->where('user_type', User::TYPE_STAFF)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * @return Collection<int, EnquiryReplyTemplate>
     */
    public function getTemplatesProperty(): Collection
    {
        return EnquiryReplyTemplate::query()->where('is_active', true)->get();
    }

    public function render(): View
    {
        return view('livewire.admin.enquiries.enquiry-show');
    }
}
