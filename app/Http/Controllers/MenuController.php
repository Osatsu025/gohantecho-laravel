<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuIndexRequest;
use App\Http\Requests\MenuStoreRequest;
use App\Models\Menu;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public const SORT_LIST = [
        '作成日の新しい順' => ['column' => 'created_at', 'direction' => 'desc'],
        '作成日の古い順' => ['column' => 'created_at', 'direction' => 'asc'],
        '更新日の新しい順' => ['column' => 'updated_at', 'direction' => 'desc'],
        '更新日の古い順' => ['column' => 'updated_at', 'direction' => 'asc'],
    ];

    public function index(MenuIndexRequest $request) {

        $sort_list = self::SORT_LIST;

        $validated = $request->validated();

        $keyword = $validated['keyword'] ?? null;
        $author = $validated['author'] ?? null;
        $sort_type = $validated['sort_type'] ?? array_key_first(self::SORT_LIST);

        $query = Menu::query()
            ->with(['user', 'tags'])
            ->searchByKeyword($keyword)
            ->filterByAuthor($author);

        $sort_column = self::SORT_LIST[$sort_type]['column'];
        $sort_direction = self::SORT_LIST[$sort_type]['direction'];
        $query->orderBy($sort_column, $sort_direction);

        $menus = $query->paginate(10);

        return view('menus.index', compact(
            'menus',
            'keyword',
            'author',
            'sort_list',
            'sort_type',
        ));
    }

    public function create() {
        $tags = Tag::all();
        return view('menus.create', compact('tags'));
    }

    public function store(MenuStoreRequest $request) {

        $validated = $request->validated();
        /** @var User $user */
        $user = Auth::user();
        $menu = $user->menus()->create($validated);
        
        $tag_names_str = $validated['input_tags'] ?? '';
        $tag_ids = Tag::findOrCreateByName($tag_names_str);

        $menu->tags()->attach($tag_ids);

        $message = $menu->title . 'を登録しました';

        return to_route('menus.index')->with('flash_message', $message);
    }

    public function show(Menu $menu) {
        $menu->load(['user', 'tags']);

        return view('menus.show', compact('menu'));
    }

    public function edit(Menu $menu) {

        $this->authorize('update', $menu);

        $menu->load(['user', 'tags']);
        $tags = Tag::all();

        $input_selected_tags = $menu->formatted_tags;

        return view('menus.edit', compact(
            'menu',
            'tags',
            'input_selected_tags',
        ));
    }

    public function update(MenuStoreRequest $request, Menu $menu) {
        $this->authorize('update', $menu);

        $validated = $request->validated();

        $menu->update($validated);

        $tag_names_str = $validated['input_tags'] ?? '';
        $tag_ids = Tag::findOrCreateByName($tag_names_str);

        $menu->tags()->sync($tag_ids);

        $message = $menu->title . 'を更新しました';

        return to_route('menus.show', $menu)->with('flash_message', $message);
    }

    public function destroy(Menu $menu) {

        $this->authorize('delete', $menu);
        
        $message = $menu->title . 'を削除しました';
        
        $menu->delete();

        return to_route('menus.index')->with('flash_message', $message);
    }

}