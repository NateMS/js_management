<x-app-layout>
    <x-slot name="header">
        <x-header>Leiter</x-header>
    </x-slot>

    <x-content-view> 
        <div class="max-w-xl">
            <h3 class="font-semibold text-xl">{{ $user->name }}</h3>
            <table class="mt-4 w-full text-sm text-left text-gray-500">
                <tr class="text-gray-900">
                    <th class="text-gray-700 uppercase pr-2 py-3">Geburtsdatum</th>
                    <td class="px-2 py-2">{{ $user->formattedBirthdate }}</td>
                </tr>
                @if (auth()->user()->isJSVerantwortlich() || auth()->user()->id == $user->id)
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">E-Mail</th>
                        <td class="px-2 py-2">{{ $user->email }}</td>
                    </tr>
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">J&S-Nummer</th>
                        <td class="px-2 py-2">
                            @if (auth()->user()->isJSVerantwortlich())
                                <form action="{{ route('users.add_js_number', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <span class="flex">
                                        <input class="w-32 bg-gray-200 rounded-md py-2 border border-transparent" type="text" name="js_number" id="js_number" value="{{ old('js_number', $user->js_number) }}" />
                                        <x-button type="submit" class="ml-2 px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                            ✓
                                        </x-button>
                                    </span>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endif
                <tr class="text-gray-900">
                    <th class="text-gray-700 uppercase pr-2 py-3">J&S Gültigkeit bis</th>
                    <td class="px-2 py-2 font-bold {{ $user->getRevalidationColorClass() }}">{{ $validityDate }}</td>
                </tr>
                <tr class="text-gray-900">
                    <th class="text-gray-700 uppercase pr-2 py-3">Hat Kids-Ausbildung</th>
                    <td class="px-2 py-2 font-bold">{{ $user->hasAttendedKidsCourse() ? 'Ja' : 'Nein' }}</td>
                </tr>
            </table>
        </div> 
    </x-content-view>

    @if(!$planned->isEmpty())
        <x-content-view>
            <h1 class="text-xl font-bold text-gray-800">Geplante Kurse</h1>
            @foreach ($planned->sortBy('courseType.order')->groupBy('courseType.name') as $courseTypeName => $coursesForType)
                <h2 class="mt-2 text-l font-semibold text-gray-700 mb-1">{{ $courseTypeName }}</h2>
                <x-simple-course-table :courses="$coursesForType" :user="$user" status="true" />                                   
            @endforeach
        </x-content-view>
    @endif

    @if($past->isNotEmpty())
        <x-content-view>
            <h1 class="text-xl font-bold text-gray-800">Vergangene Kurse</h1>
            @foreach ($past->sortBy('courseType.order')->groupBy('courseType.name') as $courseTypeName => $coursesForType)
                <h2 class="mt-2 text-l font-semibold text-gray-700 mb-1">{{ $courseTypeName }}</h2>
                <x-simple-course-table :courses="$coursesForType" :user="$user" status="true" />                                   
            @endforeach
        </x-content-view>
    @endif

</x-app-layout>