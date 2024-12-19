<x-guest-layout>
    
    <x-authentication-card>
        <x-slot name="logo">
                <x-authentication-card-logo class="m-auto" />
        </x-slot>

        <div class="text-sm text-gray-600 justify-center relative p-6">
            <div class="text-l font-semibold text-gray-700 text-center pb-3">Keine Riege</div>
            Du bist noch keiner Riege zugewiesen. Hast du eine E-Mail erhalten? Ansonsten kontaktiere bitte den/die J&S Verantwortliche*n deiner Riege.
            <form method="POST" action="{{ route('logout') }}" class="absolute -top-12 sm:-right-6 -right-3">
                @csrf
                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-2">
                    {{ __('Abmelden') }}
                </button>
            </form>
        </div>        
    </x-authentication-card>
    
</x-guest-layout>
