<x-mail::message>
# Anmeldebestätigung (Warteliste)
Da der Kurs aktuell ausgebucht ist, wurdest du durch den J&S Coach ({{ $coach->name }}) für den folgenden Kurs auf die Warteliste gesetzt:

### {{ $course->courseType->name }}
@if ($course->course_nr)
{{ $course->course_nr }}<br>
@endif
{{ $course->name }}<br>
{{ $course->location }}<br>
{{ $course->formatted_date_range }}{{ $course->duration }}<br>
@if ($course->link)
    <a href="{{ $course->link }}" target="_blank">link</a>
@endif
<x-mail::button url="{{ route('courses.show', $course) }}">
    zum Kurs
</x-mail::button>
</x-mail::message>