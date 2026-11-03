
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Profile Saya
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 px-4">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">
                Informasi Pengguna
            </h3>

            <p class="mb-2">
                <strong>Nama:</strong>
                {{ Auth::user()->name }}
            </p>

            <p class="mb-2">
                <strong>Email:</strong>
                {{ Auth::user()->email }}
            </p>

            <p>
                <strong>Role:</strong>
                {{ Auth::user()->role }}
            </p>
        </div>
    </div>
</x-app-layout>
