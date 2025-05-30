<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    private const SORT_LIST = [
        '作成日の新しい順' => ['column' => 'created_at', 'direction' => 'desc'],
        '作成日の古い順' => ['column' => 'created_at', 'direction' => 'asc'],
        '更新日の新しい順' => ['column' => 'updated_at', 'direction' => 'desc'],
        '更新日の古い順' => ['column' => 'updated_at', 'direction' => 'asc'],
    ];

    public function index(Request $request) {

        $sort_list = self::SORT_LIST;
        
        $query = Menu::query();

        $keyword = $request->query('keyword');
        if ($keyword) {
            $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($query) use ($keyword) {
                        $query->where('users.name', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('tags', function($query) use ($keyword) {
                        $query->where('tags.name', 'like', "%{$keyword}%");
                    });
        }

        $query->with('user')->with('tags');

        $sort_type = $request->query('sort_type');
        if ($sort_type) {
            $column = self::SORT_LIST[$sort_type]['column'];
            $direction = self::SORT_LIST[$sort_type]['direction'];
            $query->orderBy($column, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $menus = $query->paginate(10);

        return view('menus.index', compact(
            'menus',
            'keyword',
            'sort_list',
            'sort_type',
        ));
    }
}
