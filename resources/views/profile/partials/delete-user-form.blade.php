<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-base-content">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-base-content">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button class="btn btn-error" onclick="delete_modal.showModal()">{{ __('Delete') }}</button>
    <dialog id="delete_modal" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold">{{ __('Are you sure you want to delete your account?') }}</h3>
            <p class="py-4">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <label class="label cursor-pointer my-4">
                    <input type="checkbox" checked="checked" class="checkbox" name="is_delete_menus" value="1">
                    {{ __('Delete all menu you have posted along with your account') }}
                </label>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="delete_modal.close()">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-error">{{ __('Delete Account') }}</button>
                </div>
            </form>
        </div>
    </dialog>
</section>
