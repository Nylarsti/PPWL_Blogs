@props([
    'label',
    'value',
    'description' => 'Total data yang tersimpan',
    'icon' => 'folder',
    'color' => 'text-blue-600',
    'background' => 'bg-blue-50',
])

<div class="h-full rounded-2xl border border-slate-200 bg-white p-6
            shadow-sm transition-shadow duration-200 hover:shadow-lg">

    {{-- Label dan ikon --}}
    <div class="flex items-center justify-between gap-4">
        <p class="text-sm font-semibold text-slate-600">
            {{ $label }}
        </p>

        <div class="flex h-12 w-12 shrink-0 items-center justify-center
                    rounded-2xl {{ $background }} {{ $color }}">

            <svg
                class="h-6 w-6"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.7"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                @switch($icon)

                    @case('folder')
                        <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7Z" />
                        <path d="M3 11h18" />
                        @break

                    @case('category')
                        <rect x="3" y="3" width="7" height="7" rx="2" />
                        <rect x="14" y="3" width="7" height="7" rx="2" />
                        <rect x="3" y="14" width="7" height="7" rx="2" />
                        <rect x="14" y="14" width="7" height="7" rx="2" />
                        @break

                    @case('code')
                        <path d="m8 7-5 5 5 5" />
                        <path d="m16 7 5 5-5 5" />
                        <path d="m14 4-4 16" />
                        @break

                    @case('star')
                        <path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3l-5.6 2.9 1.1-6.2L3 9.6l6.2-.9L12 3Z" />
                        @break

                @endswitch
            </svg>
        </div>
    </div>

    {{-- Jumlah data --}}
    <p class="mt-6 text-4xl font-bold tracking-tight {{ $color }}">
        {{ $value }}
    </p>

    {{-- Keterangan --}}
    <p class="mt-2 text-sm leading-relaxed text-slate-500">
        {{ $description }}
    </p>

</div>