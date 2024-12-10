<x-app-layout>
    <x-slot name="header">
        <x-header>Eingetragene Leiter</x-header>
    </x-slot>

    <x-content-view>
        @forelse($courses as $course)
                <div class="mb-4">
                    <x-simple-course-table :course="$course" />

                    @if($course->users->isEmpty())
                        <p>No users have signed up for this course yet.</p>
                    @else
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr class="text-left">
                                <th class="px-6 py-3">J&S Nummer</th>
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">E-Mail</th>
                                <th class="px-6 py-3">Geburtsdatum</th>
                                <th class="px-6 py-3">Eingetragen am</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course->users as $user)
                                <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 whitespace-nowrap text-gray-900 border-b">
                                    <td class="px-6 py-4">{{ $user->js_number }}</td>    
                                    <td class="px-6 py-4">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">{{ $user->formattedBirthdate }}</td>
                                    <td class="px-6 py-4">{{ $user->pivot->formatted_timestamp }}</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('courses.register', [$course, $user]) }}" method="POST" class="ml-auto">
                                        @csrf
                                        <x-button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                            Anmelden
                                        </x-button>
                                    </td>
                                </form>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    @endif
                </div>
            @empty
                <p>Keine Kurse gefunden.</p>
            @endforelse
    </x-content-view>
</x-app-layout>