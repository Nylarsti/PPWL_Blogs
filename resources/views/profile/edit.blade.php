<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')

                    <section class="mt-8 border-t border-gray-200 pt-6">
                        <h2 class="text-lg font-semibold">Foto Profil</h2>
                        <p class="mt-1 text-sm text-gray-500">Ukuran minimal 200 × 200 piksel.</p>

                        @if ($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}"
                                alt="Foto profil"
                                class="mt-4 h-24 w-24 rounded-full object-cover">
                        @endif

                        <form action="{{ route('profile.photo.update') }}"
                            method="POST" enctype="multipart/form-data"
                            class="mt-4 space-y-3">
                            @csrf
                            @method('PATCH')

                            <label for="photo" class="block text-sm font-medium">Pilih foto</label>
                            <input id="photo" type="file" name="photo"
                                accept=".jpg,.jpeg,.png,.webp" required>

                            @error('photo')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div>
                                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">
                                    Simpan Foto
                                </button>
                            </div>

                            @if (session('status') === 'photo-updated')
                                <p class="text-sm text-green-600">Foto profil berhasil disimpan.</p>
                            @endif
                        </form>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
