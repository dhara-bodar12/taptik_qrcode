<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

	protected $fillable = [
	    'user_id',
	    'type',
	    'value',
	    'percentage',
	    'valid_from',
	    'valid_until',
	    'terms',
	    'success_message',
	];


    public function fields()
    {
        return $this->hasMany(OfferField::class);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}
}
