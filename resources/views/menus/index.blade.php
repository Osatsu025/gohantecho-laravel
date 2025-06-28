<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Menu') }}
        </h2>
    </x-slot>
        
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="overflow-x-auto">
        <div class="p-6 sm:p-8 bg-base-100 shadow-md rounded-lg">
          <div class="max-w-xl mx-auto">

          @if (session('flash_message'))
          <div role="alert" class="alert alert-success mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>{{ session('flash_message') }}</span>
          </div>
          @endif

          @if (session('error_message'))
          <div role="alert" class="alert alert-error mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>{{ session('error_message') }}</span>
          </div>
          @endif

          <form action="{{ route('menus.index') }}" id="search_form" class="mb-4">
            <div class="flex mb-2">
              @if ($author)
              <input type="hidden" name="author" value="{{ $author }}">
              @endif
              <label class="input">
                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <g
                    stroke-linejoin="round"
                    stroke-linecap="round"
                    stroke-width="2.5"
                    fill="none"
                    stroke="currentColor"
                  >
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                  </g>
                </svg>
                <input type="text" class="grow" placeholder="{{ __('search')}}" name="keyword" value="{{ $keyword }}" />
              </label>
              <a href="{{ route('menus.create') }}" class="btn btn-neutral ml-auto">新規投稿</a>
            </div>
            <select class="select select-sm w-auto" name="sort_type" id="sort_type" onchange="document.getElementById('search_form').submit();">
              @foreach ($sort_list as $name => $details)
                <option value="{{ $name }}" @selected($sort_type === $name)>{{ $name }}</option>
              @endforeach
            </select>
            <button type="button" class="btn btn-sm @if($tag_ids) btn-primary @endif" onclick="filter_modal.showModal()">絞り込み</button>
            <dialog id="filter_modal" class="modal">
              <div class="modal-box">
                <form method="dialog">
                  <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <div class="flex justify-around mb-4">
                  <h3 class="text-lg font-bold">絞り込み検索</h3>
                  <button type="button" id="reset_filter_button" class="btn">リセット</button>
                </div>
                @foreach($tags as $tag)
                  <input
                    type="checkbox"
                    aria-label="{{ $tag->name }}"
                    class="btn btn-xs mr-2 mb-2 tag-checkbox-selector"
                    data-tag-name="{{ $tag->name }}"
                    value="{{ $tag->id }}"
                    id="tag_{{ $tag->id }}"
                    name="tag_ids[]"
                    @checked( is_array($tag_ids) && in_array($tag->id, $tag_ids)) )
                  />
                @endforeach
                <div class="modal-action">
                  <button class="btn btn-primary" form="search_form" type="submit">この条件で絞り込む</button>
                </div>
              </div>
            </dialog>
          </form>

          <h3 class="text-lg">
            @if (!empty($author) || !empty($selected_tag_names))
              <span class="text-base-content/70">絞り込み条件： </span>
            @endif
            @if (!empty($author))
              {{ $author . ' ' }}
            @endif
            @if (!empty($selected_tag_names))
              {{ implode(' ', $selected_tag_names) . ' ' }}
            @endif
          </h3>

            <table class="table mb-4">
              <thead>
                <tr>
                  <th>タイトル</th>
                  <th>作者</th>
                  <th>タグ</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($menus as $menu)
                <tr class="hover:bg-base-300">
                  <td><a href="{{ route('menus.show', array_merge(['menu' => $menu], request()->query())) }}">{{ $menu->title }}</a></td>
                  @if ($menu->user)
                  <td><a href="{{ route('menus.index', ['author' => $menu->user->name]) }}">{{ $menu->user->name }}</a></td>
                  @else
                  <td>{{ __('Unknown') }}</td>
                  @endif
                  <td>
                    @foreach ($menu->tags as $tag)
                      @php
                        $current_tag_ids_from_query = request()->query('tag_ids', []);
                        if (!is_array($current_tag_ids_from_query)) {
                          $current_tag_ids_from_query = [$current_tag_ids_from_query];
                        }
                        $current_tag_ids_from_query = array_map('intval', $current_tag_ids_from_query);

                        $is_active_tag = in_array($tag->id, $current_tag_ids_from_query);

                        if ($is_active_tag) {
                          $new_tag_ids = array_values(array_filter($current_tag_ids_from_query, fn($id) => $id != $tag->id));
                        } else {
                          $new_tag_ids = array_values(array_unique(array_merge($current_tag_ids_from_query, [$tag->id])));
                        }
                      @endphp
                      <a href="{{ route('menus.index', array_merge(request()->query(), ['tag_ids' => $new_tag_ids, 'page' => 1])) }}"
                        role="button" class="btn btn-xs @if($is_active_tag) btn-soft btn-primary @endif">{{ $tag->name }}</a>
                    @endforeach
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="mb-4">
              {{ $menus->appends(request()->query())->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

<script>
  // DOMが完全に読み込まれた後にスクリプト実行
  document.addEventListener('DOMContentLoaded', function() {
    const resetButton = document.getElementById('reset_filter_button');
    const checkboxes = document.querySelectorAll('#filter_modal .tag-checkbox-selector');

    function updateResetButtonState() {
      const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        if (isAnyChecked) {
          resetButton.classList.add('btn-neutral');
        } else {
          resetButton.classList.remove('btn-neutral');
        }
    }

    resetButton.addEventListener('click', function() {
      checkboxes.forEach(checkbox => checkbox.checked = false);
      updateResetButtonState();
    })

    checkboxes.forEach(checkbox => checkbox.addEventListener('change', updateResetButtonState));

    updateResetButtonState();

  });
</script>