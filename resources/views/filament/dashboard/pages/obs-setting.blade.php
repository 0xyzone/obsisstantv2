<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}
        <x-filament-panels::form.actions :actions="$this->getFormActions()" />
    </x-filament-panels::form>

    <!-- Connect to OBS button -->
    <div class="mt-6">
        <button id="connectBtn" class="filament-button filament-button-size-md">
            Connect to OBS
        </button>
    </div>

    <!-- JavaScript for handling the connect button click -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch form input elements using their IDs
            const hostInput = document.getElementById('data.host');
            const portInput = document.getElementById('data.port');
            const passwordInput = document.getElementById('data.password');
            let obsWorker = null; // Declare the worker here

            document.querySelector('button[wire\\:click="mountAction(\'Disconnect\')"]').addEventListener('click', function(event) {
                event.preventDefault();
                console.log('Disconnect button clicked');
                // Set the manual disconnect flag
                setManualDisconnect(true);

                worker.postMessage({
                    command: 'disconnect'
                });
            });

            // Add event listener for the connect button
            document.querySelector('button[wire\\:click="mountAction(\'Connect\')"]').addEventListener('click', function(event) {
                event.preventDefault();

                // Fetch values from the input fields
                const host = hostInput ? hostInput.value : '';
                const port = portInput ? portInput.value : '';
                const password = passwordInput ? passwordInput.value : '';

                // Ensure all fields have values
                if (!host || !port || !password) {
                    alert('Please fill in all fields before connecting to OBS.');
                    return;
                }

                sessionStorage.setItem('host', host);
                sessionStorage.setItem('port', port);
                sessionStorage.setItem('password', password);
                sessionStorage.setItem('obsConnected', 'true');

                // Create a new WebSocket connection via obsWorker.js
                obsWorker = new Worker('{{ asset('js/obsWorker.js') }}');

                obsWorker.postMessage({
                    command: 'connect'
                    , host: host
                    , port: port
                    , password: password
                });

                // Handle worker responses
                obsWorker.onmessage = function(e) {
                    const response = e.data;
                    if (response.status === 'connected') {
                        console.log('Successfully connected to OBS.');
                    } else if (response.status === 'disconnected') {
                        clearSessionStorage();
                    } else if (response.status === 'error') {
                        alert('Error: ' + response.msg);
                    }
                };
            });
        });

        function clearSessionStorage() {
            sessionStorage.removeItem("obsConnected");
            sessionStorage.removeItem("host");
            sessionStorage.removeItem("port");
            sessionStorage.removeItem("password");
            console.log("Session storage cleared!");
        }

    </script>
</x-filament-panels::page>
