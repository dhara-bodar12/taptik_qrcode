<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferClaimController extends Controller
{
    public function index(Request $request)
    {
    	$claims = OfferClaim::orderBy('created_at', 'desc')
        ->paginate(15); 

        //dd($claims);

    return view('admin.offer_claims.index', compact('claims'));

    }
}
