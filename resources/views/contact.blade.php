@extends('layouts.app')

@section('title', 'Kontak')

@section('content')

<div class="max-w-xl mx-auto bg-white rounded-lg shadow-sm p-6">

    <h1 class="text-3xl font-bold mb-4">
        Hubungi Saya
    </h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('contact.store') }}" method="POST">

        @csrf

        <div class="mb-4">
            <label>Nama</label>
            <input type="text" name="name"
                value="{{ old('name') }}"
                class="w-full border rounded p-2">

            @error('name')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label>Email</label>
            <input type="email" name="email"
                value="{{ old('email') }}"
                class="w-full border rounded p-2">

            @error('email')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label>Subjek</label>
            <input type="text" name="subject"
                value="{{ old('subject') }}"
                class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label>Pesan</label>
            <textarea name="message" rows="5"
                class="w-full border rounded p-2">{{ old('message') }}</textarea>

            @error('message')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded">
            Kirim Pesan
        </button>

    </form>

</div>

@endsection