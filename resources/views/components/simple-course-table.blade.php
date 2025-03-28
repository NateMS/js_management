@props(['courses' => null, 'status' => false, 'course' => null, 'user' => null])

@if (isset($courses) || isset($course))
    <div class="overflow-x-auto">
        <table class="min-w-full text-xs md:text-sm lg:text-sm text-left text-gray-500">
            @if (isset($courses))
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr class="text-left">
                        <th class="px-2 w-1/6 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3 hidden md:table-cell">Kursnummer</th>
                        <th class="px-2 w-1/6 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3 {{ $status ? 'hidden lg:table-cell' : '' }}">Name</th>
                        <th class="px-2 w-1/6 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3">Ort</th>
                        <th class="px-2 w-1/6 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3">Datum</th>
                        <th class="px-2 w-1/6 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3">#</th>
                        @if ($status)
                            <th class="px-2 w-1/6 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3">Status</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                        <tr class="{{ $course->is_full ? 'bg-orange-50' : 'odd:bg-white even:bg-gray-50' }} hover:bg-blue-50 whitespace-nowrap text-gray-900 border-b clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('courses.show', $course->id) }}'">
                            <td class="px-2 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3 font-bold hidden md:table-cell">{{ $course->course_nr ?? '-' }}</td>
                            <td class="px-2 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3 {{ $status ? 'hidden lg:table-cell' : '' }}">{{ $course->name }}</td>
                            <td class="px-2 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3">{{ $course->location }}</td>
                            <td class="px-2 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3">{{ $course->formatted_date_range }}<span class="hidden lg:inline">{{ $course->duration }}</span></td>
                            <td class="px-2 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3">{{ $course->participation_number }}</td>
                            @if ($status)
                                <td class="px-2 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3">
                                    @if($course->userStatus($user->id))
                                        <x-status :status="$course->userStatus($user->id)->status" />
                                    @else
                                        <form action="{{ route('courses.signup', [$course, $user]) }}" method="POST" class="ml-auto" onsubmit="return confirm('Möchtest du dich für diesen Kurs eintragen? Der J&S-Coach wird per E-Mail informiert.');">
                                            @csrf
                                            <button type="submit" onclick="event.stopPropagation()" class="px-2 py-1 md:px-4 md:py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                                                Eintragen
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            @elseif (isset($course))
                <tbody>
                    <tr class="{{ $course->is_full ? 'bg-orange-50' : 'odd:bg-white even:bg-gray-50' }} hover:bg-blue-50 whitespace-nowrap text-gray-900 border-b clickable-row" style="cursor: pointer;" onclick="window.location='{{ route('courses.show', $course->id) }}'">
                        @if ($status && $course->isInPast() && $course->userStatus($user->id)?->status == 'registered')
                            <td class="px-2 w-1/6 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3 font-bold">{{ $course->courseType->name }}<span class="hidden md:inline">, {{ $course->course_nr }}</span></td>
                            <td class="px-2 w-1/6 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3 hidden md:table-cell">{{ $course->name }}</td>
                            <td class="px-2 w-1/6 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3">{{ $course->location }}</td>
                            <td class="px-2 w-1/6 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3">{{ $course->formatted_date_range }}<span class="hidden md:inline">{{ $course->duration }}</span></td>                          
                        @else
                            <td class="px-2 w-1/4 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3 font-bold">{{ $course->courseType->name }}<span class="hidden md:inline">, {{ $course->course_nr }}</span></td>
                            <td class="px-2 w-1/4 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3 hidden md:table-cell">{{ $course->name }}</td>
                            <td class="px-2 w-1/4 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3">{{ $course->location }}</td>
                            <td class="px-2 w-1/4 py-2 sm:px-3 sm:py-2 md:px-3 lg:px-4 lg:py-3">{{ $course->formatted_date_range }}<span class="hidden md:inline">{{ $course->duration }}</span></td>
                        @endif
                    </tr>
                </tbody>
            @endif
        
        </table>
    </div>
@endif