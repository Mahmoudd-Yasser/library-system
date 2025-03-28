<?php

namespace App\Http\Controllers;
/**
 * @OA\Info(
 *     title="University Library API",
 *     version="1.0.0",
 *     description="توثيق الـ API لمكتبة الجامعة",
 *     @OA\Contact(
 *         email="admin@university.com"
 *     )
 * ),
 * @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *         bearerFormat="JWT"
 *     )
 */
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
