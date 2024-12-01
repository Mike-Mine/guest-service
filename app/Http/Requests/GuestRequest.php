<?php

namespace App\Http\Requests;

use App\Rules\PhoneCountryMatch;
use App\Rules\ValidPhoneNumber;
use App\Services\PhoneNumberServiceInterface;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class GuestRequest extends FormRequest
{
    private PhoneNumberServiceInterface $phoneNumberService;

    /**
     * Create a new GuestRequest instance.
     *
     * @param PhoneNumberServiceInterface $phoneNumberService Service to handle phone number operations.
     */
    public function __construct(PhoneNumberServiceInterface $phoneNumberService)
    {
        parent::__construct();
        $this->phoneNumberService = $phoneNumberService;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $guest = $this->route('guest');

        return [
            'first_name'   => [
                'required',
                'string',
                'max:255',
            ],
            'last_name'    => [
                'required',
                'string',
                'max:255',
            ],
            'email'        => [
                'required',
                'email',
                Rule::unique('guests', 'email')->ignore($guest),
            ],
            'phone_number' => [
                'required',
                'string',
                'starts_with:+',
                Rule::unique('guests', 'phone_number')->ignore($guest),
                new ValidPhoneNumber($this->phoneNumberService),
            ],
            'country_code' => [
                'nullable',
                'string',
                'max:2',
            ],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation errors',
            'data'    => $validator->errors()
        ], 422));
    }

    /**
     * Modify validation rules dynamically after initial setup.
     *
     * @param  Illuminate\Contracts\Validation\Validator  $validator
     *
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->sometimes(
            'country_code',
            new PhoneCountryMatch(
                $this->phoneNumberService,
                $this->input('country_code')
            ),
            function ($input) use ($validator) {
                return $validator->errors()->missing(['phone_number', 'country_code']);
            }
        );
    }
}
