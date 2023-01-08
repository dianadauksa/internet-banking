<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Make Transfers') }}
        </h2>
    </x-slot>

    <style>
        .account-name:hover,
        .account-name:active {
            color: #6b7280;
            text-decoration: none;
        }

        .alert {
            color: red;
        }
    </style>

    <div class="py-12">

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <section class="space-y-6">
                    <div class="bg-white overflow-hidden shadow sm:rounded-lg">


                        <div class="mx-auto max-w-sm rounded shadow-lg">
                            <div class="flex flex-col bg-white p-4">
                                <div class="flex justify-center mb-4">
                                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                        {{ __('Transfer Request') }}
                                    </h2>
                                </div>
                                <div class="flex justify-center mb-2">
                                    @if (session('status'))
                                        <p
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-green-600"
                                        >{{ session('status') }}</p>
                                    @endif
                                </div>

                                <div class="flex justify-center">
                                    <form action="{{ route('transfers.make', $selectedIndex) }}" method="post">
                                        @csrf
                                        <div class="flex flex-col items-center">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <x-input-label for="account_from" :value="__('Choose your account')"/>
                                            <select id="account_from" name="account_from"
                                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                                    required autofocus autocomplete="account_from">
                                                @foreach ($accounts as $account)
                                                    <option
                                                        value="{{ $account->id }}">{{$account->currency}} {{ $account->balance }} {{ $account->name }}
                                                        ({{ $account->number }})
                                                    </option>
                                                @endforeach
                                            </select>

                                            <x-input-label class="mt-4" for="account_to"
                                                           :value="__('Recipient account')"/>
                                            <x-text-input id="account_to" name="account_to" type="text"
                                                          class="mt-1 block w-full text-center"
                                                          placeholder="Enter recipient's account e.g. LV77ORCL000000000"
                                                          required autofocus autocomplete="account_to"/>

                                            <x-input-label class="mt-4" for="amount" :value="__('Amount')"/>
                                            <x-text-input id="amount" name="amount" type="text"
                                                          class="mt-1 block w-1/2 text-center" placeholder="0.00"
                                                          required autofocus autocomplete="amount"/>

                                            <div id="password-field" style="display:none">
                                                <x-input-label class="mt-4 text-center" for="password"
                                                               :value="__('Password')"/>
                                                <x-text-input
                                                    id="password"
                                                    name="password"
                                                    type="password"
                                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-1/2 text-center"
                                                    placeholder="Enter password to confirm"
                                                />
                                            </div>

                                            <div id="security-code-field" style="display:none">
                                                <x-input-label class="mt-4 text-center" for="security_code"
                                                               value="Security Code No. {{$selectedIndex + 1}}"/>
                                                <x-text-input
                                                    id="security_code"
                                                    name="security_code"
                                                    type="text"
                                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-1/2 text-center"
                                                    placeholder="Enter code from code card"
                                                />
                                            </div>
                                            <x-primary-button class="mt-6">{{ __('Transfer') }}</x-primary-button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
            </div>
        </div>

    </div>
    <script>
        const accountToField = document.getElementById('account_to');
        const passwordField = document.getElementById('password-field');
        const securityCodeField = document.getElementById('security-code-field');
        accountToField.addEventListener('change', () => {
            const accountNumbers = @json($accounts->pluck('number'));
            if (accountNumbers.includes(accountToField.value)) {
                passwordField.style.display = 'none';
                securityCodeField.style.display = 'none';
            } else {
                passwordField.style.display = 'block';
                securityCodeField.style.display = 'block';
            }
        });
    </script>
</x-app-layout>
