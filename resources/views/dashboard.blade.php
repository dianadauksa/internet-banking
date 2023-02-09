<x-app-layout>
    <style>
        .feature:hover {
            scale: 1.05;
            transition: 0.3s;
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Explore Oracle Services') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                <img src="https://www.parallels.com/blogs/ras/app/uploads/2019/12/banking_technology.jpg" alt="banking icon" class="sm:rounded-lg w-full">
            </div>

            <div class="mt-4 bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2">

                    <div class="p-6 border-b border-gray-200 feature">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{route('accounts')}}" class="text-gray-900">Open Accounts</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-gray-600 text-sm">
                                Open a new account and start managing your money. Open and personalize additional accounts in different currencies for your financial goals.
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-b border-gray-200 md:border-l feature">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{route('statements')}}" class="text-gray-900">Track Transactions</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-gray-600 text-sm">
                                Track your transactions for each of your bank accounts. View your transaction history, filter out specific transactions by sender, receiver, date, and more.
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-b border-gray-200 feature">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{route('crypto')}}" class="text-gray-900">Purchase Crypto</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-gray-600 text-sm">
                                Purchase and sell crypto with your investment bank account. Invest in your favorite coins and start your investment journey.
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-b border-gray-200 md:border-l feature">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{route('crypto')}}" class="text-gray-900">Shortlist Crypto</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-gray-600 text-sm">
                                View crypto prices and price changes live! Shortlist your favorite coins and track them in real time to make the best investment decisions.
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-b border-gray-200 md:border-0 feature">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{route('transfers')}}" class="text-gray-900">Transfer Funds</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-gray-600 text-sm">
                                Make transfers between your accounts, or to other accounts. See transactions history, filter by date, account, recipient etc.
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-b border-gray-200 md:border-l feature">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{route('crypto.portfolio')}}" class="text-gray-900">Grow Your Portfolio</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-gray-600 text-sm">
                                Follow your crypto portfolio potential profits and losses in real time. Track your portfolio's performance and see how your investments are doing.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
