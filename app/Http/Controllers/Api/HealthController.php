<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HealthController extends Controller
{
    /**
     * Vérifie la santé de l'API et la connexion à la base de données.
     *
     * @OA\Get(
     *     path="/api/health",
     *     summary="Health check endpoint",
     *     description="Vérifie la connexion à la base de données",
     *     tags={"Health"},
     *     @OA\Response(
     *         response=200,
     *         description="Service opérationnel",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ok"),
     *             @OA\Property(property="database", type="string", example="connected"),
     *             @OA\Property(property="timestamp", type="string", example="2024-01-01T00:00:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=503,
     *         description="Service indisponible",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="database", type="string", example="disconnected"),
     *             @OA\Property(property="message", type="string", example="Database connection failed"),
     *             @OA\Property(property="timestamp", type="string", example="2024-01-01T00:00:00.000000Z")
     *         )
     *     )
     * )
     */
    public function check(): JsonResponse
    {
        try {
            // Test de connexion à la base de données
            DB::connection()->getPdo();
            
            return response()->json([
                'status' => 'ok',
                'database' => 'connected',
                'timestamp' => now()->toIso8601String(),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Health check failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'database' => 'disconnected',
                'message' => 'Database connection failed',
                'timestamp' => now()->toIso8601String(),
            ], 503);
        }
    }
}

