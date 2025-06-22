@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold text-gray-800">Offer Details</h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow space-y-4">

    <div>
        <strong>Offer Type:</strong> {{ $offer->type }}
    </div>

    <div>
        <strong>Success Message:</strong> {{ $offer->success_message }}
    </div>

    <div>
        <strong>QR Code:</strong>
       
        <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($url) }}&size=200x200" alt="QR Code">

    </div>

    <div>
        <h3 class="text-lg font-semibold mt-6 mb-2">Fields</h3>
        <ul class="list-disc pl-5">
            @foreach($offer->fields as $field)
                <li>
                    <strong>{{ $field->label }}</strong> ({{ $field->type }})
                    @if($field->required)
                        <span class="text-red-500">(Required)</span>
                    @endif
                    @if(in_array($field->type, ['radio', 'checkbox']) && is_array($field->options))
                        <ul class="pl-5 list-square text-sm text-gray-600">
                            @foreach($field->options as $option)
                                <li>{{ $option['label'] }} ({{ $option['value'] }})</li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

</div>
@endsection


