<x-app-layout>
    <x-slot name="header">
        <x-header>Home</x-header>
    </x-slot>

        @if($validityDate)
            <x-content-view>
                <h2 class="text-center text-xl font-bold text-gray-800 mb-2">Leiterlizenz ist gültig bis</h2>
                <h3 class="text-center text-2xl font-bold text-gray-800 mb-4">{{ $validityDate }}</h3>
            </x-content-view>
        @endif

        @if(!$pastCourses->isEmpty())
            <x-content-view>
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Vergangene Kurse</h1>
                <p>Bitte bestätige, ob du an diesem Kurs teilgenommen hast.</p>
                @foreach ($pastCourses->groupBy('courseType.name') as $courseTypeName => $coursesForType)
                    <h2 class="mt-6 text-l font-semibold text-gray-700 mb-1">{{ $courseTypeName }}</h2>
                    <x-simple-course-table :courses="$coursesForType" status="true" />                                   
                @endforeach
            </x-content-view>
        @endif

        @if($lastAttended)
            <x-content-view>
                <h2 class="text-l font-semibold text-gray-700 mb-1">Zuletzt besuchter Kurs:</h2>
                <x-simple-course-table :course="$lastAttended" />
                <div class="mb-4"></div>
            </x-content-view>
        @endif

        @if(!$plannedCourses->isEmpty())
            <x-content-view>
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Geplante Kurse</h1>
                @foreach ($plannedCourses->groupBy('courseType.name') as $courseTypeName => $coursesForType)
                    <h2 class="mt-6 text-l font-semibold text-gray-700 mb-1">{{ $courseTypeName }}</h2>
                    <x-simple-course-table :courses="$coursesForType" status="true" />                                   
                @endforeach
            </x-content-view>
        @endif
       
        @if($courses->isEmpty())
            <x-content-view>
                <x-no-data>Verfügbare Kurse werden hier aufgelistet.</x-no-data>
            </x-content-view>
        @else
            <x-content-view>
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Verfügbare Kurse</h1>
                @foreach ($courses->groupBy('courseType.name') as $courseTypeName => $coursesForType)
                    <h2 class="mt-6 text-l font-semibold text-gray-700 mb-1">{{ $courseTypeName }}</h2>
                    <x-simple-course-table :courses="$coursesForType" status="true" />                                   
                @endforeach
            </x-content-view>
        @endif
</x-app-layout>