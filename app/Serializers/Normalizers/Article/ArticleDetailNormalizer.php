<?php


namespace App\Serializers\Normalizers\Article;


class ArticleDetailNormalizer
{
    /**
     * @param array $data
     * @return array
     */
    public function normalize(array $data): array
    {
        $normalized = $data['article']->toArray();
        $normalized['preview_image'] = config('filesystems.article_image_prefix') . $normalized['preview_image'];
        unset($normalized['created_at']);
        unset($normalized['updated_at']);
        unset($normalized['published_at']);
        $normalized['favorite'] = $data['favorite'];
        $normalized['like'] = $data['like'];
        $normalized['recommend'] = $data['recommend'];

        return $normalized;
    }
}
