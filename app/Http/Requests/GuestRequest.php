<?php

namespace App\Http\Requests;

use App\Rules\PhoneCountryMatch;
use App\Rules\ValidPhoneNumber;
use App\Services\PhoneNumberServiceInterface;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="GuestRequest",
 *     type="object",
 *     title="Guest Request",
 *     required={"first_name", "last_name", "phone_number"},
 *     properties={
 *         @OA\Property(property="first_name", type="string", maxLength=255, example="John"),
 *         @OA\Property(property="last_name", type="string", maxLength=255, example="Doe"),
 *         @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *         @OA\Property(property="phone_number", type="string", example="+1234567890", description="Must start with + and be a valid phone number."),
 *         @OA\Property(property="country_code", type="string", maxLength=2, nullable=true, example="US", description="Must match the phone number's country."),
 *     }
 * )
 */
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
                'nullable',
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
                $this->input('phone_number')
            ),
            function ($input) use ($validator) {
                return $validator->errors()->missing(['phone_number', 'country_code']);
            }
        );
    }

    /**
     * Get the validated data from the request, update the country code if not provided.
     *
     * @param  array|int|string|null  $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if (empty($validated['country_code'])) {
            $validated['country_code'] = $this->phoneNumberService->getCountryCode($validated['phone_number']);
        }

        return $validated;
    }
}
