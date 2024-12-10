<x-app-layout>
    <x-slot name="header">
        <x-header>{{ $course->courseType->name }} ({{ $course->course_nr }})</x-header>
    </x-slot>
    <div class="max-w-7xl mx-auto md:grid md:grid-cols-4 md:gap-4">
        <div class="md:col-span-2">
            <x-content-view>  
                <h3 class="font-semibold text-xl">{{ $course->course_nr }}</h3>
                <table class="mt-4 w-full text-sm text-left text-gray-500">
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">Kurstyp</th>
                        <td class="px-2 py-2">{{ $course->courseType->name }}</td>
                    </tr>
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">Name</th>
                        <td class="px-2 py-2">{{ $course->name }}</td>
                    </tr>
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">Ort</th>
                        <td class="px-2 py-2">{{ $course->location }}</td>
                    </tr>
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">Datum</th>
                        <td class="px-2 py-2">{{ $course->formatted_date_range }} ({{ $course->duration }})</td>
                    </tr>
                    @if($course->prerequisites)
                        <tr class="text-gray-900">
                            <th class="text-gray-700 uppercase pr-2 py-3">Voraussetzungen</th>
                            <td class="px-2 py-2">{{ $course->prerequisites }}</td>
                        </tr>
                    @endif
                    <tr class=" text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">Anmeldefrist</th>
                        <td class="px-2 py-2">{{ $course->registration_deadline->format('d.m.Y') }}</td>
                    </tr>
                    @if($course->notes)
                        <tr class="text-gray-900">
                            <th class="text-gray-700 uppercase pr-2 py-3">Notizen</th>
                            <td class="px-2 py-2">{{ $course->notes }}</td>
                        </tr>
                    @endif
                    @if($course->link)
                        <tr class="text-gray-900">
                            <th class="text-gray-700 uppercase pr-2 py-3">Link</th>
                            <td class="px-2 py-2"><a class="text-blue-500 hover:text-blue-700 underline font-medium" href="{{ $course->link }}" target="_blank">Link</a></td>
                        </tr>
                    @endif
                </table>

                @if ($course->registration_deadline >= now())
                    @if ($isRegistered)
                        <form action="{{ route('courses.cancel', $course) }}" method="POST">
                            @csrf
                            <x-button class="mt-6 bg-red-700">Austragen</x-button>
                        </form>
                    @else
                        <form action="{{ route('courses.signup', $course) }}" method="POST">
                            @csrf
                            <x-button class="mt-6 btn btn-primary">Eintragen</x-button>
                        </form>
                    @endif
                @endif

            </x-content-view>
        </div>
        <div class="md:col-span-2">
            <x-content-view>
                <h3 class="font-semibold text-xl">Kursteilnehmer</h3>
                @if($users->isEmpty())
                    <x-no-data>Es sind noch keine Teilnehmer für diesen Kurs eingetragen.</x-no-data>
                @else
                    <table class="mt-4 w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr class="text-left">
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Eingetragen am</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr class="odd:bg-white even:bg-gray-50 text-gray-900 border-b">
                                    <td class="px-6 py-4">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->pivot->created_at->format('d.m.Y') }}</td> <!-- Displaying the registration date -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </x-content-view> 
        </div>
    </div>
</x-app-layout>