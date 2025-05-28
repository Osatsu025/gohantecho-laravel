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
            <table class="table">
              <thead>
                <tr>
                  <th>タイトル</th>
                  <th>作者</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($menus as $menu)
                <tr class="hover:bg-base-300">
                  <td>{{ $menu->title }}</td>
                  <td>{{ $menu->user->name }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>