<x-app-layout>
    <x-slot name="header">
        <x-header>{{ __('Verfügbare Kurse') }}</x-header>
    </x-slot>

    <x-content-view>
        @if(!$lastAttended->isEmpty())
            <h2 class="text-xl font-bold text-gray-800 mb-4">Zuletzt besuchter Kurs:</h2>
            <x-simple-course-table :courses="$lastAttended" />
            <div class="mb-4"></div>
        @endif
       
        @if($courses->isEmpty())
            <x-no-data>Aktuell sind keine verfügbaren Kurse erfasst.</x-no-data>
        @else
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Verfügbare Kurse</h1>
            @foreach ($courses->groupBy('courseType.name') as $courseTypeName => $coursesForType)
                <h2 class="text-xl font-bold text-gray-800 mb-4">{{ $courseTypeName }}</h2>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-4">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr class="text-left">
                            <th class="px-6 py-3">Kursnummer</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Ort</th>
                            <th class="px-6 py-3">Datum</th>
                            <th class="px-6 py-3">Anmeldeschluss</th>
                            <th class="px-6 py-3">Angemeldet</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coursesForType as $course)
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 whitespace-nowrap text-gray-900 border-b clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('courses.show', $course->id) }}'">
                                <td class="px-6 py-4 font-bold">{{ $course->course_nr }}</td>
                                <td class="px-6 py-4">{{ $course->name }}</td>
                                <td class="px-6 py-4">{{ $course->location }}</td>
                                <td class="px-6 py-4">{{ $course->formatted_date_range }} ({{ $course->duration }})</td>
                                <td class="px-6 py-4">{{ $course->registration_deadline->format('d.m.Y') }}</td>
                                <td class="px-6 py-4">{{ $course->participation_number }}</td>
                                <td class="px-6 py-4 font-medium">
                                    @if($course->userStatus)
                                        {{ $course->userStatus->formatted_status  }}
                                    @else
                                        <form action="{{ route('courses.signup', $course) }}" method="POST" class="ml-auto">
                                            @csrf
                                            <x-button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                                Eintragen
                                            </x-button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @endif
    </x-content-view>
</x-app-layout>