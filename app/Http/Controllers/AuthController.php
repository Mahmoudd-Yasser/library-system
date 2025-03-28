<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\students;


class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="تسجيل الدخول للطالب",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "student_id"},
     *             @OA\Property(property="name", type="string", example=" يوسف طارق احمد"),
     *             @OA\Property(property="student_id", type="numeric", example=15901)
     *         )
     *     ),
     *     @OA\Response(response=200, description="تم تسجيل الدخول بنجاح."),
     *     @OA\Response(response=401, description="بيانات الطالب غير صحيحة.")
     * )
     */
    public function login(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'student_id' => 'required|numeric'
    ]);

    $student = students::where('name', $request->name)
                       ->where('student_id', $request->student_id)
                       ->first();

    if (!$student) {
        return response()->json([
            'message' => 'بيانات الطالب غير صحيحة.'
        ], 401);
    }

    // إنشاء توكن جديد للطالب
    $token = $student->createToken('StudentAuthToken')->plainTextToken;

    return response()->json([
        'message' => 'تم تسجيل الدخول بنجاح!',
        'student' => $student,
        'token' => $token
    ]);
}


  /**
 * @OA\Post(
 *     path="/api/logout",
 *     summary="تسجيل خروج الطالب",
 *     tags={"Authentication"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(response=200, description="تم تسجيل الخروج بنجاح."),
 *     @OA\Response(response=401, description="غير مصرح لك.")
 * )
 */
    public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'تم تسجيل الخروج بنجاح!'
    ]);
}

}
