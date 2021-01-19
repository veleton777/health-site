<?php


namespace App\Serializers\Normalizers\Article;


class ArticlesListNormalizer
{
    /**
     * @param array $data
     * @return array
     */
    public function normalize(array $data): array
    {
        $normalized['data'] = $data['articles'];
        $normalized['count'] = $data['count'];
        if ($data['favorites']) {
            $data['favorites'] = $data['favorites']->toArray();
        }
        if ($data['likes']) {
            $data['likes'] = $data['likes']->toArray();
        }
        if ($data['recommends']) {
            $data['recommends'] = $data['recommends']->toArray();
        }

        foreach ($normalized['data'] as $article) {
            $article['favorite'] = $this->conformityNormalizer($data['favorites'], $article['id']);
            $article['like'] = $this->conformityNormalizer($data['likes'], $article['id']);
            $article['recommend'] = $this->conformityNormalizer($data['recommends'], $article['id']);
            $article['preview_image'] = config('filesystems.article_image_prefix') . $article['preview_image'];
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
