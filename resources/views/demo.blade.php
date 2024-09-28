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
    <script>
        $(document).ready(function() {
            $('#connect-link').on('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                $.ajax({
                    url: '{{ route('connectOBS') }}', // Adjust this URL as necessary
                    method: 'GET',
                    success: function(response) {
                        console.log(response.status);
                        // Start the WebSocket connection
                        const socket = new WebSocket('ws://192.168.1.104:4455');

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
    </script>
</body>
</html>
