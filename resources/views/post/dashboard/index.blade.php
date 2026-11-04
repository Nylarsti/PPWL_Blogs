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
    </div>

</div>
@endsection