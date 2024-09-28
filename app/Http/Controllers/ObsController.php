<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObsSetting as Obs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Ratchet\Client\connect as connect;
use Illuminate\Support\Facades\Session;
use App\Filament\Dashboard\Pages\ObsSetting;

class ObsController extends Controller
{
    // protected $userId = auth()->user()->id;
    // protected $setting = Obs::where("user_id", $userId)->first();
    // protected $host = $setting->host;
    // protected $webSocketUrl = 'ws://' . $this->host . ':4455'; // WebSocket URL for OBS
    // protected $password = 'VLXfvGG8Nls1QPlX'; // OBS WebSocket password

    public function connectToObs()
    {
        $userId = auth()->user()->id;
        $setting = Obs::where('user_id', $userId)->first();
        // Check if the setting exists
        if (!$setting) {
            Log::error("OBS setting not found for user: {$userId}");
            return response()->json(['error' => 'OBS settings not found'], 404);
        }

        $host = $setting->host;
        $password = Crypt::decryptString($setting->password);
        $scheme = request()->getScheme();
        if ($scheme === 'https') {
            $webSocketUrl = 'wss://' . $host . ':4455'; // Secure WebSocket for HTTPS
        } else {
            $webSocketUrl = 'ws://' . $host . ':4455'; // Regular WebSocket for HTTP
        }
        \Ratchet\Client\connect($webSocketUrl)->then(function ($conn) use ($password) {
            Log::info("Connected to OBS WebSocket.");

            $conn->on('message', function ($msg) use ($conn, $password) {
                Log::info("Received: {$msg}");
                $response = json_decode($msg, true);

                // Check if the identification was successful
                if (isset($response['op']) && $response['op'] === 0) { // Authentication challenge
                    $this->handleAuthChallenge($response, $conn, $password);
                } elseif (isset($response['op']) && $response['op'] === 2) { // 3 indicates the identified state
                    Log::info("Successfully identified with OBS WebSocket.");
                    Session::put('obs_connected', true);
                    $this->keepConnectionAlive($conn);
                } else {
                    Log::error("Unexpected response from OBS: " . json_encode($response));
                }
            });

            $conn->on('close', function () {
                Log::info("Connection to OBS WebSocket closed.");
            });

        }, function ($e) {
            Log::error("Could not connect: {$e->getMessage()}");
        });
    }

    public function checkConnection()
    {
        // Check if the OBS connection is successful
        if (Session::get('obs_connected')) {
            // Clear the session variable after checking
            Session::forget('obs_connected');
            return redirect()->route('demo');
        }

        // If not connected yet, return a message
        return response()->json(['status' => 'Still connecting to OBS...']);
    }

    private function handleAuthChallenge($response, $conn, $password)
    {
        $challenge = $response['d']['authentication']['challenge'];
        $salt = $response['d']['authentication']['salt'];

        $authResponse = $this->generateAuthResponse($challenge, $salt, $password);
        $identifyMessage = [
            'op' => 1, // Identify operation code
            'd' => [
                'rpcVersion' => 1,
                'authentication' => $authResponse,
                'eventSubscriptions' => 0,
            ],
        ];

        $conn->send(json_encode($identifyMessage));
        Log::info("Sent Identify message with authentication.");
    }

    // Function to generate the authentication response
    private function generateAuthResponse($challenge, $salt, $password)
    {
        // Decode the challenge
        $decodedChallenge = base64_decode($challenge);
        Log::info("Decoded Challenge: " . base64_encode($decodedChallenge));

        // Create a salted hash of the password
        $passwordSaltedHash = hash('sha256', $password . $salt, true);
        Log::info("Password Salted Hash: " . base64_encode($passwordSaltedHash));

        // Create the final hash by combining the salted hash with the decoded challenge
        $authResponse = hash('sha256', base64_encode($passwordSaltedHash) . base64_encode($decodedChallenge), true);
        Log::info("Generated authentication response (before encoding): " . base64_encode($authResponse));

        // Base64 encode the final hash
        $encodedResponse = base64_encode($authResponse);
        Log::info("Base64 encoded authentication response: " . $encodedResponse);

        return $encodedResponse;
    }

    // Function to keep the connection alive
    private function keepConnectionAlive($conn)
    {
        $loop = \React\EventLoop\Factory::create();

        // Schedule a ping every 30 seconds
        $loop->addPeriodicTimer(30, function () use ($conn) {
            $pingMessage = ['op' => 9]; // Op 9 for ping
            $conn->send(json_encode($pingMessage));
            Log::info("Sent keep-alive ping.");
        });

        // Listen for pong response
        $conn->on('message', function ($msg) {
            $response = json_decode($msg, true);
            if (isset($response['op']) && $response['op'] === 10) { // Op 10 is pong
                Log::info("Received pong response, connection is alive.");
            }
        });

        $loop->run();
    }
}
