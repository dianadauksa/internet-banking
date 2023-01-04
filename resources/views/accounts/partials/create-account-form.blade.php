<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('New Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Open a new bank account and give it a custom name.") }}
        </p>
    </header>

    <form method="post" action="{{ route('accounts.add') }}" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Account name')"/>
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="e.g. Savings"
                          required autofocus autocomplete="name"/>
            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
        </div>

        <div>
            <x-input-label for="currency" :value="__('Currency')"/>
            <select id="currency" name="currency"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                    required autofocus autocomplete="currency">
                <option value="EUR">EUR</option>
                <option value="USD">USD</option>
                <option value="GBP">GBP</option>
                <option value="UAH">UAH</option>
                <option value="AUD">AUD</option>
                <option value="NOK">NOK</option>
            </select>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'account-created')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('New Account Opened') }}</p>
            @endif
        </div>
    </form>
</section>
