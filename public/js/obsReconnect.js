// Import the worker for WebSocket connection
console.log('obsReconnect.js loaded');
const worker = new Worker(`${window.location.origin}/js/obsWorker.js`);  // Make sure the path matches your obsWorker.js location

let manualDisconnect = false;
// Reconnection logic on page load
worker.onmessage = function(event) {
    const { status, msg } = event.data;
    if (status) {
        console.log('WebSocket Status:', status);
        if (status === 'connected') {
            // Save connection state to sessionStorage
            sessionStorage.setItem('obsConnected', 'true');
        } else if (status === 'disconnected') {
            // Clear session storage on disconnection, if not manual
            if (!manualDisconnect) {
                console.log('Disconnected from OBS, attempting reconnection...');
                reconnectOBS();
            } else {
                console.log('Disconnected manually, clearing session storage...');
                // Clear the session storage when disconnecting manually
                sessionStorage.removeItem('obsConnected');
                sessionStorage.removeItem('host');
                sessionStorage.removeItem('port');
                sessionStorage.removeItem('password');
                manualDisconnect = false; // Reset flag for future disconnects
            }
        }
    }

    if (msg) {
        console.log('Message from OBS:', msg);
    }
};

// Function to attempt reconnection
// Function to attempt reconnection
function reconnectOBS() {
    const obsConnected = sessionStorage.getItem('obsConnected');
    if (obsConnected) {
        const host = sessionStorage.getItem('host');
        const port = sessionStorage.getItem('port');
        const password = sessionStorage.getItem('password');
        console.log('reconnectOBS function called.');

        if (host && port && password) {
            console.log('Reconnecting to OBS WebSocket...');
            worker.postMessage({
                command: 'connect',
                host: host,
                port: port,
                password: password
            });
        } else {
            console.error('Connection details are missing, cannot reconnect.');
        }
    } else {
        console.log('OBS is not marked as connected in sessionStorage.');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded and parsed, calling reconnectOBS...');
    console.log('SessionStorage values:', {
        host: sessionStorage.getItem('host'),
        port: sessionStorage.getItem('port'),
        password: sessionStorage.getItem('password'),
        obsConnected: sessionStorage.getItem('obsConnected')
    });
    reconnectOBS();
});

// Expose a method to set manual disconnect flag
function setManualDisconnect(flag) {
    manualDisconnect = flag;
}
