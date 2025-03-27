<x-app-layout>
    <x-slot name="header">
        <x-header>Leiter:in</x-header>
    </x-slot>

    <x-content-view> 
        <div class="max-w-xl">
            <h3 class="font-semibold text-xl">{{ $user->name }}</h3>
            <table class="mt-4 w-full text-sm text-left text-gray-500">
                <tr class="text-gray-900">
                    <th class="text-gray-700 uppercase pr-2 py-3">Geburtsdatum</th>
                    <td class="px-2 py-2">{{ $user->formattedBirthdate }}</td>
                </tr>
                @if (auth()->user()->canEditUser($user) || auth()->user()->id == $user->id)
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">E-Mail</th>
                        <td class="px-2 py-2">{{ $user->email }}</td>
                    </tr>
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">J&S-Nummer</th>
                        <td class="px-2 py-2">{{ $user->js_number }}</td>
                    </tr>
                    <tr class="text-gray-900">
                        <th class="text-gray-700 uppercase pr-2 py-3">Rolle</th>
                        <td class="px-2 py-2">{{ $user->role }}</td>
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
        @if (auth()->user()->canEditUser($user) && auth()->user()->id !== $user->id) 
            <form class="inline" action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Willst du diesen Benutzer löschen?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Löschen</button>
            </form>
        @endif
        <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 inline-block bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Bearbeiten</a>
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