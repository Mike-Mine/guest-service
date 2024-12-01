<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuestRequest;
use App\Models\Guest;
use App\Services\PhoneNumberServiceInterface;

/**
 * @OA\Info(
 *     title="Guest API",
 *     version="1.0.0",
 *     description="API Endpoints for managing guests",
 * )
 */
class GuestController extends Controller
{
    public function __construct(private PhoneNumberServiceInterface $phoneNumberService)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/guests",
     *     tags={"Guests"},
     *     summary="Retrieve a list of guests",
     *     @OA\Response(
     *         response=200,
     *         description="List of guests retrieved successfully",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Guest"))
     *     )
     * )
     */
    public function index()
    {
        $guests = Guest::all();
        return response()->json($guests);
    }

    /**
     * @OA\Post(
     *     path="/api/guests",
     *     tags={"Guests"},
     *     summary="Create a new guest",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GuestRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Guest created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="+1234567890"),
     *             @OA\Property(property="country_code", type="string", nullable=true, example="US")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="data", type="object", example={
     *                 "phone_number": {"The phone number must be valid."},
     *                 "country_code": {"The country code does not match the phone number."}
     *             })
     *         )
     *     )
     * )
     */
    public function store(GuestRequest $request)
    {
        $validated = $request->validated();

        if (empty($validated['country_code'])) {
            $validated['country_code'] = $this->phoneNumberService->getCountryCode($validated['phone_number']);
        }

        $guest = Guest::create($validated);

        return response()->json($guest, 201);

    }

    /**
     * @OA\Get(
     *     path="/api/guests/{id}",
     *     tags={"Guests"},
     *     summary="Retrieve a specific guest by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the guest",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Guest retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Guest")
     *     ),
     *     @OA\Response(response=404, description="Guest not found")
     * )
     */
    public function show(Guest $guest)
    {
        return response()->json($guest);
    }

    /**
     * @OA\Put(
     *     path="/api/guests/{id}",
     *     tags={"Guests"},
     *     summary="Update an existing guest",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the guest",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GuestRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Guest updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="+1234567890"),
     *             @OA\Property(property="country_code", type="string", nullable=true, example="US")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="data", type="object", example={
     *                 "phone_number": {"The phone number must be valid."},
     *                 "email": {"The email has already been taken."}
     *             })
     *         )
     *     ),
     *     @OA\Response(response=404, description="Guest not found")
     * )
     */
    public function update(GuestRequest $request, Guest $guest)
    {
        $validated = $request->validated();
        $guest->update($validated);

        return response()->json($guest);
    }

    /**
     * @OA\Delete(
     *     path="/api/guests/{id}",
     *     tags={"Guests"},
     *     summary="Delete a guest",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the guest",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Guest deleted successfully"),
     *     @OA\Response(response=404, description="Guest not found")
     * )
     */
    public function destroy(Guest $guest)
    {
        $guest->delete();
        return response()->json(null, 204);
    }
}
