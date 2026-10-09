<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    // 投稿フォームのセレクトボックス用（名前順）
    public function getAllOrderByName()
    {
        return Category::orderBy('name')->get();
    }
}
