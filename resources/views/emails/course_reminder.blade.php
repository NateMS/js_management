<x-mail::message>
# Erinnerung an J&S Kurs
In einer Woche findet ein Kurs statt, an dem angemeldet bist.

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

### Hinweis zur Rechnung
Solltest du eine Rechnung für Kursgebühren erhalten, so leite diese bitte weiter an den Kassier (kassier@tsvrohrdorf.ch). Solltest du eine Mahnung erhalten haben, bezahle die Rechnung bitte selbst und fordere den Rechnungsbetrag via Spesenformular ein.
</x-mail::message>