<x-app-layout>
    <x-slot name="header">
        <x-header>{{ __('Meine Kurse') }}</x-header>
    </x-slot>

    <x-content-view>
        <h2 class="text-l font-semibold text-gray-700 mb-1">Eingetragene Kurse</h2>
        @if($signedUpCourses->isEmpty())
            <x-no-data>Du hast dich für keine Kurse eingetragen.</x-no-data>
        @else
            <x-simple-course-table :courses="$signedUpCourses" :user="auth()->user()" />
        @endif
        <h2 class="pt-6 text-l font-semibold text-gray-700 mb-1">Durch J&S Coach angemeldete Kurse</h2>
        @if($registeredCourses->isEmpty())
            <x-no-data>Du bist aktuell für keine Kurse angemeldet.</x-no-data>
        @else
            <x-simple-course-table :courses="$registeredCourses" :user="auth()->user()" />
        @endif
        <h2 class="pt-6 text-l font-semibold text-gray-700 mb-1">Teilgenommene Kurse</h2>
        @if($attendedCourses->isEmpty())
            <x-no-data>Du hast noch an keinen Kursen teilgenommen.</x-no-data>
        @else
            <x-simple-course-table :courses="$attendedCourses" :user="auth()->user()" />
        @endif

        <h2 class="pt-6 text-l font-semibold text-gray-700 mb-1">Abgesagte Kurse</h2>
        @if($cancelledCourses->isEmpty())
            <x-no-data>Du hast keine Kurse abgesagt.</x-no-data>
        @else
            <x-simple-course-table :courses="$cancelledCourses" :user="auth()->user()" />
        @endif
    </x-content-view>
</x-app-layout>
