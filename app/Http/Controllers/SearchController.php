<?php

namespace App\Http\Controllers;

use App\Models\books;
use App\Models\categories;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="عرض قائمة التصنيفات",
     *     description="عرض قائمة بجميع التصنيفات المتاحة",
     *     tags={"Books"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="قائمة التصنيفات",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="الإدارة")
     *             ))
     *         )
     *     ),
     *     @OA\Response(response=401, description="غير مصرح")
     * )
     */
    public function listCategories()
    {
        $categories = categories::all(['id', 'name']);
        
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{name}/books",
     *     summary="عرض الكتب حسب التصنيف",
     *     description="عرض قائمة الكتب في التصنيف المحدد",
     *     tags={"Books"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         description="اسم التصنيف (مثال: الإدارة)",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="قائمة الكتب في التصنيف المحدد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Book"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="غير مصرح"),
     *     @OA\Response(response=404, description="التصنيف غير موجود")
     * )
     */
    public function getBooksByCategory($name)
    {
        $books = books::whereHas('category', function($q) use ($name) {
            $q->where('name', $name);
        })->with(['category', 'authors'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    public function search(Request $request)
    {
        $query = books::with(['category', 'authors']);

        if ($request->has('title')) {
            $searchTerm = $request->title;
            $query->where('title', 'like', '%' . $searchTerm . '%');

            // حفظ سجل البحث
            if (Auth::check()) {
                SearchHistory::create([
                    'student_id' => Auth::id(),
                    'search_term' => $searchTerm
                ]);
            }
        }

        if ($request->has('category_name')) {
            $categoryName = $request->category_name;
            $category = categories::where('name', 'like', '%' . $categoryName . '%')->first();
            
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $books = $query->get();

        // جلب سجل البحث للطالب الحالي
        $searchHistory = [];
        if (Auth::check()) {
            $searchHistory = SearchHistory::where('student_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $books,
            'search_history' => $searchHistory
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/search/history",
     *     summary="عرض سجل البحث",
     *     description="عرض آخر 10 عمليات بحث قام بها الطالب",
     *     tags={"Search"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="سجل البحث",
     *         @OA\JsonContent(
     *             @OA\Property(property="search_history", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="search_term", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             ))
     *         )
     *     ),
     *     @OA\Response(response=401, description="غير مصرح")
     * )
     */
    public function getSearchHistory()
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'يجب تسجيل الدخول لعرض سجل البحث'
            ], 401);
        }

        $history = SearchHistory::where('student_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'search_history' => $history
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/search/history/{id}",
     *     summary="حذف عملية بحث من السجل",
     *     description="حذف عملية بحث محددة من سجل البحث",
     *     tags={"Search"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="معرف عملية البحث",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم الحذف بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="تم حذف سجل البحث بنجاح")
     *         )
     *     ),
     *     @OA\Response(response=401, description="غير مصرح"),
     *     @OA\Response(response=404, description="لم يتم العثور على سجل البحث")
     * )
     */
    public function deleteSearchHistory($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'يجب تسجيل الدخول لحذف سجل البحث'
            ], 401);
        }

        $history = SearchHistory::where('student_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$history) {
            return response()->json([
                'message' => 'لم يتم العثور على سجل البحث'
            ], 404);
        }

        $history->delete();

        return response()->json([
            'message' => 'تم حذف سجل البحث بنجاح'
        ]);
    }
} 