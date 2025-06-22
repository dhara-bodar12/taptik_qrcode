@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold text-gray-800">Offers</h2>
@endsection

@section('content')
    <div class="bg-white p-6 rounded shadow">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-bold">Offer List</h3>
            <a href="{{ route('admin.offers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Add Offer
            </a>
        </div>

        @if($offers->isEmpty())
            <p class="text-gray-500">No offers available.</p>
        @else
            <table class="w-full table-auto border text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left">#</th>
                        <th class="p-2 text-left">Type</th>
                        <th class="p-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($offers as $offer)
                        <tr class="border-t">
                            <td class="p-2">{{ $loop->iteration }}</td>
                            <td class="p-2">{{ $offer->type }}</td>
                            <td class="p-2 space-x-2">
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
        @endif
    </div>
@endsection
