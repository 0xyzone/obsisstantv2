@php
$userId = auth()->user()->id;
$setting = App\Models\ObsSetting::where('user_id', $userId)->first();
// dd($setting);
$password = Illuminate\Support\Facades\Crypt::decryptString($setting->password);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/obs-websocket-js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    {{-- <form id="command-form" method="POST">
        @csrf
        <button type="submit">Run Command</button>
    </form> --}}
    <a href="#" id="connect-link">Connect</a>
    <a href="#" id="disconnect-link">Disconnect</a>
    <script src="{{ asset('js/obsWorker.js') }}"></script>
    <script>
        const worker = new Worker('obsWorker.js');
        console.log('Worker initialized');
        // This is how you listen for messages from the worker
        worker.onmessage = function(event) {
            const { status, msg} = event.data; // Get the data from the event
            if (status) {
                console.log('WebSocket Status:', status);
                alert(`WebSocket Status: ${status}`);
            }
            if (msg) {
                console.log('Message from OBS:', msg);
            }
        };

        $(document).ready(function() {
            $('#connect-link').on('click', function(event) {
                event.preventDefault();
                console.log('Connect button clicked');
                worker.postMessage({
                    command: 'connect',
                    host: '{{ $setting->host }}', // Pass the host
                    port: '{{ $setting->port }}', // Pass the port
                    password: '{{ $password }}' // Pass the password
                });
            });
        });

    </script>
</body>
</html>
