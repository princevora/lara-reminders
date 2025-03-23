<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Owner, Venue>
     */
    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VenueRequest::class);
    }
}
