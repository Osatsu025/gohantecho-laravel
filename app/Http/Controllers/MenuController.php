<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index() {
        $menus = Menu::with('user')->get();
        return view('menus.index', compact('menus'));
    }
}
