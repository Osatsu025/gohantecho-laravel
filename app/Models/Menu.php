<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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

    public function favoritedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'menu_favorites', 'menu_id', 'user_id');
    } 

    /**
     * キーワードでメニューを検索するスコープ。
     * 対象：タイトル、本文、作者名、タグ名
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByKeyword($query, $keyword_str)
    {
        if (empty($keyword_str)) {
            return $query;
        }

        $keywords = preg_split('/\s+/u', $keyword_str, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($keywords as $keyword) {
            $escaped_keyword = addcslashes($keyword, '%_\\');
            $query->where(function ($q) use ($escaped_keyword) {
                $q->where('title', 'like', "%{$escaped_keyword}%")
                    ->orWhere('content', 'like', "%{$escaped_keyword}%")
                    ->orWhereHas('user', function ($sub_query) use ($escaped_keyword) {
                        $sub_query->where('name', 'like', "%{$escaped_keyword}%");
                    })
                    ->orWhereHas('tags', function ($sub_query) use ($escaped_keyword) {
                        $sub_query->where('name', 'like', "%{$escaped_keyword}%");
                    });
            });   
        }
        return $query;
    }

    /**
     * 作者名でメニューを絞り込むスコープ
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $author_name
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
     * 絞り込みボタンから選択されたタグで絞り込み検索をするスコープ
     *  
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int[] $tag_ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByTagIds($query, $tag_ids)
    {
        if (empty($tag_ids)) {
            return $query;
        }

        return $query->whereHas('tags', function ($q) use ($tag_ids) {
            $q->whereIn('tags.id', $tag_ids);
        }, '=', count($tag_ids));
    }

    /**
     * 作者が自分のメニュー/公開設定がONのメニューに絞り込むためのスコープ
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByPublic($query)
    {
        return $query->where(function ($q) {
                $q->where('user_id', Auth::id())
                    ->orWhere('public', true);
        });
    }

    /**
     * お気に入りに追加しているメニューに絞り込むためのスコープ
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param boolean $is_only_favorited
     * @param \Illuminate\Database\Eloquent\Builder
     *  
     */

     public function scopeFilterByFavorited($query, $is_only_favorited)
     {
        if (!$is_only_favorited) {
            return $query;
        }

        return $query->whereHas('favoritedUsers', function ($q) {
            $q->where('menu_favorites.user_id', Auth::id());
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
