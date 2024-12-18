<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leiter') }}
        </h2>
    </x-slot>
    <x-content-view>
        @if($past->isNotEmpty())
            <div class="my-5">
                <h3 class="font-semibold text-l">Gültigkeit verfallen</h3>
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">E-Mail</th>
                            <th class="px-6 py-3">J&S-Nummer</th>
                            <th class="px-6 py-3">J&S gültig bis</th>
                            <th class="px-6 py-3">Kids</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($past as $user)
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('users.show', $user->id) }}'">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ $user->js_number }}</td>
                                <td class="px-6 py-4 font-bold text-red-800">{{ $user->getCourseRevalidationDate() }}</td>
                                <td class="px-6 py-4 font-bold">{{ $user->hasAttendedKidsCourse() ? 'Ja' : 'Nein' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($soon->isNotEmpty())
            <div class="my-5">
                <h3 class="font-semibold text-l">Kurs bald notwendig</h3>
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">E-Mail</th>
                            <th class="px-6 py-3">J&S gültig bis</th>
                            <th class="px-6 py-3">Kids</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($soon as $user)
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('users.show', $user->id) }}'">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ $user->js_number }}</td>
                                <td class="px-6 py-4 font-bold text-yellow-600">{{ $user->getCourseRevalidationDate() }}</td>
                                <td class="px-6 py-4 font-bold">{{ $user->hasAttendedKidsCourse() ? 'Ja' : 'Nein' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($future->isNotEmpty())
            <div class="my-5">
                <h3 class="font-semibold text-l">Gültigkeit > 18 Monate</h3>
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">E-Mail</th>
                            <th class="px-6 py-3">J&S-Nummer</th>
                            <th class="px-6 py-3">J&S gültig bis</th>
                            <th class="px-6 py-3">Kids</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($future as $user)
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('users.show', $user->id) }}'">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ $user->js_number }}</td>
                                <td class="px-6 py-4 font-bold text-green-700">{{ $user->getCourseRevalidationDate() }}</td>
                                <td class="px-6 py-4 font-bold">{{ $user->hasAttendedKidsCourse() ? 'Ja' : 'Nein' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($none->isNotEmpty())
            <div class="my-5">
                <h3 class="font-semibold text-l">Ohne Grundkurs</h3>
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">E-Mail</th>
                            <th class="px-6 py-3">J&S-Nummer</th>
                            <th class="px-6 py-3">J&S gültig bis</th>
                            <th class="px-6 py-3">Kids</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($none as $user)
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('users.show', $user->id) }}'">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ $user->js_number }}</td>
                                <td class="px-6 py-4 font-bold text-green-600">{{ $user->getCourseRevalidationDate() }}</td>
                                <td class="px-6 py-4 font-bold">{{ $user->hasAttendedKidsCourse() ? 'Ja' : 'Nein' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-content-view>
</x-app-layout>
