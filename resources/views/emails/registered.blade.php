<x-mail::message>
# Anmeldebestätigung
Du wurdest durch den J&S Coach ({{ $coach->name }}) für den folgenden Kurs angemeldet:

## Kurs
<x-mail::panel>
### {{ $course->courseType->name }}
@if ($course->course_nr)
{{ $course->course_nr }}<br>
@endif
{{ $course->name }}<br>
{{ $course->formatted_date_range }}{{ $course->duration }}<br>
@if ($course->link)
    <a href="{{ $course->link }}" target="_blank">link</a>
@endif
<x-mail::button url="{{ route('courses.show', $course) }}">
    zum Kurs
</x-mail::button>
</x-mail::panel>
</x-mail::message>