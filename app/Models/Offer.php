<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'success_message'];

    public function fields()
    {
        return $this->hasMany(OfferField::class);
    }
}
