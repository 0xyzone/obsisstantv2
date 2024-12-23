<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<div class="w-[1920px] h-[1080px] bg-red-500 border relative">
    {{ $slot }}
</div>
