<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Roman Electronic & Furnitures | @yield('title')</title>

    {{-- Flowbite and Tailwind --}}
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    {{-- Bangla Font --}}
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'SolaimanLipi', sans-serif;
        }

        .active {
            @apply text-blue-600 font-semibold;
        }
    </style>
</head>

<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-blue-600 border-gray-200 px-4 py-2.5 dark:bg-blue-800">
        <div class="container flex flex-wrap items-center justify-between mx-auto">
            <a href="{{ url('/') }}" class="flex items-center text-white text-xl font-bold">
                রোমান সিস্টেম
            </a>
            <button data-collapse-toggle="navbar-dropdown" type="button"
                class="inline-flex items-center p-2 ml-3 text-sm text-white rounded-lg md:hidden hover:bg-blue-700 focus:outline-none"
                aria-controls="navbar-dropdown" aria-expanded="false">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <div class="hidden w-full md:block md:w-auto" id="navbar-dropdown">
                @auth
                    <ul class="flex flex-col md:flex-row md:space-x-6 md:mt-0 text-white text-sm font-medium">

                        {{-- কাস্টমার --}}
                        <li>
                            <button id="dropdownCustomer" data-dropdown-toggle="dropdownCustomerMenu"
                                class="flex items-center gap-1">কাস্টমার <svg class="w-4 h-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg></button>
                            <div id="dropdownCustomerMenu"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <ul class="py-2 text-sm text-gray-700">
                                    @can('customer-list')
                                        <li><a href="{{ route('customers.index') }}"
                                                class="block px-4 py-2 hover:bg-gray-100">সব কাস্টমার</a></li>
                                    @endcan
                                    @can('customer-create')
                                        <li><a href="{{ route('customers.create') }}"
                                                class="block px-4 py-2 hover:bg-gray-100">নতুন কাস্টমার</a></li>
                                    @endcan
                                </ul>
                            </div>
                        </li>

                        {{-- অন্যান্য মেনু যেমনঃ পারচেস, পণ্য, ইউজার, রিপোর্ট একইভাবে যোগ করুন --}}
                        {{-- উদাহরণ: --}}
                        {{-- <li><a href="{{ route('purchases.index') }}" class="hover:underline">পারচেস</a></li> --}}

                        {{-- ইউজার --}}
                        <li>
                            <button id="dropdownUser" data-dropdown-toggle="dropdownUserMenu"
                                class="flex items-center gap-1">ইউজার</button>
                            <div id="dropdownUserMenu"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <ul class="py-2 text-sm text-gray-700">
                                    @can('user-list')
                                        <li><a href="{{ route('users.index') }}"
                                                class="block px-4 py-2 hover:bg-gray-100">সব ইউজার</a></li>
                                    @endcan
                                    @can('role-list')
                                        <li><a href="{{ route('roles.index') }}"
                                                class="block px-4 py-2 hover:bg-gray-100">রোল</a></li>
                                    @endcan
                                </ul>
                            </div>
                        </li>

                        {{-- রিপোর্ট --}}
                        @role('admin')
                            <li><a href="{{ route('monthly.reports') }}"
                                    class="hover:underline">মাসিক রিপোর্ট</a></li>
                        @endrole

                        {{-- লগআউট --}}
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="hover:underline">লগআউট</button>
                            </form>
                        </li>
                    </ul>
                @endauth

                @guest
                    <ul class="flex flex-col md:flex-row md:space-x-4 text-white">
                        <li><a href="{{ route('login') }}" class="hover:underline">লগইন</a></li>
                    </ul>
                @endguest
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="container mx-auto mt-4 px-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-center mt-10 py-4 text-sm bg-white border-t">
        © {{ date('Y') }} রোমান ইলেকট্রনিক্স ও ফার্নিচার
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @stack('scripts')
    @yield('scripts')

</body>

</html>
