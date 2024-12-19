<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leiter') }}
        </h2>
    </x-slot>
    <x-content-view class="space-y-5">
        @if($past->isNotEmpty())
            <h3 class="text-l font-semibold text-gray-700 mb-1">Gültigkeit verfallen</h3>
            <table class="w-full text-xs md:text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-2 sm:px-3 lg:px-6 py-2">Name</th>
                        <th class="px-2 sm:px-3 lg:px-6 py-2 hidden xs:table-cell">Geburtsdatum</th>
                        <th class="px-2 sm:px-3 lg:px-6 py-2 hidden md:table-cell">E-Mail</th>
                        <th class="px-2 sm:px-3 lg:px-6 py-2 hidden sm:table-cell">J&S-Nummer</th>
                        <th class="px-2 sm:px-3 lg:px-6 py-2">J&S gültig bis</th>
                        <th class="px-2 sm:px-3 lg:px-6 py-2">Kids</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($past as $user)
                        <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('users.show', $user->id) }}'">
                            <td class="px-2 sm:px-3 lg:px-6 py-2 font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-2 sm:px-3 lg:px-6 py-2 hidden xs:table-cell">{{ $user->formattedBirthdate }}</td>
                            <td class="px-2 sm:px-3 lg:px-6 py-2 hidden md:table-cell">{{ $user->email }}</td>
                            <td class="px-2 sm:px-3 lg:px-6 py-2 hidden sm:table-cell">{{ $user->js_number }}</td>
                            <td class="px-2 sm:px-3 lg:px-6 py-2 font-bold text-red-800">{{ $user->getCourseRevalidationDate() }}</td>
                            <td class="px-2 sm:px-3 lg:px-6 py-2 font-bold">{{ $user->hasAttendedKidsCourse() ? 'Ja' : 'Nein' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        @if($soon->isNotEmpty())
            <div class="my-5">
                <h3 class="text-l font-semibold text-gray-700 mb-1">Kurs bald notwendig</h3>
                <table class="w-full text-xs md:text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-2 sm:px-3 lg:px-6 py-2">Name</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2 hidden xs:table-cell">Geburtsdatum</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2 hidden md:table-cell">E-Mail</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2 hidden sm:table-cell">J&S-Nummer</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2">J&S gültig bis</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2">Kids</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($soon as $user)
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('users.show', $user->id) }}'">
                                <td class="px-2 sm:px-3 lg:px-6 py-2 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 hidden xs:table-cell">{{ $user->formattedBirthdate }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 hidden md:table-cell">{{ $user->email }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 hidden sm:table-cell">{{ $user->js_number }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 font-bold text-yellow-600">{{ $user->getCourseRevalidationDate() }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 font-bold">{{ $user->hasAttendedKidsCourse() ? 'Ja' : 'Nein' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($future->isNotEmpty())
                <h3 class="text-l font-semibold text-gray-700 mb-1">Gültigkeit > 18 Monate</h3>
                <table class="w-full text-xs md:text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-2 sm:px-3 lg:px-6 py-2">Name</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2 hidden xs:table-cell">Geburtsdatum</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2 hidden md:table-cell">E-Mail</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2 hidden sm:table-cell">J&S-Nummer</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2">J&S gültig bis</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2">Kids</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($future as $user)
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('users.show', $user->id) }}'">
                                <td class="px-2 sm:px-3 lg:px-6 py-2 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 hidden xs:table-cell">{{ $user->formattedBirthdate }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 hidden md:table-cell">{{ $user->email }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 hidden sm:table-cell">{{ $user->js_number }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 font-bold text-green-700">{{ $user->getCourseRevalidationDate() }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 font-bold">{{ $user->hasAttendedKidsCourse() ? 'Ja' : 'Nein' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        @endif
        @if($none->isNotEmpty())
            <div class="my-5">
                <h3 class="text-l font-semibold text-gray-700 mb-1">Ohne Grundkurs</h3>
                <table class="w-full text-xs md:text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-2 sm:px-3 lg:px-6 py-2">Name</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2 hidden xs:table-cell">Geburtsdatum</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2 hidden md:table-cell">E-Mail</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2 hidden sm:table-cell">J&S-Nummer</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2">J&S gültig bis</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2">Kids</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($none as $user)
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('users.show', $user->id) }}'">
                                <td class="px-2 sm:px-3 lg:px-6 py-2 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 hidden xs:table-cell">{{ $user->formattedBirthdate }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 hidden md:table-cell">{{ $user->email }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 hidden sm:table-cell">{{ $user->js_number }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 font-bold text-green-600">{{ $user->getCourseRevalidationDate() }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 font-bold">{{ $user->hasAttendedKidsCourse() ? 'Ja' : 'Nein' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($under18->isNotEmpty())
            <div class="my-5">
                <h3 class="text-l font-semibold text-gray-700 mb-1">u18 Leiter</h3>
                <table class="w-full text-xs md:text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-2 sm:px-3 lg:px-6 py-2">Name</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2 hidden xs:table-cell">Geburtsdatum</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2 hidden md:table-cell">E-Mail</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2 hidden sm:table-cell">J&S-Nummer</th>
                            <th class="px-2 sm:px-3 lg:px-6 py-2">1418</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($under18 as $user)
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('users.show', $user->id) }}'">
                                <td class="px-2 sm:px-3 lg:px-6 py-2 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 hidden xs:table-cell">{{ $user->formattedBirthdate }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 hidden md:table-cell">{{ $user->email }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 hidden sm:table-cell">{{ $user->js_number }}</td>
                                <td class="px-2 sm:px-3 lg:px-6 py-2 font-bold">{{ $user->hasAttendedUnder18Course() ? 'Ja' : 'Nein' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-content-view>
</x-app-layout>
