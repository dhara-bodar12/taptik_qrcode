@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold text-gray-800">Offer Claims</h2>
@endsection

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold">Submitted Offer Claims</h3>
    </div>

    @if($claims->isEmpty())
        <p class="text-gray-500">No offer claims submitted.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full table-auto border text-sm">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-2">#</th>
                        <th class="p-2">Offer</th>
                        <th class="p-2">User</th>
                        <th class="p-2">Submitted Data</th>
                        <th class="p-2">Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($claims as $claim)
                        <tr class="border-t">
                            <td class="p-2">{{ $loop->iteration + ($claims->currentPage() - 1) * $claims->perPage() }}</td>
                            <td class="p-2">{{ $claim->offer->value ?? 'N/A' }}</td>
                            <td class="p-2">{{ $claim->user->name ?? 'Guest' }}</td>
                            <td class="p-2">
                                <details class="bg-gray-50 p-2 rounded">
                                    <summary class="cursor-pointer text-blue-600">View</summary>
                                    <pre class="whitespace-pre-wrap text-xs mt-2">{{ json_encode($claim->submitted_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </details>
                            </td>
                            <td class="p-2">{{ $claim->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $claims->links() }}
        </div>
    @endif
</div>
@endsection
