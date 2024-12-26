<x-mail::message>
# Erinnerung an J&S Kurs
In einer Woche findet ein Kurs statt, wo du angemeldet bist.

### {{ $course->courseType->name }}
@if ($course->course_nr)
{{ $course->course_nr }}<br>
@endif
{{ $course->name }}<br>
{{ $course->location }}<br>
{{ $course->formatted_date_range }}{{ $course->duration }}<br><br>

@if($users->isNotEmpty())
Folgende Personen sind ebenfalls angemeldet für diesen Kurs:<br>
@foreach ($users as $user)
- {{ $user->name }}<br>
@endforeach
@endif
</x-mail::message>