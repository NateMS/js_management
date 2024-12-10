@props(['courses' => null, 'status' => false, 'course' => null])

@if (isset($courses) || isset($course))
    <table class="w-full text-sm text-left text-gray-500">
        @if (isset($courses))
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr class="text-left">
                    <th class="px-6 py-3">Kursnummer</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Ort</th>
                    <th class="px-6 py-3">Datum</th>
                    <!-- <th class="px-6 py-3">Anmeldeschluss</th> -->
                    <th class="px-6 py-3">Angemeldet</th>
                    @if ($status)
                        <th class="px-6 py-3">Status</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 whitespace-nowrap text-gray-900 border-b clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('courses.show', $course->id) }}'">
                        <td class="px-6 py-4 font-bold">{{ $course->course_nr ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $course->name }}</td>
                        <td class="px-6 py-4">{{ $course->location }}</td>
                        <td class="px-6 py-4">{{ $course->formatted_date_range }} ({{ $course->duration }})</td>
                        <!-- <td class="px-6 py-4">{{ $course->registration_deadline->format('d.m.Y') }}</td> -->
                        <td class="px-6 py-4">{{ $course->participation_number }}</td>
                        @if ($status)
                            <td class="px-6 py-4">
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
                        @endif
                    </tr>
                @endforeach
            </tbody>
        @elseif (isset($course))
            <tbody>
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 whitespace-nowrap text-gray-900 border-b clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('courses.show', $course->id) }}'">
                    <td class="px-6 py-4 font-bold">{{ $course->courseType->name }}</td>
                    <td class="px-6 py-4 font-bold">{{ $course->course_nr }}</td>
                    <td class="px-6 py-4">{{ $course->name }}</td>
                    <td class="px-6 py-4">{{ $course->location }}</td>
                    <td class="px-6 py-4">{{ $course->formatted_date_range }} ({{ $course->duration }})</td>
                </tr>
            </tbody>
        @endif
        
    </table>
@endif