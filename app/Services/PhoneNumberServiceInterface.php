<?php

namespace App\Services;

interface PhoneNumberServiceInterface
{
    public function validateNumber(string $number): bool;
    public function getCountryCode(string $number): string;
}
