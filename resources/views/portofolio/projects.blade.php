@extends('layouts.app')

@section('content')
<main class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <a href="{{ route('projects.index') }}"
        class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-indigo-700">
        <span aria-hidden="true">&larr;</span>
        Kembali ke daftar project
    </a>

    <article class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between gap-4 p-6 sm:p-8">
            <div class="min-w-0 flex-1">
                <h1 class="break-words text-3xl font-bold tracking-tight text-slate-900">
                    {{ $project->title }}
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Kategori: {{ $project->category?->name ?: 'Tanpa kategori' }}
                </p>
            </div>

            @if (auth()->user()?->role === 'admin')
                <a href="{{ route('post.projects.edit', $project) }}"
                    class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-indigo-600
                           px-3 py-2.5 text-sm font-semibold text-white shadow-sm
                           transition-colors hover:bg-indigo-700 sm:px-4">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L9 17l-4 1 1-4Z" />
                    </svg>
                    Edit Project
                </a>
            @endif
        </div>

        @if ($project->thumbnail)
            <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}"
                class="max-h-[36rem] w-full bg-slate-100 object-cover">
        @else
            <div class="mx-6 flex aspect-[16/9] items-center justify-center rounded-lg bg-slate-100 text-sm text-slate-400 sm:mx-8">
                Thumbnail belum tersedia
            </div>
        @endif

        <div class="space-y-8 p-6 sm:p-8">
            @if ($project->description)
                <section>
                    <h2 class="text-lg font-semibold text-slate-900">Tentang Project</h2>
                    <p class="mt-3 whitespace-pre-line leading-7 text-slate-600">{{ $project->description }}</p>
                </section>
            @endif

            <section>
                <h2 class="text-lg font-semibold text-slate-900">Gallery</h2>
                @if ($project->images->isEmpty())
                    <p class="mt-3 text-sm text-slate-500">Belum ada gambar galeri.</p>
                @else
                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach ($project->images as $image)
                            <figure class="overflow-hidden rounded-lg bg-slate-100">
                                <img src="{{ asset('storage/' . $image->image) }}"
                                    alt="{{ $image->caption ?: $project->title }}"
                                    class="aspect-[4/3] w-full object-cover">
                                @if ($image->caption)
                                    <figcaption class="p-3 text-sm text-slate-600">{{ $image->caption }}</figcaption>
                                @endif
                            </figure>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </article>
</main>

<footer class="bg-slate-900 py-5 text-center text-sm text-slate-300">
    &copy; {{ date('Y') }} MyProject. All rights reserved.
</footer>
@endsection