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

          <form action="{{ route('menus.index') }}" id="search_form">
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
            <button type="button" class="btn btn-sm" onclick="filter_modal.showModal()">絞り込み</button>
            <dialog id="filter_modal" class="modal">
              <div class="modal-box">
                <form method="dialog">
                  <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="text-lg font-bold mb-4">絞り込み検索</h3>
                @foreach($tags as $tag)
                  <input
                    type="checkbox"
                    aria-label="{{ $tag->name }}"
                    class="btn btn-xs mr-2 mb-2 tag-checkbox-selector"
                    data-tag-name="{{ $tag->name }}"
                    value="{{ $tag->id }}"
                    id="tag_{{ $tag->id }}"
                    name="tag_ids[]"
                    onchange="add_tag('{{ json_encode($tag, JSON_HEX_APOS | JSON_HEX_QUOT) }}', this)"
                    {{ is_array(old('tag_ids')) && in_array($tag->id, old("tag_ids")) ? 'checked' : '' }}
                  />
                @endforeach
                <div class="modal-action">
                  <button class="btn">この条件で絞り込む</button>
                </div>
              </div>
            </dialog>
          </form>

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
                  <td><a href="{{ route('menus.show', $menu) }}">{{ $menu->title }}</a></td>
                  @if ($menu->user)
                  <td><a href="{{ route('menus.index', ['author' => $menu->user->name]) }}">{{ $menu->user->name }}</a></td>
                  @else
                  <td>{{ __('Unknown') }}</td>
                  @endif
                  <td>
                    @foreach ($menu->tags as $tag)
                    <a href="{{ route('menus.index', ['tag_id' => $tag->id]) }}" role="button" class="btn btn-xs">{{ $tag->name }} </button>
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