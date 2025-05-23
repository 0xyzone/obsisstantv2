<x-screen>
    <div class="flex flex-col gap-2 w-[95%] mx-auto justify-center items-center h-full">
        {{-- team info --}}
        <div class="flex items-end w-full text-3xl font-bold">
            <div>
                <img src="{{ $activeMatch->teamA->logo ? asset('/storage/' . $activeMatch->teamA->logo) : '' }}" alt="team a logo" class="max-w-32 {{ (isset($activeMatch->winner) && $activeMatch->winner->id == $activeMatch->team_a) ? 'border-lime-500' : 'border-red-500' }} border-[6px] aspect-square object-cover">
            </div>
            <div class="{{ (isset($activeMatch->winner) && $activeMatch->winner->id == $activeMatch->team_a) ? 'bg-gradient-to-r from-lime-500 to-lime-950' : 'bg-gradient-to-r from-red-500 to-red-950' }} h-max py-6 pl-6 w-full">
                <p>{{ $activeMatch->teamA->name }}</p>
            </div>
            <div class="bg-transparent flex justify-center items-end gap-2 text-6xl font-bold text-white self-center h-full">
                <p class="{{ (isset($activeMatch->winner) && $activeMatch->winner->id == $activeMatch->team_a) ? 'text-lime-500 bg-lime-950' : 'text-red-500 bg-red-950' }} px-6 py-3 rounded-r-lg shadow-lg">{{ $activeMatch->team_a_mp }}</p>
                {{-- <p class="-translate-y-2">:</p> --}}
                <p class="{{ (isset($activeMatch->winner) && $activeMatch->winner->id == $activeMatch->team_b) ? 'text-lime-500 bg-lime-950' : 'text-red-500 bg-red-950' }} px-6 py-3 rounded-l-lg shadow-lg">{{ $activeMatch->team_b_mp }}</p>
            </div>
            <div class="{{ (isset($activeMatch->winner) && $activeMatch->winner->id == $activeMatch->team_b) ? 'bg-gradient-to-l from-lime-500 to-lime-950' : 'bg-gradient-to-l from-red-500 to-red-950' }} w-full h-max py-6 pr-6 text-right">
                <p>{{ $activeMatch->teamB->name }}</p>
            </div>
            <div>
                <img src="{{ $activeMatch->teamB->logo ? asset('/storage/' . $activeMatch->teamB->logo) : '' }}" alt="team a logo" class="max-w-32 {{ (isset($activeMatch->winner) && $activeMatch->winner->id == $activeMatch->team_b) ? 'border-lime-500' : 'border-red-500' }} border-[6px] aspect-square object-cover">
            </div>
        </div>
        {{-- team info end --}}

        {{-- stats --}}
        <div class="flex gap-2">
            <table class="table table-fixed w-6/12 space-y-2 border-separate border-spacing-x-0 border-spacing-y-2 text-white">
                <tbody>
                    @foreach ($activeMatch->statsForTeamA as $stat)
                    <tr class="bg-gradient-to-l {{ (isset($activeMatch->winner) && $activeMatch->winner->id == $activeMatch->team_a) ? 'to-lime-700 from-lime-950' : 'to-rose-700 from-red-950' }} text-xl font-bold">
                        <td class="rounded-l-lg">
                            <img src="{{ $stat->hero ? ($stat->hero->image ? asset('/storage/' . $stat->hero->image) : '') : '' }}" alt="" class="h-32 aspect-[12/16] object-cover rounded-l-lg">
                        </td>
                        <td class="py-4 pl-4 font-bold text-2xl space-y-2" colspan="3">
                            <div>
                                <div class="flex gap-2 items-center ">
                                    @if ($stat->is_mvp == true)
                                    <p class="text-xs flex flex-col items-center">
                                        <x-fas-crown class="w-6 h-6 text-amber-500" /> (MVP)</p>
                                    @endif
                                    <p class="w-8/12 truncate">{{ $stat->player->nickname }}</p>
                                </div>
                            </div>
                            <p class="text-lg {{ (isset($activeMatch->winner) && $activeMatch->winner->id == $activeMatch->team_a) ? 'text-lime-400' : 'text-rose-300' }}">{{ $stat->hero ? $stat->hero->name : 'No hero selected' }}</p>
                        </td>
                        <td class="px-4 space-y-4">
                            <div class="flex gap-2 items-center">
                                <x-ri-sword-fill class="w-6 h-6" /> {{ $stat->kills }}</div>
                            <div class="flex flex-col">
                                <p class="text-xs font-normal">Hero <br> Damage</p>
                                <p>{{ $stat->hero_damage }}</p>
                            </div>
                        </td>
                        <td class="px-4 space-y-4">
                            <div class="flex gap-2 items-center">
                                <x-fas-skull class="w-6 h-6" /> {{ $stat->deaths }}</div>
                            <div class="flex flex-col">
                                <p class="text-xs font-normal">Turret <br> Damage</p>
                                <p>{{ $stat->turret_damage }}</p>
                            </div>
                        </td>
                        <td class="px-4 space-y-4">
                            <div class="flex gap-2 items-center">
                                <x-phosphor-hand-fist-fill class="w-6 h-6" />{{ $stat->assists }}</div>
                            <div class="flex flex-col">
                                <p class="text-xs font-normal">Damage <br> Taken</p>
                                <p>{{ $stat->damage_taken }}</p>
                            </div>
                        </td>
                        <td class="px-4 space-y-4">
                            <div class="flex gap-2 items-center">
                                <x-fas-dollar-sign class="w-6 h-6" />{{ $stat->net_worth }}</div>
                            <div class="flex flex-col">
                                <p class="text-xs font-normal">Fight <br> Participation</p>
                                <p>{{ $stat->fight_participation }}%</p>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="table table-fixed w-6/12 space-y-2 border-separate border-spacing-x-0 border-spacing-y-2 text-white">
                <tbody>
                    @foreach ($activeMatch->statsForTeamB as $stat)
                    <tr class="bg-gradient-to-r {{ (isset($activeMatch->winner) && $activeMatch->winner->id == $activeMatch->team_b) ? 'to-lime-700 from-lime-950' : 'to-rose-700 from-red-950' }} text-xl font-bold">
                        <td>
                            <img src="{{ $stat->hero ? ($stat->hero->image ? asset('/storage/' . $stat->hero->image) : '') : '' }}" alt="" class="h-32 aspect-[12/16] object-cover">
                        </td>
                        <td class="py-4 pl-4 font-bold text-2xl space-y-2" colspan="3">
                            <div>
                                <p class="flex gap-2 items-center">
                                    @if ($stat->is_mvp == true)
                                    <span class="text-xs flex flex-col items-center">
                                        <x-fas-crown class="w-6 h-6 text-amber-500" /> (MVP)</span>
                                    @endif
                                    {{ $stat->player->nickname }}
                                </p>
                            </div>
                            <p class="text-lg {{ (isset($activeMatch->winner) && $activeMatch->winner->id == $activeMatch->team_b) ? 'text-lime-400' : 'text-red-400' }}">{{ $stat->hero ? $stat->hero->name : 'No hero selected' }}</p>
                        </td>
                        <td class="px-4 space-y-4">
                            <div class="flex gap-2 items-center">
                                <x-ri-sword-fill class="w-6 h-6" /> {{ $stat->kills }}</div>
                            <div class="flex flex-col">
                                <p class="text-xs font-normal">Hero <br> Damage</p>
                                <p>{{ $stat->hero_damage }}</p>
                            </div>
                        </td>
                        <td class="px-4 space-y-4">
                            <div class="flex gap-2 items-center">
                                <x-fas-skull class="w-6 h-6" /> {{ $stat->deaths }}</div>
                            <div class="flex flex-col">
                                <p class="text-xs font-normal">Turret <br> Damage</p>
                                <p>{{ $stat->turret_damage }}</p>
                            </div>
                        </td>
                        <td class="px-4 space-y-4">
                            <div class="flex gap-2 items-center">
                                <x-phosphor-hand-fist-fill class="w-6 h-6" />{{ $stat->assists }}</div>
                            <div class="flex flex-col">
                                <p class="text-xs font-normal">Damage <br> Taken</p>
                                <p>{{ $stat->damage_taken }}</p>
                            </div>
                        </td>
                        <td class="px-4 space-y-4 rounded-r-lg">
                            <div class="flex gap-2 items-center">
                                <x-fas-dollar-sign class="w-6 h-6" />{{ $stat->net_worth }}</div>
                            <div class="flex flex-col">
                                <p class="text-xs font-normal">Fight <br> Participation</p>
                                <p>{{ $stat->fight_participation }}%</p>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- stats end --}}
    </div>
</x-screen>
