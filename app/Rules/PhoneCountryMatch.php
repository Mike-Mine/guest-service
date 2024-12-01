<?php

namespace App\Rules;

use App\Services\PhoneNumberServiceInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneCountryMatch implements ValidationRule
{
    private PhoneNumberServiceInterface $phoneNumberService;
    private ?string $passedCountryCode;

    /**
     * Create a new PhoneCountryMatch instance.
     *
     * @param PhoneNumberServiceInterface $phoneNumberService
     * @param string|null $passedCountryCode
     */
    public function __construct(PhoneNumberServiceInterface $phoneNumberService, ?string $passedCountryCode)
    {
        $this->phoneNumberService = $phoneNumberService;
        $this->passedCountryCode = $passedCountryCode;
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
        if (empty($this->passedCountryCode)) {
            return;
        }

        if ($this->passedCountryCode !== $this->phoneNumberService->getCountryCode($value)) {
            $fail('The passed country code does not match the country code of the passed phone number.');
        }
    }
}
