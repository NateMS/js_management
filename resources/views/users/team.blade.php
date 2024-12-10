<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leiter') }}
        </h2>
    </x-slot>

    <x-content-view>
        @if($users->isEmpty())
            <x-no-data>Es gibt keine Benutzer in Ihrem Team.</x-no-data>
        @else
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">E-Mail</th>
                        <th class="px-6 py-3">Rolle</th>
                        <th class="px-6 py-3">Beigetreten</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">{{ $user->teamrole(Auth::user()->currentTeam)->name }}</td>
                            <td class="px-6 py-4">{{ $user->created_at->format('d.m.Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </x-content-view>
</x-app-layout>
