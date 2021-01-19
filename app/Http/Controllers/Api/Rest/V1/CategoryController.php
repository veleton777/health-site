<?php

namespace App\Http\Controllers\Api\Rest\V1;

use App\Http\Controllers\Controller;
use App\Serializers\Normalizers\Category\CategoriesListNormalizer;
use App\UseCases\Category\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private $categoryService;
    private $categoriesListNormalizer;

    public function __construct(
        CategoryService $categoryService,
        CategoriesListNormalizer $categoriesListNormalizer
    )
    {
        $this->categoryService = $categoryService;
        $this->categoriesListNormalizer = $categoriesListNormalizer;
    }

    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $categories = $this->categoryService->getAll();

        return response()->json(
            $this->categoriesListNormalizer->normalize($categories)
        );
    }
}
