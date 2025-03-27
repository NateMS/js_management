<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden lg:space-x-6 md:space-x-1 md:-my-px lg:ms-10 ms-1 md:flex">
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        Home
                    </x-nav-link>
                    <x-nav-link href="{{ route('courses.my-courses') }}" :active="request()->routeIs('courses.my-courses')">
                        Meine Kurse
                    </x-nav-link>
                    <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.index')">
                        Leiter
                    </x-nav-link>

                    @if(Auth()->user()->isJSVerantwortlich())
                        <div class="hidden sm:flex sm:items-center">
                            <x-dropdown align="right" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center p-1 py-4 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            J&S Admin
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>

                                <x-slot name="content">
                                    <!-- Account Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Kursverwaltung
                                    </div>

                                    <x-dropdown-link href="{{ route('courses.index') }}" class="{{ request()->routeIs('courses.index') ? 'bg-gray-100 font-bold' : ''}}">
                                        Alle Kurse
                                    </x-dropdown-link>

                                    <x-dropdown-link href="{{ route('courses.create') }}" class="{{ request()->routeIs('courses.create') ? 'bg-gray-100 font-bold' : ''}}">
                                        ➕ Kurs erfassen
                                    </x-dropdown-link>

                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Kursteilnehmer
                                    </div>

                                    <x-dropdown-link href="{{ route('courses.all') }}" class="{{ request()->routeIs('courses.all') ? 'bg-gray-100 font-bold' : ''}}">
                                        Alle Teilnehmer
                                    </x-dropdown-link>

                                    <x-dropdown-link href="{{ route('courses.signed_up') }}" class="{{ request()->routeIs('courses.signed_up') ? 'bg-gray-100 font-bold' : ''}}">
                                        🖊️ Eingetragen
                                    </x-dropdown-link>

                                    <x-dropdown-link href="{{ route('courses.waiting_list') }}" class="{{ request()->routeIs('courses.cancelled') ? 'bg-gray-100 font-bold' : ''}}">
                                        ⌛ Warteliste
                                    </x-dropdown-link>

                                    <x-dropdown-link href="{{ route('courses.registered') }}" class="{{ request()->routeIs('courses.registered') ? 'bg-gray-100 font-bold' : ''}}">
                                        ✔️ Angemeldet
                                    </x-dropdown-link>

                                    <x-dropdown-link href="{{ route('courses.attended') }}" class="{{ request()->routeIs('courses.attended') ? 'bg-gray-100 font-bold' : ''}}">
                                        ✅ Teilgenommen
                                    </x-dropdown-link>

                                    <x-dropdown-link href="{{ route('courses.cancelled') }}" class="{{ request()->routeIs('courses.cancelled') ? 'bg-gray-100 font-bold' : ''}}">
                                        ❌ Abgesagt
                                    </x-dropdown-link>

                                    @if(Auth()->user()->isJSCoach())
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Kurstypen
                                        </div>

                                        <x-dropdown-link href="{{ route('course-types.index') }}" class="{{ request()->routeIs('course-types.index') ? 'bg-gray-100 font-bold' : ''}}">
                                            Alle Kurstypen
                                        </x-dropdown-link>

                                        <x-dropdown-link href="{{ route('course-types.create') }}" class="{{ request()->routeIs('course-types.create') ? 'bg-gray-100 font-bold' : ''}}">
                                            ➕ Kurstyp erfassen
                                        </x-dropdown-link>
                                    @endif
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="hidden md:flex md:items-center lg:ms-6 ms-1">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        @if (Auth::user()->canManageTeamMembers(Auth::user()->currentTeam) || Auth::user()->allTeams()->count() > 1)
                            <x-dropdown align="right" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            {{ Auth::user()->currentTeam->name }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="w-60">
                                        @if (Auth::user()->canManageTeamMembers(Auth::user()->currentTeam))
                                            <!-- Team Management -->
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('Manage Team') }}
                                            </div>

                                            <!-- Team Settings -->
                                            <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                                {{ __('Team Settings') }}
                                            </x-dropdown-link>
                                        @endif

                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-dropdown-link href="{{ route('teams.create') }}">
                                                {{ __('Create New Team') }}
                                            </x-dropdown-link>
                                        @endcan

                                        <!-- Team Switcher -->
                                        @if (Auth::user()->allTeams()->count() > 1)
                                            <div class="border-t border-gray-200"></div>

                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                Riege wechseln
                                            </div>

                                            @foreach (Auth::user()->allTeams() as $team)
                                                <x-switchable-team :team="$team" />
                                            @endforeach
                                        @endif
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        @else
                            <span class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white">
                                {{ Auth::user()->currentTeam->name }}
                            </span>
                        @endif
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden">
        <div class="pt-1 space-y-1">
            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                Home
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('courses.my-courses') }}" :active="request()->routeIs('courses.my-courses')">
                Meine Kurse
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.index')">
                Leiter
            </x-responsive-nav-link>
        </div>

        @if(Auth()->user()->isJSVerantwortlich())
            <div class="border-t border-gray-200 pb-1">
                <div class="block px-4 py-2 text-xs text-gray-400">
                    J&S Admin
                </div>
                <x-responsive-nav-link class="pl-6 text-sm" href="{{ route('courses.index') }}" :active="request()->routeIs('courses.index')">
                    Alle Kurse
                </x-responsive-nav-link>
                <x-responsive-nav-link class="pl-6 text-sm" href="{{ route('courses.create') }}" :active="request()->routeIs('courses.create')">
                    ➕ Kurs erfassen
                </x-responsive-nav-link>
                <div class="block px-4 pt-2 pb-1 text-xs text-gray-400">
                    Kursteilnehmer
                </div>
                <x-responsive-nav-link class="pl-6 text-sm" href="{{ route('courses.all') }}" :active="request()->routeIs('courses.all')">
                    Alle Teilnehmer
                </x-responsive-nav-link>
                <x-responsive-nav-link class="pl-6 text-sm" href="{{ route('courses.signed_up') }}" :active="request()->routeIs('courses.signed_up')">
                    🖊️ Eingetragen
                </x-responsive-nav-link>
                <x-responsive-nav-link class="pl-6 text-sm" href="{{ route('courses.waiting_list') }}" :active="request()->routeIs('courses.cancelled')">
                    ⌛ Warteliste
                </x-responsive-nav-link>
                <x-responsive-nav-link class="pl-6 text-sm" href="{{ route('courses.registered') }}" :active="request()->routeIs('courses.registered')">
                    ✔️ Angemeldet
                </x-responsive-nav-link>
                <x-responsive-nav-link class="pl-6 text-sm" href="{{ route('courses.attended') }}" :active="request()->routeIs('courses.attended')">
                    ✅ Teilgenommen
                </x-responsive-nav-link>
                <x-responsive-nav-link class="pl-6 text-sm" href="{{ route('courses.cancelled') }}" :active="request()->routeIs('courses.cancelled')">
                    ❌ Abgesagt
                </x-responsive-nav-link>
                
                @if(Auth()->user()->isJSCoach())
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        Kurstypen
                    </div>
                    <x-responsive-nav-link class="pl-6 text-sm" href="{{ route('course-types.index') }}" :active="request()->routeIs('course-types.index')">
                        Alle Kurstypen
                    </x-responsive-nav-link>
                    <x-responsive-nav-link class="pl-6 text-sm" href="{{ route('course-types.index') }}" :active="request()->routeIs('course-types.index')">
                        ➕ Kurstyp erfassen
                    </x-responsive-nav-link>
                @endif
            </div>
        @endif
        <!-- Responsive Settings Options -->
        <div class="pt-1 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div>
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Team Management -->
                @if (Auth::user()->canManageTeamMembers(Auth::user()->currentTeam))
                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan
                @endif
                <!-- Team Switcher -->
                @if (Auth::user()->allTeams()->count() > 1)
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        Riege wechseln
                    </div>

                    @foreach (Auth::user()->allTeams() as $team)
                        <x-switchable-team :team="$team" component="responsive-nav-link" />
                    @endforeach
                @else
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ Auth::user()->currentTeam->name }}
                    </div>
                @endif

                <div class="border-t border-gray-200"></div>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                
            </div>
        </div>
    </div>
</nav>
