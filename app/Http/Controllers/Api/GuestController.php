<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuestRequest;
use App\Models\Guest;
use App\Services\PhoneNumberServiceInterface;

class GuestController extends Controller
{
    public function __construct(private PhoneNumberServiceInterface $phoneNumberService)
    {
        //
    }

    public function index()
    {
        $guests = Guest::all();
        return response()->json($guests);
    }

    public function store(GuestRequest $request)
    {
        $validated = $request->validated();

        if (empty($validated['country_code'])) {
            $validated['country_code'] = $this->phoneNumberService->getCountryCode($validated['phone_number']);
        }

        $guest = Guest::create($validated);

        return response()->json($guest, 201);

    }

    public function show(Guest $guest)
    {
        return response()->json($guest);
    }

    public function update(GuestRequest $request, Guest $guest)
    {
        $validated = $request->validated();
        $guest->update($validated);

        return response()->json($guest);
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return response()->json(null, 204);
    }
}
