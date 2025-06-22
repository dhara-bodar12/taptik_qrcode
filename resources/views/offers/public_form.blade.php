@extends('layouts.guest')

@section('content')
<div id="app">
    <offer-form
        :offer='@json($offer)'
        submit-url="{{ route('offers.public.submit', $offer->id) }}">
    </offer-form>

</div>
@endsection
