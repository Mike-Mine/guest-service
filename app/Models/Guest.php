<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Guest",
 *     type="object",
 *     title="Guest",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="first_name", type="string", example="John"),
 *         @OA\Property(property="last_name", type="string", example="Doe"),
 *         @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *         @OA\Property(property="phone_number", type="string", example="+1234567890"),
 *         @OA\Property(property="country_code", type="string", nullable=true, example="US"),
 *         @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
 *         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-02T12:00:00Z")
 *     }
 * )
 */
class Guest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'country_code',
    ];
}
