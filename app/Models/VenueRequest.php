<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class VenueRequest extends Model
{
    protected $fillable = [
        'user_id',
        'owner_id',
        'read_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venue(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}
