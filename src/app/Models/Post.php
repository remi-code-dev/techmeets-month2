<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    /**
     * マスアサインメント可能な属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'category_id',
    ];

    /**
     * この投稿が属するカテゴリー
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
