<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // メニューと対応するタグの名前を定義
        $menuTags = [
            '鶏肉とじゃがいもの甘辛煮' => ['和食', '主菜', '晩ごはん', '簡単', '鶏肉', 'じゃがいも', 'フライパン1つ'],
            '卵と豆腐の中華スープ' => ['中華', '汁物', 'スープ', 'ヘルシー', '卵', '豆腐', '簡単'],
            'ツナとブロッコリーのパスタ' => ['洋食', '主菜', '昼ごはん', '時短', 'ツナ缶', 'ブロッコリー', 'パスタ'],
            '洋風ハンバーグ弁当' => ['洋食', '主菜', 'お弁当', 'ひき肉', 'たまねぎ', 'ボリューム'],
            '人参とツナのしりしり風' => ['和食', '副菜', '作り置き', '簡単', 'にんじん', 'ツナ缶', 'フライパン1つ'],
            'ブロッコリーと卵のサラダ' => ['サラダ', '副菜', 'ヘルシー', '朝ごはん', 'ブロッコリー', '卵'],
            '豚こまと玉ねぎの生姜焼き' => ['和食', '主菜', '晩ごはん', '簡単', '豚肉', 'たまねぎ', 'フライパン1つ'],
            'じゃがいもとチーズのガレット' => ['洋食', '副菜', 'おやつ', 'チーズ', 'じゃがいも', 'フライパン1つ'],
            '韓国風ピーマンの肉詰め' => ['韓国料理', '主菜', '晩ごはん', 'ひき肉', 'ピーマン', 'オーブン'],
            '豆腐とわかめの味噌汁' => ['和食', '汁物', 'スープ', '朝ごはん', '豆腐', '海藻', '簡単'],
        ];

        foreach ($menuTags as $menuTitle => $tagNames) {
            $menu = DB::table('menus')->where('title', $menuTitle)->first();

            if (!$menu) {
                continue;
            }

            foreach ($tagNames as $tagName) {
                $tag = DB::table('tags')->where('name', $tagName)->first();

                if ($tag) {
                    DB::table('menu_tag')->insert([
                        'menu_id' => $menu->id,
                        'tag_id' => $tag->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
    }
}
