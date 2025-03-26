<x-app-layout>
    <x-slot name="header">
        <x-header>{{ $title }}</x-header>
    </x-slot>

    <x-content-view>
        <h2 class="text-xl font-semibold mb-4">Benutzerangaben</h2>

        <form action="{{ $submitUrl }}" method="POST">
            @csrf
            @method($method)
            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <x-label for="name" value="Name" />
                <x-input type="text" name="name" id="name" class="mt-1 block w-full" value="{{ old('name', $user->name) }}" />
                @error('name')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <x-label for="email" value="E-Mail" />
                <x-input type="text" name="email" id="email" class="mt-1 block w-full"  value="{{ old('email', $user->email) }}" required />
                @error('email')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            @if($method == 'POST')
                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                    <x-label for="password" value="Passwort" />
                    <x-input type="text" name="password" id="password" class="mt-1 block w-full"  value="" required />
                    @error('password')
                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <x-label for="birthdate" value="Geburtsdatum" />
                <x-input type="date" name="birthdate" id="birthdate" class="mt-1 block w-full"  value="{{ old('birthdate', $user->birthdate ? $user->birthdate->format('Y-m-d') : '') }}" required />
                @error('birthdate')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <x-label for="js_number" value="J&S Nummer" />
                <x-input type="text" name="js_number" id="js_number" class="mt-1 block w-full"  value="{{ old('js_number', $user->js_number) }}" required />
                @error('js_number')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-6">
                <x-label for="role" value="Rolle" />
                <select name="role" id="role" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    @foreach (array_reverse(Laravel\Jetstream\Jetstream::$roles) as $role)
                        <option value="{{ $role->key }}"
                        {{ old('role') == $role->key || $user->role == $role->name ? 'selected' : '' }}>
                        {{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role')
                    <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>
           
            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-3 md:mt-6">
                <div></div>
                <div>
                    <x-button>
                        {{ $buttonTitle }}
                    </x-button>
                    <a href="{{ $backUrl }}" class="ml-3 tn btn-secondary">Abbrechen</a>
                </div>
            </div>
        </form>
    </x-content-view>
</x-app-layout>