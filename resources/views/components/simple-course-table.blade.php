@props(['courses'])

<table class="w-full text-sm text-left text-gray-500">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
        <tr class="text-left">
            <th class="px-6 py-3">Kursnummer</th>
            <th class="px-6 py-3">Name</th>
            <th class="px-6 py-3">Ort</th>
            <th class="px-6 py-3">Datum</th>
            <th class="px-6 py-3">Anmeldeschluss</th>
            <th class="px-6 py-3">Angemeldet</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($courses as $course)
            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 whitespace-nowrap text-gray-900 border-b clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('courses.show', $course->id) }}'">
                <td class="px-6 py-4 font-bold">{{ $course->course_nr }}</td>
                <td class="px-6 py-4">{{ $course->name }}</td>
                <td class="px-6 py-4">{{ $course->location }}</td>
                <td class="px-6 py-4">{{ $course->formatted_date_range }} ({{ $course->duration }})</td>
                <td class="px-6 py-4">{{ $course->registration_deadline->format('d.m.Y') }}</td>
                <td class="px-6 py-4">{{ $course->participation_number }}</td>
            </tr>
        @endforeach
    </tbody>
</table>