<?php

namespace App\Services;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberService implements PhoneNumberServiceInterface
{
    /**
     * Create a new ValidPhoneNumber instance.
     *
     * @param PhoneNumberUtil $phoneNumberUtil An instance of PhoneNumberUtil for handling phone number operations.
     */
    public function __construct(private PhoneNumberUtil $phoneNumberUtil) {
        //
    }

    /**
     * Check if a phone number is valid.
     *
     * @param string $phoneNumber The phone number to check.
     *
     * @return bool true if the phone number is valid, false otherwise.
     */
    public function validateNumber(string $phoneNumber): bool
    {
        try {
            return $this->phoneNumberUtil->isPossibleNumber($phoneNumber);
        } catch (NumberParseException $e) {
            return false;
        }
    }

    /**
     * Return the country code from a given phone number.
     *
     * @param string $phoneNumber The phone number to get the country code from.
     *
     * @return string The country code or an empty string if the phone number is invalid.
     */
    public function getCountryCode(string $phoneNumber): string
    {
        try {
            $phoneNumberObject = $this->phoneNumberUtil->parse($phoneNumber);

            return $this->phoneNumberUtil->getRegionCodeForNumber($phoneNumberObject);
        } catch (NumberParseException $e) {
            return '';
        }
    }
}
