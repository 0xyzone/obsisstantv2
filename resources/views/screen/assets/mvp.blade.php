<x-screen>
    <div class="mt-10 w-max border-l-[18px] shadow-lg" style="border-color: {{ $tournament->primary_color ?? '#ccc' }};">
        <div class="flex">
            <p class="text-xl font-bold bg-gray-100 pr-2 py-1 w-max pl-4">{{ $tournament->name }}</p>
            <p class="text-xl font-bold bg-black text-white px-2 py-1 w-max">{{ $activeMatch->title }}</p>
        </div>
        <h1 class="text-4xl font-bold bg-gray-200 px-4 py-2 w-full">Match Mvp</h1>
    </div>
    <div class="w-full h-full flex justify-center items-center">
        <div>
            <img src="{{ asset('storage/' . $matchMvp->hero->image) }}" alt="">
            <img src="{{ asset('storage/' . $matchMvp->team->logo) }}" alt="">
            <p>{{ $matchMvp->player->name }}</p>
            <p>{{ $matchMvp->kills }}</p>
            <p>{{ $matchMvp->deaths }}</p>
            <p>{{ $matchMvp->assists }}</p>
            <p>{{ $matchMvp->net_worth }}</p>
            <p>{{ $matchMvp->hero_damage }}</p>
            <p>{{ $matchMvp->turret_damage }}</p>
            <p>{{ $matchMvp->damage_taken }}</p>
            <p>{{ $matchMvp->fight_participation }}</p>
            <p>{{ $matchMvp->team->name }}</p>
            <p>{{ $matchMvp->hero->name }}</p>
        </div>
    </div>
</x-screen>
