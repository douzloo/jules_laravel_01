<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Good to add if you might use factories later
use Illuminate\Database\Eloquent\Model;

class LandingPageVisit extends Model
{
    use HasFactory; // Corresponding use statement

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'landing_page_visits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ip_address',
        'user_agent',
        'visited_at',
    ];

    /**
     * Indicates if the model should be timestamped with created_at and updated_at.
     * The 'visited_at' column is handled separately.
     *
     * @var bool
     */
    public $timestamps = true; // Default, but explicit. Migration adds them.
}
