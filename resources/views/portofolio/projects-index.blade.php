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

    @if ($projects->isEmpty())
        <div class="rounded-xl border border-slate-200 bg-white px-6 py-16 text-center">
            <h2 class="text-lg font-semibold text-slate-800">Belum ada project</h2>
            <p class="mt-2 text-sm text-slate-500">Project yang ditambahkan akan tampil di halaman ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            @foreach ($projects as $project)
                <a href="{{ route('projects.show', $project) }}"
                    class="group overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="aspect-[16/9] overflow-hidden bg-slate-100">
                        @if ($project->thumbnail)
                            <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}"
                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                        @else
                            <div class="flex h-full items-center justify-center text-sm text-slate-400">
                                Thumbnail belum tersedia
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <h2 class="text-lg font-semibold text-slate-900 group-hover:text-indigo-700">
                            {{ $project->title }}
                        </h2>
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
