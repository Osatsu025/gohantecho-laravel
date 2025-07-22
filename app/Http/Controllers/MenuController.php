<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuIndexRequest;
use App\Http\Requests\MenuStoreRequest;
use App\Models\Menu;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MenuController extends Controller
{
    public const SORT_LIST = [
        '作成日の新しい順' => ['column' => 'created_at', 'direction' => 'desc'],
        '作成日の古い順' => ['column' => 'created_at', 'direction' => 'asc'],
        '更新日の新しい順' => ['column' => 'updated_at', 'direction' => 'desc'],
        '更新日の古い順' => ['column' => 'updated_at', 'direction' => 'asc'],
        'お気に入り数の多い順' => ['column' => 'favorited_users_count', 'direction' => 'desc'],
    ];

    public function index(MenuIndexRequest $request): View {

        $this->authorize('viewAny', Menu::class);

        $sort_list = self::SORT_LIST;

        $validated = $request->validated();

        $keyword = $validated['keyword'] ?? null;
        $author = $validated['author'] ?? null;
        $sort_type = $validated['sort_type'] ?? array_key_first(self::SORT_LIST);
        $tag_ids = $validated['tag_ids'] ?? [];
        $selected_tags = collect();
        if (!empty($tag_ids)) {
            $selected_tags = Tag::whereIn('id', $tag_ids)->get();
        }
        $is_only_favorited = $validated['is_only_favorited'] ?? null;

        $query = Menu::query()
            ->with(['user', 'tags', 'favoritedUsers'])
            ->withCount('favoritedUsers')
            ->filterByPublic()
            ->searchByKeyword($keyword)
            ->filterByAuthor($author)
            ->filterByTagIds($tag_ids)
            ->filterByFavorited($is_only_favorited);

        $sort_column = self::SORT_LIST[$sort_type]['column'];
        $sort_direction = self::SORT_LIST[$sort_type]['direction'];
        $query->orderBy($sort_column, $sort_direction);

        $other_query = clone $query;

        $users_menus = $query->where('user_id', Auth::id())->paginate(10);
        $others_menus = $other_query->whereNot('user_id', Auth::id())->paginate(10);

        $tags = Tag::all();

        return view('menus.index', compact(
            'users_menus',
            'others_menus',
            'keyword',
            'author',
            'tag_ids',
            'selected_tags',
            'is_only_favorited',
            'sort_list',
            'sort_type',
            'tags',
        ));
    }

    public function create(): View {
        $this->authorize('create', Menu::class);

        $tags = Tag::all();
        return view('menus.create', compact('tags'));
    }

    public function store(MenuStoreRequest $request): RedirectResponse {
        $this->authorize('create', Menu::class);

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

    public function show(Menu $menu): View {
        $this->authorize('view', $menu);

        $user = Auth::user();

        $menu->load(['user', 'tags', 'memos'])
            ->loadCount('favoritedUsers');
        $memo = $menu->memos->where('user_id', $user->id)->first();
        $tag_ids = request()->query('tag_ids', []);

        return view('menus.show', compact('menu', 'tag_ids', 'memo'));
    }

    public function edit(Menu $menu): View {

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

    public function update(MenuStoreRequest $request, Menu $menu): RedirectResponse {
        $this->authorize('update', $menu);

        $validated = $request->validated();

        $menu->update($validated);

        $tag_names_str = $validated['input_tags'] ?? '';
        $tag_ids = Tag::findOrCreateByName($tag_names_str);

        $menu->tags()->sync($tag_ids);

        $message = $menu->title . 'を更新しました';

        return to_route('menus.show', $menu)->with('flash_message', $message);
    }

    public function destroy(Menu $menu): RedirectResponse {

        $this->authorize('delete', $menu);
        
        $message = $menu->title . 'を削除しました';
        
        $menu->delete();

        return to_route('menus.index')->with('flash_message', $message);
    }

    public function favorite(Menu $menu): RedirectResponse {

        $this->authorize('view', $menu);
        
        /** @var User $user */
        $user = Auth::user();
        $isFavorited = $user->favoriteMenus()->where('menu_id', $menu->id)->exists();

        if ($isFavorited) {
            $user->favoriteMenus()->detach($menu);
        } else {
            $user->favoriteMenus()->attach($menu);
        }

        return back();
    }

}