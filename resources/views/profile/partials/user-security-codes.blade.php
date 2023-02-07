<style>
    #codes {
        margin-top: 4px;
    }
    .code-index {
        font-weight: bold;
    }
</style>
<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Your security codes') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Each time you wish to make an external transfer to another person you will be asked to input one of these codes for security purposes.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'security-codes')"
    >{{ __('See Codes') }}</x-danger-button>

    <x-modal name="security-codes" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Security Code Card') }}
            </h2>

            <p class="mt-1 text-sm text-red-600 mb-1">
                {{ __('Do not share these codes with other persons and keep them safe where only you have access.') }}
            </p>

            <ul class="mt-1 text-center">
                @foreach($codes as $index => $code)
                    <li id="codes"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        <span class="code-index">{{$index +1 }}</span>. {{$code}}
                    </li>
                @endforeach
            </ul>

            <div class="mt-6 flex justify-end ml-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Close') }}
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
</section>
