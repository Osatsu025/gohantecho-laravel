<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Menu_show') }}
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

            <div class="flex items-center mb-4">
              <h1 class="text-2xl mr-10">{{ $menu->title }}</h1>
              @if ($menu->user)
              <a href="{{ route('menus.index', array_merge(request()->query(), ['author' => $menu->user->name, 'page' => 1])) }}" class="text-l mr-auto">{{ $menu->user->name }}</a>
              @else
              <h2 class="text-1 mr-auto">{{ __('Unknown') }}</h2>
              @endif
              @can('update', $menu)
              <a role="button" class="btn mr-2" href="{{ route('menus.edit', $menu) }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                </svg>
                {{ __('Edit') }}
              </a>
              @endcan
              @can('delete', $menu)
              <button class="btn btn-error" onclick="delete_modal.showModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                  <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                </svg>
                {{ __('Delete') }}
              </button>
              <dialog id="delete_modal" class="modal">
                <div class="modal-box">
                  <h3 class="text-lg font-bold">{{ __('Do you really want to delete this menu ?') }}</h3>
                  <div class="modal-action">
                    <form method="dialog">
                      <button class="btn">{{ __('Cancel') }}</button>
                    </form>
                    <form method="POST" action="{{ route('menus.destroy', $menu) }}">
                      @csrf
                      @method('delete')
                      <input type="submit" value="{{ __('Delete') }}" class="btn btn-error">
                    </form>
                  </div>
                </div>
              </dialog>
              @endcan
            </div>
            <p class="mb-4">{!! nl2br(e($menu->content)) !!}</p>
            <div>
              <p>タグ</p>
              @foreach ($menu->tags as $tag)
              <a href="{{ route('menus.index', array_merge(request()->query(), ['tag_id' => $tag->id, 'page' => 1])) }}" role="button" class="btn btn-xs">{{ $tag->name }}</a>
              @endforeach 
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</x-app-layout>