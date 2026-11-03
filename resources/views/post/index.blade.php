
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5 mb-8">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-900">
                Blog Posts
            </h1>
            <p class="text-slate-500 mt-2">
                Kelola, cari, dan lihat semua postingan kamu.
            </p>
        </div>

        <a href="{{ route('post.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
            <span class="text-lg leading-none">+</span>
            Create New Post
        </a>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Filter dan Search --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm mb-6">
        <form method="GET" action="{{ route('post.index') }}"
              class="flex flex-col md:flex-row md:items-end gap-4">

            <div class="flex-1">
                <label for="search" class="block text-sm font-semibold text-slate-700 mb-2">
                    Cari Postingan
                </label>
                <input
                    type="search"
                    id="search"
                    name="search"
                    value="{{ $search ?? request('search', '') }}"
                    placeholder="Masukkan judul postingan..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition"
                >
            </div>

            <div class="md:w-48">
                <label for="per_page" class="block text-sm font-semibold text-slate-700 mb-2">
                    Data per Halaman
                </label>
                <select
                    id="per_page"
                    name="per_page"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    @foreach ([5, 10, 25] as $option)
                        <option value="{{ $option }}"
                            @selected((int) ($perPage ?? 10) === $option)>
                            {{ $option }} data
                        </option>
                    @endforeach
                </select>
            </div>

            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-700 transition">
                Terapkan
            </button>
        </form>
    </div>

    {{-- Daftar Postingan --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

        <div class="flex items-center justify-between gap-3 px-6 py-5 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-slate-900">
                    Daftar Postingan
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Total {{ $posts->total() }} postingan ditemukan
                </p>
            </div>
            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600">
                {{ $posts->count() }} ditampilkan
            </span>
        </div>

        @if($posts->count() > 0)

            <div class="divide-y divide-slate-100">
                @foreach ($posts as $post)
                    <a href="{{ route('post.show', $post->id) }}"
                       class="flex items-center gap-4 px-5 sm:px-6 py-5 hover:bg-indigo-50/50 transition group">

                        {{-- Nomor Urut --}}
                        <div class="w-11 h-11 shrink-0 flex items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 font-bold text-sm group-hover:bg-indigo-100 transition">
                            {{ $posts->firstItem() + $loop->index }}
                        </div>

                        {{-- Informasi Post --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-semibold text-slate-800 group-hover:text-indigo-600 transition truncate">
                                {{ $post->title }}
                            </h3>

                            <p class="mt-1 text-xs text-slate-400">
                                Dipublikasikan pada
                                {{ $post->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        {{-- Aksi --}}
                        <div class="hidden sm:flex items-center gap-2">
                            <span class="text-xs font-semibold text-indigo-600 opacity-0 group-hover:opacity-100 transition">
                                Lihat Detail
                            </span>
                            <span class="text-lg text-slate-300 group-hover:text-indigo-600 transition">
                                &rarr;
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="border-t border-slate-100 bg-slate-50/50 px-5 sm:px-6 py-5">
                <div class="flex flex-col gap-4">

                    <div>
                        {{ $posts->withQueryString()->links() }}
                    </div>
                </div>
            </div>

        @else

            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                <div class="w-16 h-16 flex items-center justify-center rounded-2xl bg-slate-100 text-3xl mb-5">
                    📭
                </div>
                <h3 class="text-lg font-bold text-slate-800">
                    Tidak ada postingan
                </h3>
                <p class="text-sm text-slate-500 mt-2 max-w-sm">
                    Belum ada postingan yang cocok dengan pencarian kamu.
                </p>
                <a href="{{ route('post.index') }}"
                   class="mt-5 inline-flex rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                    Lihat Semua Post
                </a>
            </div>

        @endif
    </div>

    {{-- Footer --}}
    <p class="text-center text-xs text-slate-400 mt-8">
        Blog Management System &bull; Laravel
    </p>

</div>
@endsection
