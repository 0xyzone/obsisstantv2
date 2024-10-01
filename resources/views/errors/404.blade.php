<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Gayab!</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="max-w-screen max-h-screen h-screen">
    <!-- component -->
    <div
        class="lg:px-24 lg:py-24 md:py-20 md:px-44 px-4 py-24 items-center flex justify-center flex-col-reverse lg:flex-row md:gap-28 gap-16 bg-stone-900 max-h-screen h-full">
        <div class="xl:pt-24 w-full xl:w-1/2 relative pb-12 lg:pb-0">
            <div class="relative">
                <div class="absolute">
                    <div class="">
                        <h1 class="my-2 text-gray-200 font-bold text-2xl">
                            Looks like you've found the
                            doorway to the great nothing
                        </h1>
                        <p class="my-2 text-gray-300">Sorry about that! Please visit our hompage to get where you need
                            to go.</p>
                        <button
                            class="sm:w-full lg:w-auto my-2 border border-amber-700 rounded-md py-4 px-8 text-center bg-amber-200 text-amber-800 hover:bg-amber-500 smooth focus:outline-none focus:ring-2 focus:ring-amber-700 focus:ring-opacity-50"
                            onclick="location.href='{{ route('home') }}'">Take me there!</button>
                    </div>
                </div>
                <div>
                    <img src="{{ asset('img/404.png') }}" />
                </div>
            </div>
        </div>
        <div>
            <img src="{{ asset('img/Group.png') }}" class="max-w-sm" />
        </div>
    </div>
</body>

</html>
