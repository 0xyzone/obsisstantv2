<x-screen>
    <div class="absolute top-4 right-10">
        <img src="{{ $tournament->logo ? asset('/storage/' . $tournament->logo) : '' }}" alt=""  class="max-w-48">
    </div>
    <div class="relative w-full h-full">
        <div class="w-max pt-8 pb-5 pl-20 pr-10 bg-gray-800 text-gray-200 -skew-x-[30deg] absolute top-24 text-4xl font-bold -left-10 shadow-xl">
            <p class="skew-x-[30deg]">
                {{ $activeMatch->title ?? 'No match has been activated' }}
            </p>
        </div>
        <div class="w-max py-5 pl-20 pr-10 bg-gray-200 -skew-x-[30deg] absolute top-10 text-4xl font-bold -left-10 shadow-xl">
            <p class="skew-x-[30deg]">
                {{ $tournament->name ?? 'No tournament has been activated' }}
            </p>
        </div>
        @if ($activeMatch !== null) 
            <div class="w-full h-full flex absolute top-0 items-center text-2xl font-bold">
                <div class="flex w-full items-center gap-8 mx-auto justify-around px-16">
                    <div class="flex flex-col justify-center items-center w-4/12">
                        <div><img src="{{ $activeMatch->teamA->logo ? asset('/storage/' . $activeMatch->teamA->logo) : "" }}" alt="" class="w-64 aspect-square object-scale-down shadow-xl bg-white rounded-lg p-6"></div>
                        <div class="w-full text-center py-2 px-8 bg-gray-200 -skew-x-[30deg] text-6xl font-bold shadow-xl -translate-y-5">
                            <p class="skew-x-[30deg]">{{ $activeMatch->teamA->name }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-9xl text-white">vs</p>
                    </div>
                    <div class="flex flex-col justify-center items-center w-4/12">
                        <div><img src="{{ $activeMatch->teamB->logo ? asset('/storage/' . $activeMatch->teamB->logo) : "" }}" alt="" class="w-64 aspect-square object-scale-down shadow-xl bg-white rounded-lg p-6"></div>
                        <div class="w-full text-center py-2 px-8 bg-gray-200 skew-x-[30deg] text-6xl font-bold shadow-xl -translate-y-5">
                            <p class="-skew-x-[30deg]">{{ $activeMatch->teamB->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-screen>
