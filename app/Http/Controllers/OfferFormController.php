<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\OfferClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferFormController extends Controller
{
    public function show(Offer $offer)
    {
    	$offer->load('fields');
        return view('offers.public_form', compact('offer'));
    }

    public function submit(Request $request, Offer $offer)
	{
		$today = now()->toDateString();

		    if (
		        ($offer->valid_from && $today < $offer->valid_from) ||
		        ($offer->valid_until && $today > $offer->valid_until)
		    ) {
		        return response()->json([
		            'message' => 'This offer is not currently valid.',
		        ], 422); // Unprocessable Entity
		    }
		
	    $fields = $offer->fields ?? [];

	    $rules = [];

	    foreach ($fields as $field) {
	        $fieldRules = [];

	        if (!empty($field['required'])) {
	            $fieldRules[] = 'required';
	        } else {
	            $fieldRules[] = 'nullable';
	        }

	        // Type-specific validation
	        switch ($field['type']) {
	            case 'email':
	                $fieldRules[] = 'email';
	                break;
	            case 'number':
	                $fieldRules[] = 'numeric';
	                break;
	            case 'url':
	                $fieldRules[] = 'url';
	                break;
	            case 'file':
	                $fieldRules[] = 'file';
	                break;
	            case 'image':
	                $fieldRules[] = 'image';
	                break;
	        }

	        $rules["submitted_data.{$field['name']}"] = $fieldRules;
	    }

	    // Base validation
	    $validated = $request->validate(array_merge([
	        'user_id' => 'nullable|integer',
	        'submitted_data' => 'required|array',
	    ], $rules));

	    $submitted = [];

	    foreach ($fields as $field) {
	        $name = $field['name'];

	        if (in_array($field['type'], ['file', 'image']) && $request->hasFile("submitted_data.{$name}")) {
	            $file = $request->file("submitted_data.{$name}");
	            $path = $file->store("offers/{$offer->id}", 'public'); // stored in storage/app/public/offers/{id}
	            $submitted[$name] = $path;
	        } else {
	            $submitted[$name] = $validated['submitted_data'][$name] ?? null;
	        }
	    }

	    OfferClaim::create([
	        'offer_id' => $offer->id,
	        'user_id' => $validated['user_id'] ?? null,
	        'submitted_data' => $submitted,
	    ]);

	    return response()->json([
	        'message' => $offer->success_message ?? 'Offer submitted!',
	    ]);
	}
}
