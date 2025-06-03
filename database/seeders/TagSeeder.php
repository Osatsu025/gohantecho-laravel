<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // 🏷️ 料理ジャンル
            '和食', '洋食', '中華', '韓国料理', 'エスニック', 'イタリアン',

            // 🧑‍🍳 料理区分
            '主菜', '副菜', '汁物', 'サラダ', 'スープ', 'デザート',

            // 🍽️ 食事シーン・目的
            '朝ごはん', '昼ごはん', '晩ごはん', 'お弁当', '作り置き', 'おやつ',
            'ヘルシー', 'ダイエット', 'ボリューム', '節約', '簡単', '時短',

            // 🔪 調理方法・スタイル
            '電子レンジ', 'フライパン1つ', '炊飯器', 'オーブン', '煮込み', '蒸し料理',
            '冷凍保存', '常備菜',

            // 🧄 具材カテゴリ（肉・魚・野菜・卵など）
            '鶏肉', '豚肉', '牛肉', 'ひき肉', '魚', 'ツナ缶',
            'じゃがいも', 'にんじん', 'たまねぎ', 'ピーマン', 'ブロッコリー',
            '卵', '豆腐', 'きのこ', '海藻', '納豆', 'チーズ',

            // 🍞 主食・炭水化物
            'ごはん', 'パン', 'パスタ', 'うどん', 'そば', '米粉', 'もち',
        ];

        $now = Carbon::now();
        $tag_data = [];
        foreach($tags as $tag) {
            $tag_data[] = [
                    'name' => $tag,
                    'created_at' => $now,
                    'updated_at' => $now,
            ];
        }
        DB::table('tags')->insert($tag_data);
    }
}
