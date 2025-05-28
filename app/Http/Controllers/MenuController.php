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
            $query->where('title', 'like', "%{$keyword}%");
        }
        
        $menus = $query->with('user')->with('tags')->paginate(10);

        return view('menus.index', compact('menus'));
    }
}
