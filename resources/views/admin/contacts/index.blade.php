
@extends('layouts.app')

@section('title', 'Admin Contacts')

@section('content')
<div class="container mx-auto px-4 py-8">

    <h1 class="text-3xl font-bold mb-6">
        Messages
    </h1>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Subjek</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($contacts as $contact)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            {{ $contact->name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $contact->email }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $contact->subject ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            @if ($contact->is_read)
                                <span class="text-green-600 font-semibold">
                                    Sudah dibaca
                                </span>
                            @else
                                <span class="text-red-600 font-semibold">
                                    Belum dibaca
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            {{ $contact->created_at->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-4 py-3">
                            <a href="{{ route('post.contacts.show', $contact->id) }}"
                               class="text-blue-600 hover:underline">
                                Lihat Pesan
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center">
                            Belum ada pesan masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <div class="mt-4">
        {{ $contacts->links() }}
    </div>

</div>
@endsection
