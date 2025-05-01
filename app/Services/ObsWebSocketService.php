<?php
namespace App\Services;

use WebSocket\Client;
use Illuminate\Support\Facades\Log;

class ObsWebSocketService
{
    protected ?Client $client = null;
    protected bool $connected = false;
    
    public function __construct(
        protected string $host = 'localhost',
        protected int $port = 4455,
        protected string $password = ''
    ) {}
    
    public function connect(): bool
    {
        try {
            $this->client = new Client("ws://{$this->host}:{$this->port}");
            $this->connected = true;
            
            if ($this->password) {
                $this->authenticate();
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('OBS connection failed: '.$e->getMessage());
            $this->connected = false;
            return false;
        }
    }
    
    protected function authenticate(): bool
    {
        $authRequired = $this->sendRequest('GetAuthRequired');
        
        if ($authRequired['authRequired'] ?? false) {
            $secret = hash('sha256', $this->password.$authRequired['salt']);
            $authResponse = hash('sha256', $secret.$authRequired['challenge']);
            
            $result = $this->sendRequest('Authenticate', [
                'auth' => $authResponse
            ]);
            
            return !isset($result['error']);
        }
        
        return true;
    }
    
    public function sendRequest(string $type, array $data = []): mixed
    {
        if (!$this->connected && !$this->connect()) {
            throw new \RuntimeException('Not connected to OBS');
        }
        
        $message = array_merge([
            'request-type' => $type,
            'message-id' => uniqid()
        ], $data);
        
        try {
            $this->client->send(json_encode($message));
            return json_decode($this->client->receive(), true);
        } catch (\Exception $e) {
            $this->connected = false;
            throw new \RuntimeException("OBS command failed: ".$e->getMessage());
        }
    }
    
    // Helper methods
    public function startStream(): bool
    {
        return !isset($this->sendRequest('StartStream')['error']);
    }
    
    public function switchScene(string $sceneName): bool
    {
        return !isset($this->sendRequest('SetCurrentScene', [
            'scene-name' => $sceneName
        ])['error']);
    }
}