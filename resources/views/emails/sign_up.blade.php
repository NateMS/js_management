<x-mail::message>
# Neue Kursanmeldung
{{ $user->name }} hat sich für einen Kurs eingetragen:

<x-mail::panel>
<b>{{ $course->courseType->name }}</b><br><br>
{{ $course->course_nr }}<br>
{{ $course->name }}<br>
{{ $course->formatted_date_range }}{{ $course->duration }}<br>
<a href="{{ $course->link }}" target="_blank">link</a>
<x-mail::button url="{{ route('courses.show', $course) }}">
    zum Kurs
</x-mail::button>
</x-mail::panel>
<x-mail::panel>
<b>{{ $user->name }}</b><br><br>
{{ $user->formatted_birthdate }}<br>
{{ $user->email }}<br>
{{ $user->js_number }}<br>

<x-mail::button url="{{ route('users.show', $user) }}">
    zum Benutzer
</x-mail::button>
</x-mail::panel>
</x-mail::message>