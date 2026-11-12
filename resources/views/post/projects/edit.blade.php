@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl p-6">
    <h1 class="mb-6 text-2xl font-bold">Edit Project</h1>

    <form action="{{ route('post.projects.update', $project) }}"
          method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block">Nama Project</label>
            <input id="title" name="title" value="{{ old('title', $project->title) }}"
                   class="w-full rounded-lg border-gray-300" required>
            @error('title')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block">Deskripsi</label>
            <textarea id="description" name="description" rows="4"
                class="w-full rounded-lg border-gray-300">{{ old('description', $project->description) }}</textarea>
            @error('description')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="project_date" class="mb-1 block text-sm font-medium text-gray-700">
                Tanggal Proyek
            </label>
            <input id="project_date" name="project_date" type="date"
                value="{{ old('project_date', $project->project_date ?? '') }}"
                class="w-full rounded-lg border-gray-300">

            @error('project_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="status" class="block">Status Project</label>
            <select id="status" name="status" class="w-full rounded-lg border-gray-300">
                <option value="draft" @selected(old('status', $project->status) === 'draft')>Draft</option>
                <option value="published" @selected(old('status', $project->status) === 'published')>Published</option>
            </select>
            @error('status')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        @if ($project->thumbnail)
            <img src="{{ asset('storage/' . $project->thumbnail) }}"
                 alt="Thumbnail saat ini" class="h-40 rounded-lg object-cover">
        @endif

        <div>
            <label for="thumbnail" class="block">Thumbnail baru</label>
            <input id="thumbnail" type="file" name="thumbnail"
                   accept="image/jpeg,image/png,image/webp">
            <p class="text-sm text-gray-500">Minimal 400 × 300 piksel, maksimal 2 MB.</p>
            @error('thumbnail')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button class="rounded-lg bg-indigo-600 px-4 py-2 text-white">
            Simpan Perubahan
        </button>
    </form>

    <section class="mt-8 border-t border-slate-200 pt-6">
        <h2 class="mb-4 text-lg font-bold">Tambah Gambar Galeri</h2>

        <form action="{{ route('post.projects.gallery.store', $project) }}"
            method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="gallery-images" class="mb-2 block">Pilih gambar</label>
                <input id="gallery-images" type="file" name="images[]"
                    accept="image/jpeg,image/png,image/webp" multiple required>

                @error('images')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-white">
                Upload Gambar
            </button>
        </form>
    </section>

    <section class="mt-8 border-t border-slate-200 pt-6">
        <h2 class="mb-4 text-lg font-bold">Edit Caption Galeri</h2>

        @if (session('success'))
            <p class="mb-4 text-sm text-green-700">{{ session('success') }}</p>
        @endif

        @error('caption')
            <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="grid gap-4 sm:grid-cols-2">
            @forelse ($project->images as $image)
                <div class="rounded-lg border border-slate-200 p-4">
                    <img src="{{ asset('storage/' . $image->image) }}"
                        alt="{{ $image->caption ?: $project->title }}"
                        class="mb-3 h-36 w-full rounded-lg object-cover">

                    <form action="{{ route('post.projects.gallery.update', [$project, $image]) }}"
                        method="POST" class="space-y-2">
                        @csrf
                        @method('PUT')

                        <label for="caption-{{ $image->id }}" class="block text-sm">
                            Keterangan gambar
                        </label>
                        <input id="caption-{{ $image->id }}" type="text" name="caption"
                            value="{{ $image->caption }}" maxlength="255"
                            class="w-full rounded-lg border-slate-300">

                        <button type="submit"
                                class="rounded-lg bg-indigo-600 px-3 py-2 text-sm text-white">
                            Simpan Caption
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-slate-500">Belum ada gambar galeri.</p>
            @endforelse
        </div>
    </section>

    <section class="mt-8 border-t border-slate-200 pt-6">
        <h2 class="mb-4 text-lg font-bold">Hapus Gambar Galeri</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($project->images as $image)
                <form action="{{ route('post.projects.gallery.destroy', [$project, $image]) }}"
                    method="POST" class="rounded-lg border border-slate-200 p-4">
                    @csrf
                    @method('DELETE')
                    <p class="mb-3 text-sm text-slate-600">
                        {{ $image->caption ?: 'Gambar #' . $image->id }}
                    </p>
                    <button type="submit"
                        class="rounded-lg bg-red-600 px-3 py-2 text-sm text-white hover:bg-red-700">
                        Hapus Gambar
                    </button>
                </form>
            @endforeach
        </div>
    </section>
</div>
@endsection