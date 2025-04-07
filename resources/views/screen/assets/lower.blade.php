<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Match Up</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="">
    <div class="flex gap-0 relative h-32 overflow-hidden">
        <div class="skew-x-[15deg] bg-black w-48 object-scale-down px-8 z-10 py-8">
            <img src="{{ asset('storage/' . $data->tournament->logo) }}" alt="" class="-skew-x-[15deg]">
        </div>
        <div class="h-full">
            <div class="w-max {{ $textColor }} px-3 pl-5 py-2 skew-x-[15deg] -translate-x-4 font-bold" style="background: {{ $tournamentPrimaryColor }};">
                <p class="-skew-x-[15deg]">{{ $data->title }} <span class="text-xs font-extrabold">{{ $data->schedule > now() ? ' (Up coming)' : '' }}</span></p>
            </div>
            <div class="flex gap-2 text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 pl-5 py-2 pr-4 text-white skew-x-[15deg] -translate-x-2">
                <p class="-skew-x-[15deg]">
                    {{ $data->teamA->name }}
                </p>
                <p class="-skew-x-[15deg]">vs</p>
                <p class="-skew-x-[15deg]">
                    {{ $data->teamB->name }}
                </p>
            </div>
            <div class="h-full bg-black text-white -skew-x-[15deg] -translate-x-5 w-max text-xl font-bold pl-10 pr-10 pt-1">
                <p class="skew-x-[15deg]">{{ date('h:i A', strtotime($data->schedule)) }}</p>
            </div>
        </div>
    </div>
</body>
</html>
