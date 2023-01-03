<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once the chosen bank account is deleted, all of its data will be permanently deleted. Before deleting the bank account, please download any data or information that you wish to retain. Precondition: only accounts with a balance of 0.00 can be deleted.') }}
        </p>
    </header>

    <form method="post" action="{{ route('accounts.delete') }}" class="mt-6 space-y-6">
        @csrf
        @method('delete')

        <div>
            <x-input-label for="account_number" :value="__('Choose account to delete')"/>
            <select
                id="account_number"
                name="account_number"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                required autofocus autocomplete="account_number">
                @foreach ($bankAccounts as $bankAccount)
                    @if ($bankAccount->name !== 'MAIN')
                    <option value="{{ $bankAccount->account_number }}">{{ $bankAccount->name }}
                        ({{ $bankAccount->account_number }})
                    </option>
                    @endif
                @endforeach
            </select>
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
                {{ __('Delete Account') }}
            </x-danger-button>
            @if (session('status') === 'account-deleted')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 4000)"
                    class="text-sm text-red-600"
                >{{ __('Bank Account Deleted') }}</p>
            @endif
        </div>
    </form>
</section>
