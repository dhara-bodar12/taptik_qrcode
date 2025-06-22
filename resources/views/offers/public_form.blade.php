<!-- @extends('layouts.guest')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Claim Your Offer: {{ $offer->type }}</h2>

    @if(session('success'))
        <p class="mb-4 text-green-600">{{ session('success') }}</p>
    @endif

    <form action="{{ route('offers.public.submit', $offer->id) }}" method="POST">
        @csrf

        @foreach($offer->fields as $field)
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">{{ $field['label'] }}</label>

                @if(in_array($field['type'], ['text', 'email', 'number', 'date', 'tel', 'url', 'password']))
                    <input type="{{ $field['type'] }}" name="{{ $field['name'] }}"
                           class="w-full border rounded px-3 py-2"
                           {{ $field['required'] ? 'required' : '' }}>
                @elseif($field['type'] === 'textarea')
                    <textarea name="{{ $field['name'] }}" class="w-full border rounded px-3 py-2"
                              {{ $field['required'] ? 'required' : '' }}></textarea>
                @elseif(in_array($field['type'], ['radio', 'checkbox']))
                    @foreach($field['options'] ?? [] as $opt)
                        <label class="inline-flex items-center mr-4">
                            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}{{ $field['type'] === 'checkbox' ? '[]' : '' }}"
                                   value="{{ $opt['value'] ?? $opt['label'] }}"
                                   class="mr-1">
                            {{ $opt['label'] }}
                        </label>
                    @endforeach
                @endif
            </div>
        @endforeach

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Submit
        </button>
    </form>
</div>
@endsection
 -->

 @extends('layouts.guest')

@section('content')
<div id="app">
    <offer-form
        :offer='@json($offer)'
        submit-url="{{ route('offers.public.submit', $offer->id) }}">
    </offer-form>
</div>
@endsection