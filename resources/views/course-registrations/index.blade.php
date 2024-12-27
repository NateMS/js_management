<x-app-layout>
<div class="container">
    <h1>Verfügbare Kurse</h1>
    @forelse ($courses->sortBy('courseType.order')->groupBy('courseType.name') as $courseTypeName => $coursesForType)
        <div class="mb-4">
            <h2>{{ $courseTypeName }}</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Kursnummer</th>
                        <th>Name</th>
                        <th>Ort</th>
                        <th>Startdatum</th>
                        <th>Enddatum</th>
                        <th>Registrierungsfrist</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coursesForType as $course)
                        <tr>
                            <td>{{ $course->course_nr }}</td>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->location }}</td>
                            <td>{{ $course->date_start->format('d.m.Y') }}</td>
                            <td>{{ $course->date_end->format('d.m.Y') }}</td>
                            <td>{{ $course->registration_deadline->format('d.m.Y') }}</td>
                            <td>
                                <form action="{{ route('courses.register', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Registrieren</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p>Keine verfügbaren Kurse.</p>
    @endforelse
</div>
</x-app-layout>
