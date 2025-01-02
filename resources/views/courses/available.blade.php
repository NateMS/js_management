<x-app-layout>
    <x-slot name="header">
        <x-header>Home</x-header>
    </x-slot>

        @if($validityDate)
            <x-content-view>
                <h2 class="text-center text-xl font-bold text-gray-800 mb-2">Leiterlizenz ist gültig bis</h2>
                <h3 class="text-center text-xl font-bold {{ auth()->user()->getRevalidationColorClass() }} mb-4">{{ $validityDate }}</h3>
            </x-content-view>
        @endif

        @if(!$pastCourses->isEmpty())
            <x-content-view>
                <h1 class="text-xl font-bold text-gray-800">Vergangene Kurse</h1>
                <p class="pb-2 text-sm">Bitte bestätige, ob du an diesem Kurs teilgenommen hast.</p>
                @foreach ($pastCourses as $course)
                    <x-simple-course-table :course="$course" :user="auth()->user()" status="true" />
                    <span class="flex justify-end pt-2 pb-4">
                        <span class="pr-2">Teilgenommen:</span>
                        <form action="{{ route('courses.attend', [$course, auth()->user()]) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-2 py-1 md:px-4 md:py-2 bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                Ja
                            </button>
                        </form>
                        <form action="{{ route('courses.cancel', [$course, auth()->user()]) }}" method="POST" class="ml-2">
                            @csrf
                            <button type="submit" class="px-2 py-1 md:px-4 md:py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                Nein
                            </button>
                        </form>
                    </span>                                   
                @endforeach
            </x-content-view>
        @endif

        @if($lastAttended)
            <x-content-view>
                <h2 class="text-l font-semibold text-gray-700 mb-1">Zuletzt besuchter Kurs:</h2>
                <x-simple-course-table :course="$lastAttended" :user="auth()->user()" />
                <div class="mb-4"></div>
            </x-content-view>
        @endif

        @if(!$plannedCourses->isEmpty())
            <x-content-view>
                <h1 class="text-xl font-bold text-gray-800">Geplante Kurse</h1>
                @foreach ($plannedCourses->sortBy('courseType.order')->groupBy('courseType.name') as $courseTypeName => $coursesForType)
                    <h2 class="mt-2 text-l font-semibold text-gray-700 mb-1">{{ $courseTypeName }}</h2>
                    <x-simple-course-table :courses="$coursesForType" :user="auth()->user()" status="true" />                                   
                @endforeach
            </x-content-view>
        @endif
       
        @if($courses->isEmpty())
            <x-content-view>
                <x-no-data>Verfügbare Kurse werden hier aufgelistet.</x-no-data>
            </x-content-view>
        @else
            <x-content-view>
                <h1 class="text-xl font-bold text-gray-800">Verfügbare Kurse</h1>
                @foreach ($courses->sortBy('courseType.order')->groupBy('courseType.name') as $courseTypeName => $coursesForType)
                    <h2 class="mt-2 text-l font-semibold text-gray-700 mb-1">{{ $courseTypeName }}</h2>
                    <x-simple-course-table :courses="$coursesForType" :user="auth()->user()" status="true" />                                   
                @endforeach
            </x-content-view>
        @endif
</x-app-layout>