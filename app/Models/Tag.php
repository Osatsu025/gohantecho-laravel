<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];
    
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class);
    }

    /**
     * スペース区切りのタグ名文字列からタグを検索または作成し、IDの配列を返す
     * 
     * @param string $tag_names_str
     * @return int[]
     */
    public static function findOrCreateByName(string $tag_names_str): array
    {
        $tag_names = [];
        $normalized_tag_names_str = mb_convert_kana($tag_names_str, 's');
        if (trim($normalized_tag_names_str) != '') {
            $tag_names = array_unique(
                array_filter(
                    array_map('trim', explode(' ', $normalized_tag_names_str)),
                    'strlen'
                )
            );
        }

        if (empty($tag_names)) {
            return [];
        }

        $tag_ids = [];
        foreach($tag_names as $tag_name) {
            $tag = self::withTrashed()->where('name', $tag_name)->first();
            if ($tag) {
                if ($tag->trashed()) {
                    $tag->restore();
                }
            } else {
                $tag = self::create(['name' => $tag_name]);
            }
            $tag_ids[] = $tag->id;
        }
        return $tag_ids;
    }
}
