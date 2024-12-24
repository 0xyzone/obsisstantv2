<x-roster :tournament=$tournament :activeMatch=$activeMatch>
        <div><img src="{{ $activeMatch->teamA->logo ? asset('/storage/' . $activeMatch->teamA->logo) : "" }}" alt="" class="min-w-[20rem] max-w-[20rem] aspect-square bg-white object-cover"></div>
        <div class="w-max py-2 px-8 bg-gray-200 -skew-x-[30deg] text-6xl font-bold shadow-xl -translate-y-5">
            <p class="skew-x-[30deg]">{{ $activeMatch->teamA->name }}</p>
        </div>
        <div class="flex flex-col items-center gap-2 min-w-[1000px] max-w-[1000px] text-center">
            @foreach ($activeMatch->statsForTeamA as $stat)
            <div style="background-color: {{ $tournamentPrimaryColor }};" class="py-4 {{ $textColor }} w-full skew-x-[45deg]">
                <p class="-skew-x-[45deg]">{{ $stat->player->nickname }}</p>
            </div>
            @endforeach

        </div>
</x-roster>
