<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete This Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once the chosen bank account is deleted, all of its data will be permanently deleted. Before deleting the bank account, please download any data or information that you wish to retain. Precondition: only accounts with a balance of 0.00 can be deleted.') }}
        </p>
    </header>

    <form method="post" action="{{ route('accounts.delete', $account) }}" class="mt-6 space-y-6">
        @csrf
        @method('delete')

        <div>
            @if (session('status') === 'cannot-delete-account-balance')
                <x-input-error :messages="'Cannot delete account with a balance greater than 0.00'" class="mt-2"/>
            @elseif (session('status') === 'cannot-delete-account-main')
                <x-input-error :messages="'Cannot delete your main account'" class="mt-2"/>
            @endif
        </div>

        <div>
            <x-input-label for="password" value="Password"/>
            <x-text-input
                id="password"
                name="password"
                type="password"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                placeholder="Enter Your Password"
            />
            <x-input-error :messages="$errors->bankAccountDeletion->get('password')" class="mt-2"/>
        </div>

        <div>
            <x-danger-button class="flex items-center gap-4">
                {{ __('Delete') }}
            </x-danger-button>
        </div>
    </form>
</section>
