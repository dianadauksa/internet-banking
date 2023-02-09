<style>
    #balance {
        font-weight: bold;
    }
</style>
<section class="space-y-6">
    <div class="bg-white overflow-hidden shadow sm:rounded-lg">

            <div class="p-6 border-b border-gray-200">
                <a href="{{ route('accounts.show', $account) }}" class="block font-bold text-xl mb-2 account-name">
                    {{ $account->name }}
                </a>
                <div class="text-gray-700 font-medium text-sm">
                    Account Number: {{ $account->number }}
                </div>
                <div class="text-gray-700 font-medium text-sm">
                    <span id="balance">Balance:</span> {{ number_format($account->balance,2) }} {{ $account->currency }}
                </div>
            </div>

    </div>
</section>
