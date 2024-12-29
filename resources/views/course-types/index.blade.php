<x-app-layout>
    <x-slot name="header">
        <x-header>{{ __('Alle Kurstypen') }}</x-header>
    </x-slot>
    <x-content-view>  
        <div class="flex justify-end">
            <a href="{{ route('course-types.create') }}" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Neuer Kurstyp</a>
        </div>
        @if($courseTypes->isEmpty())
            <x-no-data>Keine Kurstypen erfasst.</x-no-data>
        @else
            
            <table class="mt-4 w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr class="text-left">
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Reihenfolge</th>
                        <th class="px-6 py-3">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courseTypes as $courseType)
                        <tr class="odd:bg-white even:bg-gray-50 text-gray-900 border-b">
                            <td class="px-6 py-4 font-bold">{{ $courseType->name }}</td>
                            <td class="px-6 py-4">{{ $courseType->order }}</td>
                            <td class="px-6 py-4 font-medium">
                                <a href="{{ route('course-types.edit', $courseType) }}" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Bearbeiten</a>
                            
                                @if ($courseType->courses->isEmpty())
                                    <form class="inline" action="{{ route('course-types.destroy', $courseType) }}" method="POST" class="d-inline" onsubmit="return confirm('Willst du diesen Kurstyp löschen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Löschen</button>
                                    </form>
                                @else
                                    <span class="px-4 py-2 bg-red-700/70 border border-transparent rounded-md font-semibold text-xs text-white/70 uppercase tracking-widest cursor-not-allowed">Löschen</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </x-content-view>
</x-app-layout>
