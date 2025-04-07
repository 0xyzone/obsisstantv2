<x-roster :tournament=$tournament :activeMatch=$activeMatch>

    <div class="grid grid-cols-5 items-center max-w-[1720px] gap-2 text-center w-full">
        @foreach ($activeMatch->statsForTeamA as $stat)
        <div class="">
            <img src="{{ $stat->player->portrait ? asset('storage/' . $stat->player->portrait) : ($stat->player->gender == "male" ? asset('img/placeholder/1w.png') : asset('img/placeholder/2w.png'))  }}" alt="" class="w-full">

            <div style="background-color: {{ $tournamentPrimaryColor }}; clip-path: polygon(100% 0%, 98% 19%, 100% 38%, 96% 54%, 100% 61%, 98% 85%, 100% 100%, 0% 100%, 4% 81%, 0 70%, 4% 66%, 0 40%, 3% 28%, 0 20%, 2% 0);
" class="py-2 {{ $textColor }} w-full drop-shadow-lg">
                <p class="">{{ $stat->player->nickname }}</p>
            </div>

        </div>
        @endforeach
    </div>
    <div class="flex mt-10">
        <div>
            <img src="{{ $activeMatch->teamA->logo ? asset('/storage/' . $activeMatch->teamA->logo) : "" }}" alt="" class="min-w-[10rem] max-w-[10rem] aspect-square bg-white object-cover">
        </div>
        <div class="w-max py-2 pr-32 px-8 bg-gray-200 text-6xl font-bold shadow-lg flex items-center" style="clip-path: polygon(0 0%, 10% 0, 90% 0, 100% 0%, 90% 15%, 100% 5%, 90% 45%, 100% 60%, 88% 75%, 93% 79%, 100% 100%, 0% 100%, 0 0%);">
            <p class="">{{ $activeMatch->teamA->name }}</p>
        </div>
    </div>

</x-roster>
