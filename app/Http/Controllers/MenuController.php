<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request) {
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
        
        $menus = $query->with('user')->with('tags')->paginate(10);

        return view('menus.index', compact(
            'menus',
            'keyword'
        ));
    }
}
