@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
    <div class="rounded-xl bg-white p-6 shadow-sm sm:p-8">
        <h1 class="mb-6 text-2xl font-bold text-gray-900">Tambah Project</h1>

        @if (session('success'))
            <div class="mb-5 rounded-lg bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('post.projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="title" class="mb-1 block text-sm font-medium text-gray-700">Nama Project</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" required maxlength="255"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category_id" class="mb-1 block text-sm font-medium text-gray-700">Kategori</label>
                <select id="category_id" name="category_id"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih kategori (opsional)</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @if ($categories->isEmpty())
                    <p class="mt-1 text-sm text-gray-500">Belum ada kategori. Project tetap bisa disimpan tanpa kategori.</p>
                @endif
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="thumbnail" class="mb-1 block text-sm font-medium text-gray-700">Thumbnail</label>
                <input id="thumbnail" type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp"
                    class="block w-full text-sm text-gray-700">
                <p class="mt-1 text-sm text-gray-500">JPG, PNG, atau WebP; maksimal 2 MB; ukuran minimal 400×300 px.</p>
                @error('thumbnail')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="images" class="mb-1 block text-sm font-medium text-gray-700">Gallery Project</label>
                <input id="images" type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp" multiple
                    class="block w-full text-sm text-gray-700">
                <p class="mt-1 text-sm text-gray-500">Pilih hingga 10 gambar; maksimal 4 MB per gambar; ukuran minimal 400×300 px.</p>
                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                Simpan Project
            </button>
        </form>
    </div>
</div>
@endsection