<x-app-layout>
    <x-slot name="header">
        <x-header>{{ __('Alle Kurse') }}</x-header>
    </x-slot>
    <x-content-view>
        <div class="grid grid-cols-2">
            <span>
                @if(!$courses->isEmpty())
                    <form method="GET" action="{{ route('courses.index') }}">
                        <label class="text-xl text-gray-700" for="year">Jahr:</label>
                        <select name="year" id="year" onchange="this.form.submit()" class="text-xl cursor-pointer hover:border-transparent active:border-transparent focus:border-transparent text-gray-700 border-transparent bg-gray-100 rounded-md hover:bg-gray-200 transition ease-in-out duration-150">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif
            </span>
            <div>
                <span class="flex justify-end">
                    <a href="{{ route('courses.create') }}" class="align-middle px-4 py-2 bg-gray-800 border border-transparent rounded-md inline-block font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Neuer Kurs</a>
                </span>    
            </div>
        </div>
        @if($courses->isEmpty())
            <x-no-data>Keine Kurse erfasst.</x-no-data>
        @else
            @foreach ($courses->sortBy('courseType.order')->groupBy('courseType.name') as $courseTypeName => $coursesForType)
                    <h2 class="mt-6 text-l font-semibold text-gray-700 mb-1">{{ $courseTypeName }}</h2>
                    <x-simple-course-table :courses="$coursesForType" />
            @endforeach
        @endif
    </x-content-view>
</x-app-layout>
