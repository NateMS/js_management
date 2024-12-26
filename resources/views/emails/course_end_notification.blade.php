<x-mail::message>
# Kursteilnahme bestätigen
Heute endet ein Kurs, für welchen du eingetragen warst.
Bitte bestätige, ob du an diesem Kurs teilgenommen hast:

### {{ $course->courseType->name }}
@if ($course->course_nr)
{{ $course->course_nr }}<br>
@endif
{{ $course->name }}<br>
{{ $course->formatted_date_range }}{{ $course->duration }}<br><br>

Hast du an diesem Kurs teilgenommen?
<table>
<tr>
<td style="padding-right: 10px;">
<x-mail::button url="{{ $confirmUrl }}" color="green">
    Ja
</x-mail::button>
</td>
<td>
<x-mail::button url="{{ $cancelUrl }}" color="red">
    Nein
</x-mail::button>
</td>
</tr>
</table>

</x-mail::message>