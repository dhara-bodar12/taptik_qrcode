<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::latest()->get();
        return view('admin.offers.index', compact('offers'));
    }

    public function show($id)
    {
        $offer = Offer::with('fields')->findOrFail($id);
        $url = route('offers.public', $offer->id); 
        $qrData = route('offers.redeem', $offer->id); // Example

        return view('admin.offers.show', compact('offer', 'qrData','url'));
    }

    public function create()
    {
        return view('admin.offers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'success_message' => 'required|string',
            'fields' => 'nullable|array',
            'fields.*.label' => 'required|string',
            'fields.*.name' => 'required|string',
            'fields.*.type' => 'required|string',
            'fields.*.required' => 'nullable|in:1',
            'fields.*.options' => 'nullable|array',
            'fields.*.options.*.label' => 'nullable|string',
            'fields.*.options.*.value' => 'nullable|string',
        ]);

        $offer = Offer::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'success_message' => $validated['success_message'],
        ]);

        foreach ($validated['fields'] ?? [] as $field) {
            $options = [];

            if (!empty($field['options']) && is_array($field['options'])) {
                foreach ($field['options'] as $opt) {
                    // Optional safety: check keys exist
                    if (!empty($opt['label']) && !empty($opt['value'])) {
                        $options[] = [
                            'label' => $opt['label'],
                            'value' => $opt['value'],
                        ];
                    }
                }
            }

            // You can dd here to confirm:
             //dd($request->all(),$options,$field);  // Should show the options array correctly

            OfferField::create([
                'offer_id' => $offer->id,
                'label' => $field['label'],
                'name' => $field['name'],
                'type' => $field['type'],
                'required' => isset($field['required']),
                'options' => $options, 
            ]);
        }

        return redirect()->route('admin.offers.index')->with('success', 'Offer created successfully.');
    }

    public function edit(Offer $offer)
    {
        // Ensure fields are loaded (if using a relationship)
        $offer->load('fields');

        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                'type' => 'required|string',
                'success_message' => 'required|string',
                'fields' => 'nullable|array',
                'fields.*.label' => 'required|string',
                'fields.*.name' => 'required|string',
                'fields.*.type' => 'required|string',
                'fields.*.required' => 'nullable|in:1',
                'fields.*.options' => 'nullable|array',
                'fields.*.options.*.label' => 'nullable|string',
                'fields.*.options.*.value' => 'nullable|string',
            ]);

        $offer = Offer::findOrFail($id);
        $offer->update([
            'type' => $validated['type'],
            'success_message' => $validated['success_message'],
        ]);

        // Delete old fields and recreate (or use a smarter sync if needed)
        $offer->fields()->delete();

        foreach ($validated['fields'] ?? [] as $field) {
            $options = [];

            if (!empty($field['options'])) {
                foreach ($field['options'] as $opt) {
                    $options[] = [
                        'label' => $opt['label'] ?? '',
                        'value' => $opt['value'] ?? '',
                    ];
                }
            }

            OfferField::create([
                'offer_id' => $offer->id,
                'label' => $field['label'],
                'name' => $field['name'],
                'type' => $field['type'],
                'required' => isset($field['required']),
                'options' => $options, // Cast to JSON
            ]);
        }

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully.');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('admin.offers.index')->with('success', 'Offer deleted!');
    }
}
