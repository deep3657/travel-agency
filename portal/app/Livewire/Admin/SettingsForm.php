<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\AgencySetting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

/**
 * Admin-only settings form (LLD §9.6). M1 implements the "Agency" tab; other
 * tabs (GST rates, AI extraction, Reminders, Branding, Document templates,
 * Email templates) are placeholders that wire up in later milestones.
 *
 * Authorization is enforced at three layers:
 *   1. Route middleware: `role:admin` (bootstrap/app.php alias).
 *   2. Component mount(): hard 403 if non-admin slips through.
 *   3. save(): re-checks before mutating.
 */
class SettingsForm extends Component
{
    public string $activeTab = 'agency';

    #[Validate('required|string|max:190')]
    public string $agency_name = '';

    #[Validate('nullable|string|max:190')]
    public ?string $agency_legal_name = null;

    #[Validate('nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/')]
    public ?string $gstin = null;

    #[Validate('nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/')]
    public ?string $pan = null;

    #[Validate('nullable|string|max:80')]
    public ?string $state = null;

    #[Validate('nullable|string|max:500')]
    public ?string $address = null;

    #[Validate('nullable|string|max:80')]
    public ?string $city = null;

    #[Validate('nullable|regex:/^[0-9]{6}$/')]
    public ?string $pincode = null;

    #[Validate('nullable|string|max:20')]
    public ?string $phone = null;

    #[Validate('nullable|email:rfc|max:190')]
    public ?string $email = null;

    #[Validate('nullable|url|max:190')]
    public ?string $website = null;

    public bool $saved = false;

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);

        $s = AgencySetting::singleton();

        $this->agency_name = (string) $s->agency_name;
        $this->agency_legal_name = $s->agency_legal_name;
        $this->gstin = $s->gstin;
        $this->pan = $s->pan;
        $this->state = $s->state;
        $this->address = $s->address;
        $this->city = $s->city;
        $this->pincode = $s->pincode;
        $this->phone = $s->phone;
        $this->email = $s->email;
        $this->website = $s->website;
    }

    public function save(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);

        $data = $this->validate();

        $s = AgencySetting::singleton();
        $s->fill($data);
        $s->updated_by_id = Auth::id();
        $s->save();

        $this->saved = true;
        $this->dispatch('agency-settings.saved');
    }

    public function render(): View
    {
        return view('livewire.admin.settings-form');
    }
}
