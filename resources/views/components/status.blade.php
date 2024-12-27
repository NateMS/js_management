@if($status == 'signed_up')
    <span class="flex items-center justify-center sm:w-32 h-6 sm:h-8 text-xs uppercase font-bold text-blue-500 bg-blue-100 rounded-full">
        eingetragen
    </span>
@elseif($status == 'registered')
    <span class="flex items-center justify-center sm:w-32 h-6 sm:h-8 text-xs uppercase font-bold text-green-500 bg-green-100 rounded-full">
        angemeldet
    </span>
@elseif($status == 'attended')
    <span class="flex items-center justify-center sm:w-32 h-6 sm:h-8 text-xs uppercase font-bold text-purple-400 bg-purple-100 rounded-full">
        teilgenommen
    </span>
@elseif($status == 'cancelled')
    <span class="flex items-center justify-center sm:w-32 h-6 sm:h-8 text-xs uppercase font-bold text-red-500 bg-red-100 rounded-full">
        abgesagt
    </span>
@endif