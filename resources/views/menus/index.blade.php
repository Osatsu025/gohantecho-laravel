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

          <form action="{{ route('menus.index') }}" id="search_form" class="flex">
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
            <select class="select select-sm w-auto" name="sort_type" id="sort_type" onchange="document.getElementById('search_form').submit();">
              @foreach ($sort_list as $name => $conditions)
                @if ($sort_type === $name)
                <option value="{{ $name }}" selected>{{ $name }}</option>
                @else
                <option value="{{ $name }}">{{ $name }}</option>
                @endif
              @endforeach
            </select>
            <a href="{{ route('menus.create') }}" class="btn btn-neutral ml-auto">新規投稿</a>
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
                  <td><a href="{{ route('menus.index', ['user_id' => $menu->user->id]) }}">{{ $menu->user->name }}</a></td>
                  <td>
                  @foreach ($menu->tags as $tag)
                  <button class="btn btn-xs">{{ $tag->name }} </button>
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