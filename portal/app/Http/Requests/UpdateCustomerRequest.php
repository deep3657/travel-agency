<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation for editing an existing customer (LLD §6.1).
 *
 * Phone and email uniqueness rules use `ignore` so the current row's values
 * don't trigger a false conflict.
 */
class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Customer $customer */
        $customer = $this->route('customer');

        return $this->user()->can('update', $customer);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        /** @var Customer $customer */
        $customer = $this->route('customer');

        return [
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\- ]{8,20}$/', "unique:customers,phone,{$customer->id}"],
            'alt_phone' => ['nullable', 'string', 'regex:/^[0-9+\- ]{8,20}$/'],
            'email' => ['required', 'email:rfc', 'max:190', "unique:customers,email,{$customer->id}"],
            'gstin' => ['nullable', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'],
            'company_name' => ['required_with:gstin', 'nullable', 'string', 'max:190'],
            'pan' => ['nullable', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/'],
            'address_line1' => ['nullable', 'string', 'max:190'],
            'address_line2' => ['nullable', 'string', 'max:190'],
            'city' => ['nullable', 'string', 'max:80'],
            'state' => ['nullable', 'string', 'max:80'],
            'pincode' => ['nullable', 'regex:/^[0-9]{6}$/'],
            'country' => ['nullable', 'string', 'max:80'],
            'dob' => ['nullable', 'date', 'before:today'],
            'anniversary' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
        ];
    }
}
