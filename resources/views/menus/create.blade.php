<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Menu_create') }}
        </h2>
    </x-slot>
        
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="overflow-x-auto">
        <div class="p-6 sm:p-8 bg-base-100 shadow-md rounded-lg">
          <div class="max-w-xl mx-auto">
            <form method="POST" action="{{ route('menus.store') }}">
              @csrf
              <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-l border p-4">
                <legend class="fieldset-legend">新メニュー</legend>

                <label for="title" class="label">{{ __('Title') }}</label>
                <input type="text" class="input w-lg" placeholder="" name="title" value="{{ old('title') }}">

                <label for="content" class="label">{{ __('Content') }}</label>
                <textarea name="content" id="content" class="textarea w-lg size-100" placeholder="{{ __('Write Ingredients and order of your original recipe or Other sites URL or Restaurants menu you ate') }}">{{ old('content') }}</textarea>

                <label for="public">
                  <input type="checkbox" checked="checked" class="toggle" name="public" id="public" value="1">
                  {{ __('Public') }}
                </label>

                <button type="submit" class="btn btn-neutral">{{ __('Submit') }}</button>

              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>