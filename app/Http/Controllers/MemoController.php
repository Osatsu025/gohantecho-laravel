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

        $user->memos()->create([
            ...$validated,
            'menu_id' => $menu->id
        ]);

        return back();
    }

    public function update(MemoStoreRequest $request, Menu $menu, Memo $memo)
    {
        $this->authorize('update', $memo);

        $validated = $request->validated();
        $memo->update($validated);

        return back();
    }

    public function destroy(Menu $menu, Memo $memo)
    {
        $this->authorize('delete', $memo);
        
        $memo->delete();

        return back();
    }
}
