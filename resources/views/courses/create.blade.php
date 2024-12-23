<x-app-layout>
    <x-slot name="header">
        <x-header>{{ __('Kurs erfassen') }}</x-header>
    </x-slot>

    <x-content-view>
        <h2 class="text-xl font-semibold mb-4">Kursdetails</h2>

        <form action="{{ route('courses.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <x-label for="course_nr" value="Kursnummer" />
                <x-input type="text" name="course_nr" id="course_nr" class="mt-1 block w-full" />
                @error('course_nr')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <x-label for="name" value="Name" />
                <x-input type="text" name="name" id="name" class="mt-1 block w-full" required />
                @error('name')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <!-- Course Type -->
                <x-label for="course_type_id" value="Kurstyp" />
                <select name="course_type_id" id="course_type_id" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">Wähle den Kurstyp</option>
                    @foreach($courseTypes as $courseType)
                        <option value="{{ $courseType->id }}">{{ $courseType->name }}</option>
                    @endforeach
                </select>
                @error('course_type_id')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <!-- Start Date -->
                <x-label for="date_start" value="Startdatum" />
                <x-input type="date" name="date_start" id="date_start" class="mt-1 block w-full" required />
                @error('date_start')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <!-- End Date -->
                <x-label for="date_end" value="Enddatum" />
                <x-input type="date" name="date_end" id="date_end" class="mt-1 block w-full" required />
                @error('date_end')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <!-- Location -->
                <x-label for="location" value="Ort" />
                <x-input type="text" name="location" id="location" class="mt-1 block w-full" required />
                @error('location')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <!-- Prerequisites -->
                <x-label for="prerequisites" value="Voraussetzungen" />
                <textarea name="prerequisites" id="prerequisites" rows="3" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                @error('prerequisites')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <!-- Registration Deadline -->
                <x-label for="registration_deadline" value="Anmeldeschluss" />
                <x-input type="date" name="registration_deadline" id="registration_deadline" class="mt-1 block w-full" required />
                @error('registration_deadline')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <!-- Link -->
                <x-label for="link" value="Link" />
                <x-input type="url" name="link" id="link" class="mt-1 block w-full" />
                @error('registration_deadline')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-3 md:mt-6">
                <div></div>
                <div>
                    <x-button>
                        Kurs erstellen
                    </x-button>
                    <a href="{{ route('courses.index') }}" class="ml-3 tn btn-secondary">Abbrechen</a>
                </div>
            </div>
        </form>
    </x-content-view>
</x-app-layout>