<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('New Crypto Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Open a new account for investing in Crypto Currency.") }}
        </p>
    </header>

    <form method="post" action="{{ route('crypto.add') }}" class="mt-6 space-y-6">
        @csrf
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Open Crypto Account') }}</x-primary-button>
        </div>
    </form>
</section>
