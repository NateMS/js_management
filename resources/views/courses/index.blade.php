<x-app-layout>
    <x-slot name="header">
        <x-header>{{ __('Alle Kurse') }}</x-header>
    </x-slot>
    <div class="pt-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="sm:px-6 lg:px-8 flex justify-end">
                <a href="{{ route('courses.create') }}" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Neuen Kurs erfassen</a>
            </div>
        </div>  
    </div>
    @if($courses->isEmpty())
        <x-no-data>Keine Kurse erfasst.</x-no-data>
    @else
        @foreach ($courses->groupBy('courseType.name') as $courseTypeName => $coursesForType)
            <x-content-view>
                <h2 class="text-xl font-bold text-gray-800 mb-4">{{ $courseTypeName }}</h2>
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr class="text-left">
                            <th class="px-6 py-3">Kursnummer</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Ort</th>
                            <th class="px-6 py-3">Datum</th>
                            <th class="px-6 py-3">Anmeldeschluss</th>
                            <th class="px-6 py-3">Angemeldet</th>
                            <th class="px-6 py-3">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coursesForType as $course)
                            <tr class="odd:bg-white even:bg-gray-50 whitespace-nowrap text-gray-900 border-b clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('courses.show', $course->id) }}'">
                                <td class="px-6 py-4 font-bold">{{ $course->course_nr }}</td>
                                <td class="px-6 py-4">{{ $course->name }}</td>
                                <td class="px-6 py-4">{{ $course->location }}</td>
                                <td class="px-6 py-4">{{ $course->formatted_date_range }} ({{ $course->duration }})</td>
                                <td class="px-6 py-4">{{ $course->registration_deadline->format('d.m.Y') }}</td>
                                <td class="px-6 py-4">{{ $course->participation_number }}</td>
                                <td class="px-6 py-4 font-medium">
                                    <a href="{{ route('courses.edit', $course) }}" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Bearbeiten</a>
                                
                                    <form class="inline" action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Möchten Sie diesen Kurstyp wirklich löschen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Löschen</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-content-view>
        @endforeach
    @endif
</x-app-layout>
