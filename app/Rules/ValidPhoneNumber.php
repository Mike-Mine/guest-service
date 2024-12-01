<?php

namespace App\Rules;

use App\Services\PhoneNumberServiceInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    /**
     * Create a new ValidPhoneNumber instance.
     *
     * @param  PhoneNumberServiceInterface  $phoneNumberService
     */
    public function __construct(private PhoneNumberServiceInterface $phoneNumberService)
    {
        //
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  \Closure(string): void  $fail
     *
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->phoneNumberService->validateNumber($value)) {
            $fail('The passed phone number does not seem to be a valid phone number.');
        }
    }
}
