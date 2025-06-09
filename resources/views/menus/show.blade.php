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
              <h2 class="text-l mr-auto">{{ $menu->user->name }}</h2>
              @if ($menu->user == Auth::user())
              <a role="button" class="btn" href="{{ route('menus.edit', $menu) }}">{{ __('Edit') }}</a>
              @endif
            </div>
            <p class="mb-4">{!! nl2br(e($menu->content)) !!}</p>
            <div>
              <p>タグ</p>
              @foreach ($menu->tags as $tag)
              <button class="btn btn-xs">{{ $tag->name }}</button>
              @endforeach 
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</x-app-layout>