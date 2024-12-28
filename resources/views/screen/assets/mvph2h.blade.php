<x-screen>
    <style>
        /* div {
            border: 1px solid cyan;
        } */
    </style>
    <div class="mt-10 w-max border-l-[18px] shadow-lg" style="border-color: {{ $tournament->primary_color ?? '#ccc' }};">
        <div class="flex">
            <p class="text-xl font-bold bg-gray-100 pr-2 py-1 w-max pl-4">{{ $tournament->name }}</p>
            <p class="text-xl font-bold bg-black text-white px-2 py-1 w-max">{{ $activeMatch->title }}</p>
        </div>
        <h1 class="text-4xl font-bold bg-gray-200 px-4 py-2 w-full">Head to Head Comparision</h1>
    </div>
    <div class="w-full h-full flex justify-center items-center absolute top-0">
        
    <div class="w-full h-full flex justify-center items-center absolute top-0 px-10">
        <div class="flex w-full justify-center">
            <div class="w-6/12 relative">
                <img src="{{ asset('storage/' . $matchMvp->hero->image) }}" alt="" class="w-full h-full object-cover">
                <p class="px-2 py-2 absolute bottom-0 font-bold text-2xl w-full h-52 flex items-end text-white justify-center" style="background-image: linear-gradient(to top, rgb(21, 144, 21), transparent)">{{ $matchMvp->hero->name }}</p>
            </div>
            <div class="w-full overflow-hidden">
                <p class="text-5xl text-center py-2 font-bold text-white" style="background-color: rgb(21, 144, 21);">{{ $matchMvp->player->name }}</p>
                <div class="h-full" style="background-image: linear-gradient(to bottom, rgb(21, 144, 21), transparent)">
                    <div class="grid grid-cols-2 gap-2 px-6 pt-16 text-white">
                        <div class="flex flex-col justify-center">
                            <p class="font-bold">K/D/A</p>
                            <p class="text-4xl font-black">{{ $matchMvp->kills }}/{{ $matchMvp->deaths }}/{{ $matchMvp->assists }}</p>
                        </div>
                        <div class="flex text-5xl items-center">
                            <x-fas-dollar-sign class="w-10 h-10" />
                            <p class="font-black">{{ $matchMvp->net_worth }}</p>
                        </div>
                        <div>
                            <p>Hero Damage:</p>
                            <p class="text-4xl font-black">{{ $matchMvp->hero_damage }}</p>
                        </div>
                        <div>
                            <p>Turret Damage:</p>
                            <p class="text-4xl font-black">{{ $matchMvp->turret_damage }}</p>
                        </div>
                        <div>
                            <p>Damage Taken:</p>
                            <p class="text-4xl font-black">{{ $matchMvp->damage_taken }}</p>
                        </div>
                        <div>
                            <p>Fight Participation:</p>
                            <p class="text-4xl font-black">{{ $matchMvp->fight_participation }}%</p>
                        </div>
                        <div class="col-span-2">
                            <img src="{{ asset('img/MVP.webp') }}" alt="" class="w-5/12 mx-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex w-full justify-center">
            <div class="w-full overflow-hidden">
                <p class="text-5xl text-center py-2 font-bold text-white" style="background-color: rgb(198, 13, 13);">{{ $looserMvp->player->name }}</p>
                <div class="h-full" style="background-image: linear-gradient(to bottom, rgb(198, 13, 13), transparent)">
                    <div class="grid grid-cols-2 gap-2 px-6 pt-16 text-white">
                        <div class="flex flex-col justify-center">
                            <p class="font-bold">K/D/A</p>
                            <p class="text-4xl font-black">{{ $looserMvp->kills }}/{{ $looserMvp->deaths }}/{{ $looserMvp->assists }}</p>
                        </div>
                        <div class="flex text-5xl items-center">
                            <x-fas-dollar-sign class="w-10 h-10" />
                            <p class="font-black">{{ $looserMvp->net_worth }}</p>
                        </div>
                        <div>
                            <p>Hero Damage:</p>
                            <p class="text-4xl font-black">{{ $looserMvp->hero_damage }}</p>
                        </div>
                        <div>
                            <p>Turret Damage:</p>
                            <p class="text-4xl font-black">{{ $looserMvp->turret_damage }}</p>
                        </div>
                        <div>
                            <p>Damage Taken:</p>
                            <p class="text-4xl font-black">{{ $looserMvp->damage_taken }}</p>
                        </div>
                        <div>
                            <p>Fight Participation:</p>
                            <p class="text-4xl font-black">{{ $looserMvp->fight_participation }}%</p>
                        </div>
                        <div class="col-span-2">
                            <img src="{{ asset('img/MVP.webp') }}" alt="" class="w-5/12 mx-auto">
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-6/12 relative">
                <img src="{{ asset('storage/' . $looserMvp->hero->image) }}" alt="" class="w-full h-full object-cover">
                <p class="px-2 py-2 absolute bottom-0 font-bold text-2xl w-full h-52 flex items-end text-white justify-center" style="background-image: linear-gradient(to top, rgb(198, 13, 13), transparent)">{{ $looserMvp->hero->name }}</p>
            </div>
        </div>
    </div>
    </div>
</x-screen>
