
@extends('layouts.app')

@section('title', 'Detail Pesan')

@section('content')
<div class="container mx-auto px-4 py-8">

    <h1 class="text-3xl font-bold mb-6">
        Detail Pesan
    </h1>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

        <div class="mb-4">
            <p class="text-gray-500 text-sm">Nama Pengirim</p>
            <p class="font-semibold">{{ $contact->name }}</p>
        </div>

        <div class="mb-4">
            <p class="text-gray-500 text-sm">Email</p>
            <p>{{ $contact->email }}</p>
        </div>

        <div class="mb-4">
            <p class="text-gray-500 text-sm">Subjek</p>
            <p>{{ $contact->subject ?? '-' }}</p>
        </div>

        <div class="mb-4">
            <p class="text-gray-500 text-sm">Isi Pesan</p>
            <p class="whitespace-pre-line">{{ $contact->message }}</p>
        </div>

        <div class="mb-6">
            <p class="text-gray-500 text-sm">Tanggal Dikirim</p>
            <p>{{ $contact->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <a href="{{ route('post.contacts.index') }}"
           class="text-blue-600 hover:underline">
            ← Kembali ke Daftar Pesan
        </a>

    </div>

</div>
@endsection
