@extends('layouts.app')

@section('title', 'Detail Pesanan - Toko Bagus')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="text-green-600 hover:text-green-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Include success template (reuse) -->
    @include('checkout.success')
</div>
@endsection
