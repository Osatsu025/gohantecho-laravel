<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Casts\Attribute as CastsAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'public',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * キーワードでメニューを検索するスコープ。
     * 対象：タイトル、本文、作者名、タグ名
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByKeyword($query, $keyword)
    {
        if (empty($keyword)) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('content', 'like', "%{$keyword}%")
                ->orWhereHas('user', function ($sub_query) use ($keyword) {
                    $sub_query->where('name', 'like', "%{$keyword}%");
                })
            ->orWhereHas('tags', function ($sub_query) use ($keyword) {
                $sub_query->where('name', 'like', "%{$keyword}%");
            });
        });
    }

    /**
     * 作者名でメニューを絞り込むスコープ
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByAuthor($query, $author_name)
    {
        if (empty($author_name)) {
            return $query;
        }

        return $query->whereHas('user', function ($q) use ($author_name) {
            $q->where('name', $author_name);
        });
    }

    /**
     * 関連付けられたタグをスペース区切りの文字列として取得するアクセサ
     * 
     * @return \Illuminate\Database\Eloquent\Casts\Attribute 
     */
    protected function formattedTags(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tags->pluck('name')->implode(' '),
        );
    }
}
