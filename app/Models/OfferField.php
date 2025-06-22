<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferField extends Model
{
    use HasFactory;

    protected $fillable = ['offer_id', 'label', 'name', 'type', 'required', 'options'];

    protected $casts = [
        'required' => 'boolean',
        'options' => 'array',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
