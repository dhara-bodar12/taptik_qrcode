{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-semibold text-gray-800">Dashboard</h2>
@endsection

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold">Welcome, {{ Auth::user()->name }}</h3>
        <p class="text-sm text-gray-600 mt-2">You are logged in as an admin.</p>
    </div>
@endsection
