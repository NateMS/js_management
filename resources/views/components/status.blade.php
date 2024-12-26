@if($status == 'signed_up')
    <span class="flex items-center justify-center w-24 sm:w-28 h-6 sm:h-8 text-xs sm:text-sm text-white bg-blue-800/70 border border-blue-900/80 rounded-full">
        eingetragen
    </span>
@elseif($status == 'registered')
    <span class="flex items-center justify-center w-24 sm:w-28 h-6 sm:h-8 text-xs sm:text-sm text-white bg-green-900/70 border border-green-900/80 rounded-full">
        angemeldet
    </span>
@elseif($status == 'attended')
    <span class="flex items-center justify-center w-24 sm:w-28 h-6 sm:h-8 text-xs sm:text-sm text-white bg-purple-900/70 border-purple-900/80 rounded-full">
        teilgenommen
    </span>
@elseif($status == 'cancelled')
    <span class="flex items-center justify-center w-24 sm:w-28 h-6 sm:h-8 text-xs sm:text-sm text-white bg-red-900/70 border-red-900/80 rounded-full">
        abgesagt
    </span>
@endif