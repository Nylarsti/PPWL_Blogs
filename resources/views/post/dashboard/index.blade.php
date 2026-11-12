@extends('layouts.app')

@section('content')
<div class="rounded-3xl bg-slate-50 p-4 sm:p-6 lg:p-8">

    {{-- Judul halaman --}}
    <div class="mb-8">
        <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-slate-400">
            Portofolio
        </p>

        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
            Dashboard
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Lihat ringkasan data portofolio yang kamu kelola.
        </p>
    </div>

    {{-- Kartu statistik --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card
            label="Projects"
            :value="$totalProjects"
            description="Project dalam portofolio"
            icon="folder"
            color="text-blue-600"
            background="bg-blue-50"
        />

        <x-stat-card
            label="Categories"
            :value="$totalCategories"
            description="Kategori yang tersedia"
            icon="category"
            color="text-green-600"
            background="bg-green-50"
        />

        <x-stat-card
            label="Technologies"
            :value="$totalTechnologies"
            description="Teknologi yang digunakan"
            icon="code"
            color="text-purple-600"
            background="bg-purple-50"
        />

        <x-stat-card
            label="Skills"
            :value="$totalSkills"
            description="Skill yang tercatat"
            icon="star"
            color="text-orange-600"
            background="bg-orange-50"
        />

        <x-stat-card label="Published" :value="$publishedProjects"
            color="text-green-600" background="bg-green-50" />

        <x-stat-card label="Draft" :value="$draftProjects"
            color="text-orange-600" background="bg-orange-50" />

    </div>

    <section class="mt-8 rounded-2xl border border-slate-200 bg-white p-6">
        <h2 class="mb-3 text-lg font-semibold text-slate-900">
            Project per Kategori
        </h2>

        <ul class="divide-y divide-slate-100">
            @forelse ($categories as $category)
                <li class="flex items-center justify-between gap-4 py-3">
                    <span>{{ $category->name ?: 'Kategori tanpa nama' }}</span>
                    <span class="font-semibold text-indigo-600">
                        {{ $category->projects_count }} project
                    </span>
                </li>
            @empty
                <li class="py-3 text-slate-500">Belum ada kategori.</li>
            @endforelse
        </ul>
    </section>

    <section class="mt-8 rounded-2xl border border-slate-200 bg-white p-6">
        <h2 class="mb-3 text-lg font-semibold text-slate-900">Skill Teratas</h2>

        <ul class="divide-y divide-slate-100">
            @forelse ($topSkills as $skill)
                <li class="flex items-center justify-between gap-4 py-3">
                    <span>{{ $skill->name }}</span>
                    <span class="font-semibold text-indigo-600">
                        {{ $skill->proficiency }}/100
                    </span>
                </li>
            @empty
                <li class="py-3 text-slate-500">Belum ada skill.</li>
            @endforelse
        </ul>
    </section>

</div>
@endsection