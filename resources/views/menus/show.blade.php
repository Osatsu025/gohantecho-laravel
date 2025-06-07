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
            <div class="flex mb-4">
              <h1 class="text-2xl">{{ $menu->title }}</h1>
              <h2 class="text-l ml-auto">{{ $menu->user->name }}</h2>
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