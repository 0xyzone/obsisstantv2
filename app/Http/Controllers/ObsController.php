<?php
// app/Http/Controllers/ObsController.php

namespace App\Http\Controllers;

use App\Services\ObsWebSocketService;
use Illuminate\Http\JsonResponse;

class ObsController extends Controller
{
    public function __construct(
        protected ObsWebSocketService $obs
    ) {}
    
    public function startStream(): JsonResponse
    {
        try {
            $success = $this->obs->startStream();
            return response()->json(['success' => $success]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function switchScene(string $scene): JsonResponse
    {
        try {
            $success = $this->obs->switchScene($scene);
            return response()->json(['success' => $success]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}