<x-mail::message>
# Neue Kursanmeldung
{{ $user->name }} hat sich für einen Kurs eingetragen:


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

## Teilnehmer
### {{ $user->name }}
{{ $user->formatted_birthdate }}<br>
{{ $user->email }}<br>
@if ($user->js_number)
{{ $user->js_number }}<br>
@endif
<x-mail::button url="{{ route('users.show', $user) }}">
    zum Benutzer
</x-mail::button>
</x-mail::message>