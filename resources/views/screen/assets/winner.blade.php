<x-screen>
    @if ($activeMatch->winner)
    <div class="w-full h-full flex justify-center items-center">
        <div class="flex flex-col items-center w-full">
            <img src="{{ $activeMatch->winner->logo ? asset('storage/' . $activeMatch->winner->logo) : '' }}" alt="" class="w-64 aspect-square object-scale-down shadow-xl bg-white rounded-lg p-6">
            <h1 class="w-6/12 text-4xl px-8 py-2 bg-gray-300 -translate-y-5 text-center">{{ $activeMatch->winner->name }}</h1>
            @if (isset($activeMatch->winner) && $activeMatch->winner->id == $activeMatch->team_a)
            <div class="space-y-2 text-center text-2xl w-6/12 text-white">
                @foreach ($activeMatch->statsForTeamA as $stat)
                <p class="w-full py-4 bg-gray-800">{{ $stat->player->nickname }}</p>
                @endforeach
            </div>
            @else
            <div class="space-y-2 text-center text-2xl w-6/12 text-white">
                @foreach ($activeMatch->statsForTeamB as $stat)
                <p class="w-full py-4 bg-gray-800">{{ $stat->player->nickname }}</p>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="w-full h-full flex justify-center items-center text-6xl font-bold">
        <p class="px-6 py-3 rounded-lg bg-gray-400 animate-pulse">No Winner Has Been Selected!</p>
    </div>
    @endif
</x-screen>
