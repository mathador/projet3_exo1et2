<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="Notes & Tags API",
 *     version="1.0.0",
 *     description="API REST pour la gestion de notes et de tags",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Serveur API"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Authentification par token Sanctum"
 * )
 */
class SwaggerController extends Controller
{
    // Ce contrôleur est utilisé uniquement pour les annotations Swagger globales
}

