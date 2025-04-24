<?php

namespace App\Http\Controllers;

use App\Models\categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $category = new categories();
        $category->name = $request->name;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/categories', $filename);
            $category->image = $filename;
        }

        $category->save();

        return response()->json([
            'message' => 'تم إضافة الفئة بنجاح',
            'category' => $category
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $category = categories::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $category->name = $request->name;

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا وجدت
            if ($category->image) {
                Storage::delete('public/categories/' . $category->image);
            }

            $image = $request->file('image');
            $filename = Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/categories', $filename);
            $category->image = $filename;
        }

        $category->save();

        return response()->json([
            'message' => 'تم تحديث الفئة بنجاح',
            'category' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = categories::findOrFail($id);

        // حذف الصورة إذا وجدت
        if ($category->image) {
            Storage::delete('public/categories/' . $category->image);
        }

        $category->delete();

        return response()->json([
            'message' => 'تم حذف الفئة بنجاح'
        ]);
    }
} 