<?php

namespace App\Rules;

use App\Services\PhoneNumberServiceInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneCountryMatch implements ValidationRule
{
    private PhoneNumberServiceInterface $phoneNumberService;
    private string $passedPhoneNumber;

    /**
     * Create a new PhoneCountryMatch instance.
     *
     * @param PhoneNumberServiceInterface $phoneNumberService
     * @param string|null $passedPhoneNumber
     */
    public function __construct(PhoneNumberServiceInterface $phoneNumberService, string $passedPhoneNumber)
    {
        $this->phoneNumberService = $phoneNumberService;
        $this->passedPhoneNumber = $passedPhoneNumber;
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
        if (empty($value)) {
            return;
        }

        if ($value !== $this->phoneNumberService->getCountryCode($this->passedPhoneNumber)) {
            $fail('The passed country code does not match the country code of the passed phone number.');
        }
    }
}
