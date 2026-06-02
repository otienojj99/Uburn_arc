<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{

    use ApiResponseTrait;

    public function __construct(
        protected CategoryService $categoryService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $filters = $request->only(['search', 'is_active', 'parent_id', 'trashed']);
        $perPage = $request->input('per_page', 15);

        $categories = $this->categoryService->getPaginated($filters, $perPage);
        return $this->successResponse(
            CategoryResource::collection($categories)->response()->getData(true)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $path = $file->store('categories', 's3'); // store in S3
            $url = Storage::disk('s3')->url($path);

            $data['image'] = $url;

            \Log::info('Stored image URL: ' . $url);
        }

        $category = $this->categoryService->create($data);
        if ($request->hasFile('image')) {
            \Log::info('Image file present: ' . $request->file('image')->getClientOriginalName());
        } else {
            \Log::info('No image file in request');
        }
        return $this->successResponse(
            new CategoryResource($category),
            'Category created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load(['parent', 'children']);
        return $this->successResponse(new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // 🔥 OPTIONAL: delete old image from S3
            if ($category->image) {
                $oldPath = parse_url($category->image, PHP_URL_PATH);
                $oldPath = ltrim($oldPath, '/');
                Storage::disk('s3')->delete($oldPath);
            }

            // ✅ Upload new image
            $path = $file->store('categories', 's3');
            $url = Storage::disk('s3')->url($path);

            $data['image'] = $url;

            \Log::info('Updated image URL: ' . $url);
        }

        $category = $this->categoryService->update($category, $data);

        return $this->successResponse(
            new CategoryResource($category),
            'Category updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $this->categoryService->delete($category);
            return $this->successResponse(null, 'Category deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Restore a soft-deleted category.
     */

    public function restore($id)
    {
        try {
            $this->categoryService->restore($id);
            return $this->successResponse(null, 'Category restored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Toggle category active status.
     */

    public function toggleActive(Category $category)
    {
        $this->categoryService->toggleActive($category);
        return $this->successResponse(
            new CategoryResource($category),
            'Category status toggled successfully.'
        );
    }
}
