<?php


namespace App\Serializers\Normalizers\Article;


class FavoriteArticlesListNormalizer
{
    /**
     * @param array $data
     * @return array
     */
    public function normalize(array $data): array
    {
        $normalized['data'] = $data['articles'];
        $normalized['count'] = $data['count'];
        if ($data['likes']) {
            $data['likes'] = $data['likes']->toArray();
        }
        if ($data['recommends']) {
            $data['recommends'] = $data['recommends']->toArray();
        }

        foreach ($normalized['data'] as $article) {
            $article['preview_image'] = config('filesystems.article_image_prefix') . $article['preview_image'];
            $article['like'] = $this->conformityNormalizer($data['likes'], $article['id']);
            $article['recommend'] = $this->conformityNormalizer($data['recommends'], $article['id']);
            unset($article['content']);
            unset($article['created_at']);
            unset($article['updated_at']);
            unset($article['published_at']);
        }

        return $normalized;
    }

    /**
     * @param array|null $data
     * @param $articleId
     * @return bool
     */
    private function conformityNormalizer(?array $data, $articleId): bool
    {
        if ($data) {
            if (array_search($articleId, array_column($data, 'article_id')) !== false) {
                return true;
            }
        }

        return false;
    }
}
