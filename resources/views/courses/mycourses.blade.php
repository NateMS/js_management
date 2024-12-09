<x-app-layout>
    <x-slot name="header">
        <x-header>{{ __('Meine Kurse') }}</x-header>
    </x-slot>

    <x-content-view>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Eingetragene Kurse</h2>
        @if($signedUpCourses->isEmpty())
            <x-no-data>Du hast dich für keine Kurse eingetragen.</x-no-data>
        @else
            <x-simple-course-table :courses="$signedUpCourses" />
        @endif

        <h2 class="mt-8 text-xl font-bold text-gray-800 mb-4">Durch J&S Coach angemeldete Kurse</h2>
        @if($registeredCourses->isEmpty())
            <x-no-data>Du bist aktuell für keine Kurse angemeldet.</x-no-data>
        @else
            <x-simple-course-table :courses="$registeredCourses" />
        @endif

        <h2 class="mt-8 text-xl font-bold text-gray-800 mb-4">Teilgenommene Kurse</h2>
        @if($attendedCourses->isEmpty())
            <x-no-data>Du hast noch an keinen Kursen teilgenommen.</x-no-data>
        @else
            <x-simple-course-table :courses="$attendedCourses" />
        @endif

        <h2 class="mt-8 text-xl font-bold text-gray-800 mb-4">Abgesagte Kurse</h2>
        @if($cancelledCourses->isEmpty())
            <x-no-data>Du hast keine Kurse abgesagt.</x-no-data>
        @else
            <x-simple-course-table :courses="$cancelledCourses" />
        @endif
    </x-content-view>
</x-app-layout>
