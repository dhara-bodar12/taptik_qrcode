@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold text-gray-800">Offers</h2>
@endsection

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold">Offer List</h3>
        <a href="{{ route('admin.offers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add Offer
        </a>
    </div>

    <form method="GET" action="{{ route('admin.offers.index') }}" class="mb-4 grid grid-cols-1 md:grid-cols-5 gap-4">

        <!-- Search Field -->
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search (Type, Value, Name)</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}"
                   placeholder="e.g. Discount, 20%, John"
                   class="border border-gray-300 rounded-md px-3 py-2 w-full">
        </div>

        <!-- Valid From -->
        <div>
            <label for="valid_from" class="block text-sm font-medium text-gray-700 mb-1">Valid From</label>
            <input type="date" name="valid_from" id="valid_from" value="{{ request('valid_from') }}"
                   class="border border-gray-300 rounded-md px-3 py-2 w-full">
        </div>

        <!-- Valid Until -->
        <div>
            <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-1">Valid Until</label>
            <input type="date" name="valid_until" id="valid_until" value="{{ request('valid_until') }}"
                   class="border border-gray-300 rounded-md px-3 py-2 w-full">
        </div>

        <!-- Search Button -->
        <div class="flex items-end">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 w-full">
                Search
            </button>
        </div>

        <!-- Clear Button -->
        <div class="flex items-end">
            <a href="{{ route('admin.offers.index') }}"
               class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 w-full text-center">
                Clear
            </a>
        </div>

    </form>


    @if($offers->isEmpty())
        <p class="text-gray-500">No offers available.</p>
    @else
        @php
            $currentSort = request('sort_by');
            $currentOrder = request('sort_order', 'asc');
            function sortLink($field, $label) {
                $direction = (request('sort_by') === $field && request('sort_order') === 'asc') ? 'desc' : 'asc';
                $icon = (request('sort_by') === $field) ? ($direction === 'asc' ? '▲' : '▼') : '';
                $params = array_merge(request()->query(), ['sort_by' => $field, 'sort_order' => $direction]);
                return '<a href="' . route('admin.offers.index', $params) . '" class="hover:underline">' . $label . ' ' . $icon . '</a>';
            }
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full table-auto border text-sm">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-2">#</th>
                        <th class="p-2">{!! sortLink('type', 'Type') !!}</th>
                        <th class="p-2">{!! sortLink('value', 'Value') !!}</th>
                        <th class="p-2">{!! sortLink('percentage', 'Percentage') !!}</th>
                        <th class="p-2">{!! sortLink('valid_from', 'Valid From') !!}</th>
                        <th class="p-2">{!! sortLink('valid_until', 'Valid Until') !!}</th>
                        <th class="p-2">Terms</th>
                        <th class="p-2">Success Message</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($offers as $offer)
                        <tr class="border-t">
                            <td class="p-2">{{ $loop->iteration + ($offers->currentPage() - 1) * $offers->perPage() }}</td>
                            <td class="p-2">{{ $offer->type }}</td>
                            <td class="p-2">{{ $offer->value }}</td>
                            <td class="p-2">{{ $offer->percentage ?? '-' }}</td>
                            <td class="p-2">{{ $offer->valid_from ?? '-' }}</td>
                            <td class="p-2">{{ $offer->valid_until ?? '-' }}</td>
                            <td class="p-2">{{ Str::limit($offer->terms, 30) }}</td>
                            <td class="p-2">{{ $offer->success_message }}</td>
                            <td class="p-2 space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.offers.show', $offer->id) }}" class="text-green-600 hover:underline">View</a>
                                <a href="{{ route('admin.offers.edit', $offer->id) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.offers.destroy', $offer->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Delete this offer?')" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $offers->links() }}
        </div>
    @endif
</div>
@endsection
