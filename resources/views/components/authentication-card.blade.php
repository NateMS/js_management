<div class="min-h-screen flex flex-col bg-gray-100 sm:bg-black/30 sm:justify-center items-center pt-6 sm:pt-0">
    <div clas="flex relative">
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white sm:bg-white/70 shadow-md sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
