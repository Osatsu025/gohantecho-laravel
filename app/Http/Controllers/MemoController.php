<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemoStoreRequest;
use App\Models\Memo;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class MemoController extends Controller
{

    public function store(MemoStoreRequest $request, Menu $menu)
    {
        $this->authorize('create', Memo::class);

        $validated = $request->validated();
        /** @var User $user */
        $user = Auth::user();

        if ($menu->memos()->where('user_id', $user->id)->exists()) {
            return back()->with('error_message', 'メモはすでに登録されています');
        }

        $user->memos()->create([
            ...$validated,
            'menu_id' => $menu->id
        ]);

        return back()->with('flash_message', 'メモを登録しました');
    }

    public function update(MemoStoreRequest $request, Menu $menu, Memo $memo)
    {
        $this->authorize('update', $memo);

        $validated = $request->validated();
        $memo->update($validated);

        return back()->with('flash_message', 'メモを更新しました');
    }

    public function destroy(Menu $menu, Memo $memo)
    {
        $this->authorize('delete', $memo);
        
        $memo->delete();

        return back()->with('flash_message', 'メモを削除しました');
    }
}
