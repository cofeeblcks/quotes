<div class="relative bg-customPrimary p-4 sm:p-6 rounded-md overflow-hidden mb-8">
    @if( Auth::check() )
        @php
            $currentTime = date('H:i:s');
            $currentTimeHour = date('H', strtotime($currentTime));
            $morningStart = 5;
            $afternoonStart = 12;
            $eveningStart = 18;

            if ($currentTimeHour >= $morningStart && $currentTimeHour < $afternoonStart) {
                $greeting = 'Buenos días';
            } elseif ($currentTimeHour >= $afternoonStart && $currentTimeHour < $eveningStart) {
                $greeting = 'Buenas tardes';
            } else {
                $greeting = 'Buenas noches';
            }
        @endphp
    @endif

    <!-- Content -->
    <div class="relative">
        <h1 class="text-2xl md:text-3xl text-white font-bold mb-1 max-xs:text-xl">{{ $greeting }}, {{ Auth::user()->name }} 👋</h1>
    </div>
</div>
