<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferFormController extends Controller
{
    public function show(Offer $offer)
	{
	    return view('offers.public_form', compact('offer'));
	}

	public function submit(Request $request, Offer $offer)
	{
	    $validated = [];

	    foreach ($offer->fields as $field) {
	        $rules = [];

	        if ($field['required']) $rules[] = 'required';

	        switch ($field['type']) {
	            case 'email': $rules[] = 'email'; break;
	            case 'number': $rules[] = 'numeric'; break;
	            case 'url': $rules[] = 'url'; break;
	            // Add more validations as needed
	        }

	        $validated[$field['name']] = $rules;
	    }

	    $data = $request->validate($validated);

	    // Save submission logic here (optional)

	    return redirect()->back()->with('success', $offer->success_message ?? 'Submitted!');
	}
}
