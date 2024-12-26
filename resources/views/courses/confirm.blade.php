<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="text-sm text-gray-600 justify-center relative p-6">
            <div class="text-l font-semibold text-gray-700 text-center pb-3">Bestätigung Kursteilnahme</div>
            {{ $message }}
            @if(auth()->user())
                <form method="POST" action="{{ route('logout') }}" class="absolute -top-12 sm:-right-6 -right-3">
                    @csrf
                    <button type="submit" class="underline text-sm text-gray-600 sm:text-gray-300 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-2">
                        {{ __('Abmelden') }}
                    </button>
                </form>
                <br>
                <div class="flex justify-center mt-4">
                    <a href="{{ route('home') }}" class="px-4 py-2 inline-block bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Zur Startseite</a>
                </div>        
            @else
                <br>
                <div class="flex justify-center mt-4">
                    <a href="{{ route('home') }}" class=" px-4 py-2 inline-block bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Anmelden</a>
                </div>
            @endif
        </div>        
    </x-authentication-card>
</x-guest-layout>
