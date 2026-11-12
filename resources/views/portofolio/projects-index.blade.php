@extends('layouts.app')

@section('content')
<main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-end justify-between gap-4">
        <div>
            <p class="mb-2 text-sm font-medium uppercase tracking-wider text-slate-500">Portofolio</p>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Projects</h1>
        </div>

        @auth
            <a href="{{ route('post.projects.create') }}"
                class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                Tambah Project
            </a>
        @endauth
    </div>

    <form method="GET" action="{{ route($projectsIndexRoute) }}" class="mb-6 flex flex-col gap-3 sm:flex-row">
        <input type="search" name="search" value="{{ request('search') }}"
            placeholder="Cari project..." aria-label="Cari project"
            class="rounded-lg border-slate-300 sm:flex-1">

        <select name="category" aria-label="Kategori"
                class="rounded-lg border-slate-300">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->slug }}"
                        @selected(request('category') === $category->slug)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="technology" aria-label="Teknologi" class="rounded-lg border-slate-300">
            <option value="">Semua teknologi</option>
            @foreach ($technologies as $technology)
                <option value="{{ $technology->slug }}"
                        @selected(request('technology') === $technology->slug)>
                    {{ $technology->name }}
                </option>
            @endforeach
        </select>

        <select name="sort" aria-label="Urutan proyek" class="rounded-lg border-slate-300">
            <option value="terbaru" @selected(request('sort', 'terbaru') === 'terbaru')>Terbaru</option>
            <option value="terlama" @selected(request('sort') === 'terlama')>Terlama</option>
            <option value="az" @selected(request('sort') === 'az')>Judul A–Z</option>
            <option value="project_date" @selected(request('sort') === 'project_date')>
                Tanggal Proyek Terbaru
            </option>
        </select>

        @if ($isAdminProjectList)
            <select name="status" aria-label="Status proyek" class="rounded-lg border-slate-300">
                <option value="">Semua status</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
            </select>
        @endif

        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-white">
            Cari
        </button>

        <a href="{{ route($projectsIndexRoute) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-center text-slate-700 hover:bg-slate-50">
            Reset
        </a>
    </form>

    <p class="mb-4 text-sm text-slate-600">
        <span class="font-semibold">{{ $projects->total() }}</span> hasil ditemukan
    </p>

    @if ($projects->isEmpty())
        <div class="rounded-xl border border-slate-200 bg-white px-6 py-16 text-center">
            <h2 class="text-lg font-semibold text-slate-800">Belum ada project</h2>
            <p class="mt-2 text-sm text-slate-500">Project yang sudah dipublikasikan akan tampil di halaman ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            @foreach ($projects as $project)
                <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <a href="{{ route('projects.show', $project) }}" class="group block">
                        <div class="relative aspect-[16/9] overflow-hidden bg-slate-100">
                            @if ($project->thumbnail)
                                <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}"
                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            @else
                                <div class="flex h-full items-center justify-center text-sm text-slate-400">
                                    Thumbnail belum tersedia
                                </div>
                            @endif
                            @if (auth()->user()?->role === 'admin' && $project->status === 'draft')
                                <span class="absolute left-3 top-3 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                                    Draft
                                </span>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                <h2 class="min-w-0 break-words text-lg font-semibold text-slate-900 group-hover:text-indigo-700">
                                    {{ $project->title }}
                                </h2>

                                @if ($project->project_date)
                                    @php
                                        $projectDate = \Illuminate\Support\Carbon::parse($project->project_date)->locale('id');
                                    @endphp

                                    <time datetime="{{ $projectDate->toDateString() }}"
                                        class="whitespace-nowrap text-sm text-slate-500">
                                        {{ $projectDate->translatedFormat('j F Y') }}
                                    </time>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $project->category?->name ?: 'Tanpa kategori' }}
                            </p>
                            @if ($project->description)
                                <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">
                                    {{ $project->description }}
                                </p>
                            @endif
                        </div>
                    </a>
                    @if (auth()->user()?->role === 'admin')
                        <div class="border-t border-slate-100 px-5 py-3">
                            <a href="{{ route('post.projects.edit', $project) }}"
                                class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">
                                Edit project
                            </a>
                        </div>
                    @endif
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $projects->links() }}
        </div>
    @endif
</main>

<footer class="bg-slate-900 py-5 text-center text-sm text-slate-300">
    &copy; {{ date('Y') }} MyProject. All rights reserved.
</footer>
@endsection
