<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Account Name') }}
        </h2>
    </header>
    <form method="post" action="{{route('accounts.update', $account)}}" class="mt-6 space-y-6">
        @csrf
        @method('put')
        <div>
            <x-input-label for="name" :value="__('New name')"/>
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $account->name)"
                          required autofocus autocomplete="name"/>
            <x-input-error :messages="$errors->get('main-account')" class="mt-2"/>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Update name') }}</x-primary-button>

            @if (session('status') === 'name-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Account Name Updated') }}</p>
            @endif
        </div>
    </form>
</section>
