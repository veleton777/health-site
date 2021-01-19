<?php


namespace App\Serializers\Normalizers\Category;


use Illuminate\Support\Collection;

class CategoriesListNormalizer
{
    /**
     * @param Collection $categories
     * @return array
     */
    public function normalize(Collection $categories): array
    {
        $normalized['data'] = $categories->toArray();

        foreach ($normalized['data'] as $k => $category) {
            if ($category['image']) {
                $normalized['data'][$k]['image'] = config('filesystems.category_image_prefix') . $category['image'];
            }
        }

        return $normalized;
    }
}
