<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    {{-- <form id="command-form" method="POST">
        @csrf
        <button type="submit">Run Command</button>
    </form> --}}
    <a href="#" id="connect-link">Connect</a>
    <script src="https://cdn.jsdelivr.net/npm/obs-websocket-js"></script>
    {{-- <script>
        $(document).ready(function() {
            $('#connect-link').on('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                $.ajax({
                    url: '{{ route('connectOBS') }}', // Adjust this URL as necessary
                    method: 'GET',
                    success: function(response) {
                        console.log(response.status);
                        // Determine the correct WebSocket protocol (ws or wss)
                        const protocol = window.location.protocol === 'https:' ? 'wss://' : 'ws://';
                        const host = '{{ $setting->host }}'; // Assuming $setting->host is passed to the view
                        const port = '{{ $setting->port }}';
                        const webSocketUrl = `${protocol}${host}:${port}`;
                        // Start the WebSocket connection
                        const socket = new WebSocket(webSocketUrl);

                        socket.onopen = function() {
                            console.log('WebSocket connection opened.');
                            // Redirect to demo route after connection is established
                            window.location.href = "{{ route('demo') }}"; // Redirect to demo
                        };

                        socket.onmessage = function(event) {
                            console.log('Message from server:', event.data);
                        };

                        socket.onclose = function() {
                            console.log('WebSocket connection closed.');
                        };
                    },
                    error: function(xhr, status, error) {
                        console.error('Error connecting to OBS:', error);
                    }
                });
            });
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            $('#connect-link').on('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                $.ajax({
                    url: '{{ route('connectOBS') }}', // Adjust this URL as necessary
                    method: 'GET',
                    success: function(response) {
                        console.log(response.status);
                        // Determine the correct WebSocket protocol (ws or wss)
                        const protocol = window.location.protocol === 'https:' ? 'wss://' : 'ws://';
                        const host = '{{ $setting->host }}'; // Ensure this is populated correctly
                        const port = '{{ $setting->port }}'; // Ensure this is populated correctly
                        const webSocketUrl = `${protocol}${host}:${port}`;
                        
                        // Create an instance of the OBS WebSocket client
                        const obs = new OBSWebSocket();

                        // Connect to OBS
                        obs.connect({ address: webSocketUrl, password: '{{ $setting->password }}' }) // Replace with actual password if needed
                            .then(() => {
                                console.log('Connected to OBS WebSocket.');
                                // Redirect to demo route after connection is established
                                window.location.href = "{{ route('demo') }}"; // Redirect to demo
                            })
                            .catch(err => {
                                console.error('Failed to connect to OBS:', err);
                            });

                        // Event listener for messages
                        obs.on('message', (msg) => {
                            console.log('Message from OBS:', msg);
                        });

                        // Handle disconnect
                        obs.on('Disconnect', () => {
                            console.log('Disconnected from OBS WebSocket.');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error connecting to OBS:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>
