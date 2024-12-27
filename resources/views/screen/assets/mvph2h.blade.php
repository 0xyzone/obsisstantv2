<x-screen>
    <div class="mt-10 w-max border-l-[18px] shadow-lg" style="border-color: {{ $tournament->primary_color ?? '#ccc' }};">
        <div class="flex">
            <p class="text-xl font-bold bg-gray-100 pr-2 py-1 w-max pl-4">{{ $tournament->name }}</p>
            <p class="text-xl font-bold bg-black text-white px-2 py-1 w-max">{{ $activeMatch->title }}</p>
        </div>
        <h1 class="text-4xl font-bold bg-gray-200 px-4 py-2 w-full">MVP Head to Head</h1>
    </div>
    <div class="w-full h-full flex justify-center items-center">
        something
    </div>
</x-screen>
