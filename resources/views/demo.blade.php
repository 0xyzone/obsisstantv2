{{-- demo.blade.php --}}
@php
$userId = auth()->user()->id ?? "";
$setting = App\Models\ObsSetting::where('user_id', $userId)->first();
$password = $setting ? Illuminate\Support\Facades\Crypt::decryptString($setting->password) : "";
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
    @if ($setting)
    <a href="#" id="connect-link">Connect</a>
    <a href="#" id="disconnect-link">Disconnect</a>
    <script src="{{ asset('js/obsWorker.js') }}"></script>
    <script>
        const worker = new Worker("{{ asset('js/obsWorker.js') }}");
        console.log('Worker initialized');
        // This is how you listen for messages from the worker
        worker.onmessage = function(event) {
            const {
                status
                , msg
            } = event.data; // Get the data from the event
            if (status) {
                console.log('WebSocket Status:', status);
                if (status === 'connected') {
                    // Save connection details to sessionStorage
                    sessionStorage.setItem('obsConnected', 'true');
                    sessionStorage.setItem('host', '{{ $setting->host }}');
                    sessionStorage.setItem('port', '{{ $setting->port }}');
                    sessionStorage.setItem('password', '{{ $password }}');
                }
                if (status === 'disconnected') {
                    // Remove connection details when disconnected
                    sessionStorage.removeItem('obsConnected');
                    sessionStorage.removeItem('host');
                    sessionStorage.removeItem('port');
                    sessionStorage.removeItem('password');
                }
            }
            if (msg) {
                console.log('Message from OBS:', msg);
            }
        };

        $(document).ready(function() {
            const obsConnected = sessionStorage.getItem('obsConnected');
            if (obsConnected) {
                console.log('Reconnecting to OBS WebSocket...');
                worker.postMessage({
                    command: 'connect'
                    , host: sessionStorage.getItem('host')
                    , port: sessionStorage.getItem('port')
                    , password: sessionStorage.getItem('password')
                });
            }
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
            $('#disconnect-link').on('click', function(event) {
                event.preventDefault();
                console.log('Disconnect button clicked');
                worker.postMessage({
                    command: 'disconnect'
                });
                // Clear the session storage
                sessionStorage.removeItem('obsConnected');
                sessionStorage.removeItem('host');
                sessionStorage.removeItem('port');
                sessionStorage.removeItem('password');
            });
        });

    </script>
    @else
    Obs setting not set!
    @endif
</body>
</html>
