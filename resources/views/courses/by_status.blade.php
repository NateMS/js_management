<x-app-layout>
    <x-slot name="header">
        <x-header>{{ $title }}</x-header>
    </x-slot>

    <x-content-view>
        @if(!$courses->isEmpty() && isset($years) && count($years) > 1)
            <form method="GET" action="{{ route(Route::currentRouteName()) }}">
                <label class="text-xl text-gray-700" for="year">Jahr:</label>
                <select name="year" id="year" onchange="this.form.submit()" class="text-xl cursor-pointer hover:border-transparent active:border-transparent focus:border-transparent text-gray-700 border-transparent bg-gray-100 rounded-md hover:bg-gray-200 transition ease-in-out duration-150">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </form>
        @endif
        @forelse($courses as $course)
            @if($course->users->isNotEmpty())
                <div class="mb-4">
                    <x-simple-course-table :course="$course" />
                    <div class="overflow-x-auto">                  
                        <table class="min-w-full text-xs md:text-sm lg:text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr class="text-left">
                                <th class="px-2 py-2 md:px-3 lg:px-4 md:py-3">Name</th>
                                <th class="px-2 py-2 md:px-3 lg:px-4 md:py-3 {{ $course->users->first()?->pivot->status == 'signed_up' || $course->users->first()?->pivot->status == 'registered' ? 'hidden md:table-cell' : '' }}">Geburtsdatum</th>
                                <th class="px-2 py-2 md:px-3 lg:px-4 md:py-3 {{ $course->users->first()?->pivot->status == 'signed_up' || $course->users->first()?->pivot->status == 'registered' ? 'hidden md:table-cell' : '' }}">E-Mail</th>
                                <th class="px-2 py-2 md:px-3 lg:px-4 md:py-3 hidden sm:table-cell">J&S Nummer</th>
                                <th class="px-2 py-2 md:px-3 lg:px-4 md:py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course->users as $user)
                                <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 whitespace-nowrap text-gray-900 border-b" style="cursor: pointer;" onclick="window.location='{{ route('users.show', $user->id) }}'">
                                    <td class="px-2 py-1 md:px-3 lg:px-4 md:py-2">{{ $user->name }}</td>
                                    <td class="px-2 py-1 md:px-3 lg:px-4 md:py-2 {{ $user->pivot->status == 'signed_up' || $user->pivot->status == 'registered' ? 'hidden md:table-cell' : '' }}"">{{ $user->formattedBirthdate }}</td>
                                    <td class="px-2 py-1 md:px-3 lg:px-4 md:py-2 {{ $user->pivot->status == 'signed_up' || $user->pivot->status == 'registered' ? 'hidden md:table-cell' : '' }}">{{ $user->email }}</td>
                                    <td class="px-2 py-1 md:px-3 lg:px-4 md:py-2 hidden sm:table-cell">{{ $user->js_number }}</td>
                                    <td class="px-2 py-1 md:px-3 lg:px-4 md:py-2">
                                        <span class="flex items-left">
                                            <x-status :status="$user->pivot->status" />

                                            @if ($user->pivot->status == 'signed_up')
                                                @if (!$course->isInPast())
                                                    @if (auth()->user()->isJSCoach())
                                                        <form action="{{ route('courses.register', [$course, $user]) }}" method="POST" class="ml-2">
                                                            @csrf
                                                            <button type="submit" class="px-2 py-1 md:px-4 md:py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                                                Anmelden
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <span class="ml-2 flex items-center">teilgenommen:</span>
                                                    <form action="{{ route('courses.attend', [$course, $user]) }}" method="POST" class="ml-2">
                                                        @csrf
                                                        <button type="submit" class="px-2 py-1 md:px-4 md:py-2 bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                                            ja
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('courses.cancel', [$course, $user]) }}" method="POST" class="ml-2">
                                                        @csrf
                                                        <button type="submit" class="px-2 py-1 md:px-4 md:py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                                            nein
                                                        </button>
                                                    </form>
                                                @endif
                                            @elseif ($user->pivot->status == 'registered')
                                                @if ($course->isInPast())
                                                    <span class="ml-2 flex items-center">teilgenommen:</span>
                                                    <form action="{{ route('courses.attend', [$course, $user]) }}" method="POST" class="ml-2">
                                                        @csrf
                                                        <button type="submit" class="px-2 py-1 md:px-4 md:py-2 bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                                            ja
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('courses.cancel', [$course, $user]) }}" method="POST" class="ml-2">
                                                        @csrf
                                                        <button type="submit" class="px-2 py-1 md:px-4 md:py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                                            nein
                                                        </button>
                                                    </form>
                                                @elseif (auth()->user()->isJSCoach())
                                                    <form action="{{ route('courses.change-status', [$course]) }}" method="POST" class="ml-2">
                                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                        <input type="hidden" name="status" value="cancelled">
                                                        @csrf
                                                        <button type="submit" class="px-2 py-1 md:px-4 md:py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                                            Absagen
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            <tfoot>
                            <tr class="bg-gray-100 font-semibold whitespace-nowrap text-gray-700">
                                <td class="px-2 py-1 md:px-3 lg:px-4 md:py-2">{{ $course->users->count() }} Teilnehmer
                        </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @empty
            <p>Keine Kurse gefunden.</p>
        @endforelse
    </x-content-view>
</x-app-layout>