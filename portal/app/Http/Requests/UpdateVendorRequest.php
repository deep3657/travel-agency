<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Vendor $vendor */
        $vendor = $this->route('vendor');

        return $this->user()->can('update', $vendor);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        /** @var Vendor $vendor */
        $vendor = $this->route('vendor');

        return [
            'name' => ['required', 'string', 'max:190'],
            'code' => ['nullable', 'string', 'max:20', "unique:vendors,code,{$vendor->id}"],
            'contact_person' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email:rfc', 'max:190'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9+\- ]{8,20}$/'],
            'gstin' => ['nullable', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'],
            'address' => ['nullable', 'string', 'max:1000'],
            'payment_terms_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
