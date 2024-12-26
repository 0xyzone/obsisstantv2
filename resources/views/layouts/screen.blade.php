<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<div class="w-[1920px] h-[1080px] relative bg-red-300">
    {{ $slot }}
</div>
