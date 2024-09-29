// Import the worker for WebSocket connection
const worker = new Worker('/js/obsWorker.js');  // Make sure the path matches your obsWorker.js location

// Reconnection logic on page load
worker.onmessage = function(event) {
    const { status, msg } = event.data;
    if (status) {
        console.log('WebSocket Status:', status);
        if (status === 'connected') {
            // Save connection state to sessionStorage
            sessionStorage.setItem('obsConnected', 'true');
        } else if (status === 'disconnected') {
            sessionStorage.removeItem('obsConnected');
        }
    }

    if (msg) {
        console.log('Message from OBS:', msg);
    }
};

// Function to attempt reconnection
function reconnectOBS() {
    const obsConnected = sessionStorage.getItem('obsConnected');
    if (obsConnected) {
        const host = sessionStorage.getItem('host');
        const port = sessionStorage.getItem('port');
        const password = sessionStorage.getItem('password');

        if (host && port && password) {
            console.log('Reconnecting to OBS WebSocket...');
            worker.postMessage({
                command: 'connect',
                host: host,
                port: port,
                password: password
            });
        }
    }
}

// Trigger reconnection logic when the page is loaded
document.addEventListener('DOMContentLoaded', reconnectOBS);
