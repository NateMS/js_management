<x-app-layout>
    <x-slot name="header">
        <x-header>{{ __('Meine Kurse') }}</x-header>
    </x-slot>

    <x-content-view>
        @if($signedUpCourses->isNotEmpty())
            <h2 class="text-l font-semibold text-gray-700 mb-1">Eingetragene Kurse</h2>
            <x-simple-course-table :courses="$signedUpCourses" :user="auth()->user()" />
        @endif
        
        @if($registeredCourses->isNotEmpty())
            <h2 class="pt-6 text-l font-semibold text-gray-700 mb-1">Durch J&S Coach angemeldete Kurse</h2>
            <x-simple-course-table :courses="$registeredCourses" :user="auth()->user()" />
        @endif
        
        @if($attendedCourses->isNotEmpty())
            <h2 class="pt-6 text-l font-semibold text-gray-700 mb-1">Teilgenommene Kurse</h2>
            <x-simple-course-table :courses="$attendedCourses" :user="auth()->user()" />
        @endif

        @if($cancelledCourses->isNotEmpty())
            <h2 class="pt-6 text-l font-semibold text-gray-700 mb-1">Abgesagte Kurse</h2>
            <x-simple-course-table :courses="$cancelledCourses" :user="auth()->user()" />
        @endif

        @if($signedUpCourses->isEmpty() && $registeredCourses->isEmpty() && $attendedCourses->isEmpty() && $cancelledCourses->isEmpty())
            <x-no-data>Hier werden deine Kurse angezeigt. Aktuell bist du bei keinen Kursen eingetragen.</x-no-data>
        @endif
    </x-content-view>
</x-app-layout>
