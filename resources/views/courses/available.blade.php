<x-app-layout>
    <x-slot name="header">
        <x-header>Home</x-header>
    </x-slot>

    <x-content-view>
        @if($validityDate)
            <h2 class="text-center text-xl font-bold text-gray-800 mb-2">Leiterlizenz ist gültig bis</h2>
            <h3 class="text-center text-2xl font-bold text-gray-800 mb-4">{{ $validityDate }}</h3>
        @endif
        @if(!$lastAttended->isEmpty())
            <h2 class="text-l font-semibold text-gray-700 mb-1">Zuletzt besuchter Kurs:</h2>
            <x-simple-course-table :courses="$lastAttended" />
            <div class="mb-4"></div>
        @endif
       
        @if($courses->isEmpty())
            <x-no-data>Aktuell gibt es keine verfügbaren Kurse.</x-no-data>
        @else
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Verfügbare Kurse</h1>
            @foreach ($courses->groupBy('courseType.name') as $courseTypeName => $coursesForType)
                <h2 class="mt-6 text-l font-semibold text-gray-700 mb-1">{{ $courseTypeName }}</h2>
                <x-simple-course-table :courses="$coursesForType" status="true" />                                   
            @endforeach
        @endif
    </x-content-view>
</x-app-layout>