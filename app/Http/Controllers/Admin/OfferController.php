<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{   
   public function index(Request $request)
    {
        $query = Offer::query()->with('user'); 
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('type', 'like', "%$search%")
                  ->orWhere('value', 'like', "%$search%")
                  ->orWhere('success_message', 'like', "%$search%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%$search%"));
            });
        }

        if ($request->filled('valid_from')) {
            $query->whereDate('valid_from', '>=', $request->valid_from);
        }

        if ($request->filled('valid_until')) {
            $query->whereDate('valid_until', '<=', $request->valid_until);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $offers = $query->orderBy($sortBy, $sortOrder)->paginate(10)->withQueryString();

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
            'value' => 'required|string',
            'percentage' => 'nullable|integer|min:1|max:100',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'terms' => 'nullable|string',
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
            'value' => $validated['value'],
            'percentage' => $validated['percentage'] ?? null,
            'valid_from' => $validated['valid_from'] ?? null,
            'valid_until' => $validated['valid_until'] ?? null,
            'terms' => $validated['terms'] ?? null,
            'success_message' => $validated['success_message'],
        ]);

        foreach ($validated['fields'] ?? [] as $field) {
            $options = [];

            if (!empty($field['options']) && is_array($field['options'])) {
                foreach ($field['options'] as $opt) {
                    if (!empty($opt['label']) && !empty($opt['value'])) {
                        $options[] = [
                            'label' => $opt['label'],
                            'value' => $opt['value'],
                        ];
                    }
                }
            }

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
                'options' => $options, 
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
