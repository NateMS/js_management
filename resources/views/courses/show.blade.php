<x-app-layout>
    <x-slot name="header">
        <x-header>{{ $course->courseType->name }} {{ $course->course_nr ? '(' . $course->course_nr . ')' : "Kurs" }}</x-header>
    </x-slot>
    <div class="max-w-7xl mx-auto md:grid md:grid-cols-7 md:gap-4">
        <div class="md:col-span-3">
            <x-content-view>  
                <h3 class="font-semibold text-xl">📋 Kursdetails</h3>
                <table class="mt-4 w-full text-sm text-left text-gray-500">
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">Kurstyp</th>
                        <td class="px-2 py-2">{{ $course->courseType->name }}</td>
                    </tr>
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">Kursnummer</th>
                        <td class="px-2 py-2">{{ $course->course_nr }}</td>
                    </tr>
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">Name</th>
                        <td class="px-2 py-2">{{ $course->name }}</td>
                    </tr>
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">Ort</th>
                        <td class="px-2 py-2">{{ $course->location }}</td>
                    </tr>
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">Datum</th>
                        <td class="px-2 py-2">{{ $course->formatted_date_range }} ({{ $course->duration }})</td>
                    </tr>
                    @if($course->prerequisites)
                        <tr class="text-gray-900">
                            <th class="text-gray-700 uppercase pr-2 py-3">Voraussetzungen</th>
                            <td class="px-2 py-2">{{ $course->prerequisites }}</td>
                        </tr>
                    @endif
                    <tr class=" text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">Anmeldefrist</th>
                        <td class="px-2 py-2">{{ $course->registration_deadline->format('d.m.Y') }}</td>
                    </tr>
                    @if($course->notes)
                        <tr class="text-gray-900">
                            <th class="text-gray-700 uppercase pr-2 py-3">Notizen</th>
                            <td class="px-2 py-2">{{ $course->notes }}</td>
                        </tr>
                    @endif
                    @if($course->link)
                        <tr class="text-gray-900">
                            <th class="text-gray-700 uppercase pr-2 py-3">Link</th>
                            <td class="px-2 py-2"><a class="text-blue-500 hover:text-blue-700 underline font-medium" href="{{ $course->link }}" target="_blank">Link</a></td>
                        </tr>
                    @endif
                </table>

                @if (auth()->user()->canEditCourse($course))
                    <a href="{{ route('courses.edit', $course) }}" class="px-4 py-2 inline-block bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Bearbeiten</a>
                @endif
        
                @if (auth()->user()->isJSCoach())
                    <form class="inline" action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Möchten Sie diesen Kurstyp wirklich löschen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Löschen</button>
                    </form>
                @endif
            </x-content-view>
        </div>
        <div class="md:col-span-4">
            <x-content-view>
                <div class="grid grid-cols-2">
                    <h3 class="font-semibold text-xl">👥 Kursteilnehmer</h3>
                    @if ($course->registration_deadline >= now())
                        <span class="flex justify-end">
                            @if (!$userStatus)
                                <form action="{{ route('courses.signup', $course) }}" method="POST">
                                    @csrf
                                    <x-button class="btn btn-primary">Mich Eintragen</x-button>
                                </form>
                            @endif
                        </span>
                    @endif
                </div>
                @if($users->isEmpty())
                    <x-no-data>Es sind noch keine Teilnehmer für diesen Kurs eingetragen.</x-no-data>
                @else
                    <table class="mt-4 w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr class="text-left">
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Datum</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr class="odd:bg-white even:bg-gray-50 text-gray-900 border-b">
                                    <td class="px-6 py-3">{{ $user->name }}</td>
                                    <td class="px-6 py-3">{{ $user->pivot->formatted_timestamp }}</td>
                                    @if ((auth()->user()->isJSVerantwortlich() && $currentTeamUsers->contains($user) && !$user->isJSCoach()) || auth()->user()->isJSCoach())
                                        <td class="px-6 py-3">
                                            <span class="flex">
                                                <form action="{{ route('courses.change-status', [$course]) }}" method="POST">
                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                    @csrf
                                                
                                                        <select id="status" onchange="this.form.submit()" name="status" class="border-transparent bg-gray-200 rounded-md">
                                                            @if ($user->pivot->status == 'signed_up')
                                                                <option value="signed_up" {{ $user->pivot->status == 'signed_up' ? 'selected' : ''}}>⌛ Eingetragen</option>
                                                            @endif
                                                            @if ((auth()->user()->isJSCoach() && $user->pivot->status == 'signed_up') || $user->pivot->status == 'registered')
                                                                <option value="registered" {{ $user->pivot->status == 'registered' ? 'selected' : ''}}>✔️ Angemeldet</option>
                                                            @endif
                                                            @if ($course->isInPast() || $user->pivot->status == 'attended')
                                                                <option value="attended" {{ $user->pivot->status == 'attended' ? 'selected' : ''}}>✅ Teilgenommen</option>
                                                            @endif
                                                            @if ($user->pivot->status == 'cancelled' || ($course->isInPast() && ($user->pivot->status == 'registered' || $user->pivot->status == 'attended')) || $user->pivot->status == 'registered' && auth()->user()->isJSCoach())
                                                                <option value="cancelled" {{ $user->pivot->status == 'cancelled' ? 'selected' : ''}}>❌ Abgesagt</option>
                                                            @endif
                                                        </select>
                                                </form>
                                                @if (auth()->user()->isJSCoach() && ($user->pivot->status == 'signed_up' || $user->pivot->status == 'cancelled'))
                                                    <form action="{{ route('courses.delete-status', $course) }}" method="POST">
                                                        @csrf
                                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                            <x-button type="submit" class="h-full ml-2 px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">X</x-button>
                                                    </form>
                                                @endif
                                            </span>
                                        </td>
                                    @elseif ($user->id == auth()->user()->id && $course->isInPast() && $course->userStatus->status == 'registered')
                                        <td class="px-6 py-3">
                                            
                                            <span class="flex">
                                                <span>Teilgenommen:</span>
                                                <form action="{{ route('courses.attend', [$course, auth()->user()]) }}" method="POST" class="ml-2">
                                                    @csrf
                                                    <x-button type="submit" class="px-4 py-2 bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                                        Ja
                                                    </x-button>
                                                </form>
                                                <form action="{{ route('courses.cancel', [$course, auth()->user()]) }}" method="POST" class="ml-2">
                                                    @csrf
                                                    <x-button type="submit" class="px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                                        nein
                                                    </x-button>
                                                </form>
                                            </span>
                                        </td>    
                                    @else

                                        <td class="px-6 py-3">{{ $user->pivot->formatted_status }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                @if (auth()->user()->isJSVerantwortlich() && !$availableUsers->isEmpty())
                    <form action="{{ route('courses.change-status', [$course]) }}" method="POST" class="ml-auto">
                        @csrf
                        <span class="flex mt-2">
                            <select id="user_id" name="user_id" class="border-transparent bg-gray-200 rounded-md">
                                <option value="" disabled selected>Leiter auswählen</option>
                                @foreach ($availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <select id="status" name="status" class="ml-2 border-transparent bg-gray-200 rounded-md">
                                @if (!$course->isInPast())
                                    <option value="signed_up">Eingetragen</option>
                                @endif
                                @if (auth()->user()->isJSCoach() && !$course->isInPast())
                                    <option value="registered">Angemeldet</option>
                                @endif
                                @if ($course->isInPast())
                                    <option value="attended">Teilgenommen</option>
                                @endif
                            </select>
                            <x-button type="submit" class="ml-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                ✓
                            </x-button>
                        </span>
                    </form>
                @endif
                @if ($userStatus == 'signed_up')
                    <span class="mt-3 block text-gray-600">Du hast dich für diesen Kurs eingetragen. Sobald die Anmeldung durch den J&S-Coach erfolgt ist, ändert sich der Status auf 'Angemeldet'.</span>
                @endif
                @if ($userStatus == 'registered')
                    <span class="mt-3 block text-gray-600">Du wurdest durch den J&S-Coach für diesen Kurs angemeldet. Du kannst dich nicht mehr selbstständig austragen. Wenn du dich vom Kurs abgemeldet hast, melde dies dem J&S-Coach oder dem J&S Verantwortlichen deiner Riege.</span>
                @endif
                @if ($userStatus == 'attended')
                    <span class="mt-3 block text-gray-600">Du hast an diesem Kurs teilgenommen!</span>
                @endif
            </x-content-view> 
        </div>
    </div>
</x-app-layout>