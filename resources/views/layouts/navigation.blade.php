<nav x-data="{ open: false }" class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-16 items-center justify-between gap-6">
            <a href="{{ route('post.dashboard') }}" class="shrink-0 text-xl font-bold tracking-tight text-slate-800">
                MyProject
            </a>

            <div class="hidden flex-1 items-center justify-center gap-1 md:flex">
                <a href="{{ route('post.dashboard') }}"
                    @class([
                        'rounded-lg px-3 py-2 text-sm font-medium transition',
                        'bg-indigo-50 text-indigo-700' => request()->routeIs('post.dashboard', 'dashboard'),
                        'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => !request()->routeIs('post.dashboard', 'dashboard'),
                    ])>Home</a>
                <a href="#about" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">About</a>
                <a href="#education" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">Education</a>
                <a href="{{ route('projects.index') }}"
                    @class([
                        'rounded-lg px-3 py-2 text-sm font-medium transition',
                        'bg-indigo-50 text-indigo-700' => request()->routeIs('projects.*'),
                        'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => !request()->routeIs('projects.*'),
                    ])>Projects</a>
                <a href="{{ route('post.index') }}"
                    @class([
                        'rounded-lg px-3 py-2 text-sm font-medium transition',
                        'bg-indigo-50 text-indigo-700' => request()->routeIs('post.index', 'post.show', 'post.create', 'post.edit'),
                        'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => !request()->routeIs('post.index', 'post.show', 'post.create', 'post.edit'),
                    ])>Posts</a>
                <a href="{{ route('profile.edit') }}"
                    @class([
                        'rounded-lg px-3 py-2 text-sm font-medium transition',
                        'bg-indigo-50 text-indigo-700' => request()->routeIs('profile.*', 'post.profile-saya'),
                        'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => !request()->routeIs('profile.*', 'post.profile-saya'),
                    ])>Profile</a>
            </div>

            <div class="hidden shrink-0 items-center gap-4 md:flex">
                @auth
                    <span class="max-w-32 truncate text-sm font-medium text-slate-600">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-slate-500 transition hover:text-red-600">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-700">Login</a>
                @endauth
            </div>

            <button type="button" @click="open = !open"
                class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 md:hidden"
                :aria-expanded="open.toString()" aria-label="Toggle navigation">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak class="border-t border-slate-200 bg-white md:hidden">
        <div class="space-y-1 px-4 py-3">
            <a href="{{ route('post.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Home</a>
            <a href="#about" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">About</a>
            <a href="#education" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Education</a>
            <a href="{{ route('projects.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Projects</a>
            <a href="{{ route('post.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Posts</a>
            @auth
                <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Profile</a>
            @endauth
        </div>
        @auth
            <div class="flex items-center justify-between border-t border-slate-200 px-7 py-3">
                <span class="truncate text-sm font-medium text-slate-600">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-slate-500 hover:text-red-600">Logout</button>
                </form>
            </div>
        @else
            <div class="border-t border-slate-200 px-7 py-3">
                <a href="{{ route('login') }}" class="text-sm font-medium text-indigo-700">Login</a>
            </div>
        @endauth
    </div>
</nav>
