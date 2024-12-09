<x-app-layout>
    <x-slot name="header">
        <x-header>{{ __('Kurstyp erfassen') }}</x-header>
    </x-slot>

    <x-content-view>
        <h2 class="text-xl font-semibold mb-4">Kurstyp erfassen</h2>

        <form action="{{ route('course-types.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <x-label for="name" value="Name" />
                <x-input type="text" name="name" id="name" class="mt-1 block w-full" required />
                @error('name')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <x-label for="minimum_age" value="Mindestalter" />
                <x-input type="number" name="minimum_age" id="minimum_age" class="mt-1 block w-full" />
                @error('minimum_age')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <x-label for="maximum_age" value="Höchstalter" />
                <x-input type="number" name="maximum_age" id="maximum_age" class="mt-1 block w-full" />
                @error('maximum_age')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <x-label for="requires_repetition" value="Braucht Wiederholung alle 2 Jahre?" />
                <x-input type="checkbox" name="requires_repetition" id="requires_repetition" class="mt-1 block" />
                @error('requires_repetition')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <x-label for="order" value="Reihenfolge" />
                <x-input type="number" name="order" id="order" class="mt-1 block w-full" required />
                @error('order')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <x-label for="prerequisite_course_type_id" value="Voraussetzung für Kurse dieses Kurstyps" />
                <select name="prerequisite_course_type_id" id="prerequisite_course_type_id" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Kurstyp wählen</option>
                    @foreach($courseTypes as $courseType)
                        <option value="{{ $courseType->id }}">{{ $courseType->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <x-label for="teams" value="Kurse dieses Kurstyps für folgende Gruppen sichtbar machen" />
                <select multiple name="teams[]" id="teams" class="block mt-1 w-full">
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}" @if(isset($courseType) && $courseType->teams->contains($team->id)) selected @endif>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-3 md:mt-6">
                <div></div>
                <div>
                    <x-button>
                        Kurstyp erstellen
                    </x-button>
                    <a href="{{ route('course-types.index') }}" class="ml-3 btn btn-secondary">Abbrechen</a>
                </div>
            </div>
        </form>
    </x-content-view>
</x-app-layout>