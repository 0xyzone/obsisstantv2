// obsWorker.js
let obs = null;
let isConnected = false;
console.log('Worker Loaded');
self.onmessage = function(e) {
    const { command, host, port, password } = e.data;
    console.log('self on message executed');

    if (command === 'connect') {
        const protocol = self.location.protocol === 'https:' ? 'wss://' : 'ws://';
        const webSocketUrl = `${protocol}${host}:${port}`;

        // Ensure the OBSWebSocket class is available in the worker scope
        if (!obs) {
            obs = new OBSWebSocket();
        }

        obs.connect(webSocketUrl, password)
            .then(() => {
                isConnected = true;
                console.log('Connected to OBS WebSocket.');
                postMessage({ status: 'connected' });
            })
            .catch(err => {
                console.error('Failed to connect to OBS:', err);
                postMessage({ status: 'error', msg: err.message });
            });

        obs.on('message', (msg) => {
            postMessage({ msg });
        });

        obs.on('Disconnect', () => {
            console.log('Disconnected from OBS WebSocket.');
            isConnected = false;
            postMessage({ status: 'disconnected' });
        });
    }
};
