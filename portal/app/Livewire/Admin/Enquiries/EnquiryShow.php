<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Enquiries;

use App\Models\Enquiry;
use App\Models\EnquiryReplyTemplate;
use App\Models\User;
use App\Services\EnquiryService;
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
        $this->enquiry = Enquiry::with(['customer', 'assignedUser', 'notes.author', 'package'])
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
