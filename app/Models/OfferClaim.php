<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferClaim extends Model
{
    use HasFactory;

    protected $fillable = ['offer_id', 'user_id', 'submitted_data'];

    protected $casts = [
        'submitted_data' => 'array',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
