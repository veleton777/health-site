<?php

namespace App\UseCases\Category;

use App\Models\Article\Category;
use Illuminate\Support\Collection;

class CategoryService
{
    /**
     * @return Collection|Category[]
     */
    public function getAll(): Collection
    {
        return Category::query()
            ->withCount('articles')
            ->get();
    }
}
