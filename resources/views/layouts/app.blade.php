<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roman System | @yield('title')</title>

    {{-- Flowbite CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    {{-- Bangla Font --}}
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'SolaimanLipi', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900">

    {{-- Navbar --}}
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="{{ url('/') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">রোমান সিস্টেম</span>
            </a>
            <button data-collapse-toggle="navbar-default" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M1 1h15M1 7h15M1 13h15"/>
                </svg>
            </button>
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50
                           md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white
                           dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">

                    <li>
                        <a href="{{ url('/') }}"
                           class="block py-2 px-3 {{ request()->is('/') ? 'text-white bg-blue-700' : 'text-gray-900 hover:text-blue-700' }} rounded-sm md:bg-transparent md:p-0 dark:text-white md:dark:hover:text-blue-500">
                            হোম
                        </a>
                    </li>

                    @auth
                        <li>
                            <a href="{{ route('customers.index') }}"
                               class="block py-2 px-3 text-gray-900 hover:text-blue-700 rounded-sm md:p-0 dark:text-white md:dark:hover:text-blue-500">
                                কাস্টমার
                            </a>
                        </li>

                        @can('user-list')
                            <li>
                                <a href="{{ route('users.index') }}"
                                   class="block py-2 px-3 text-gray-900 hover:text-blue-700 rounded-sm md:p-0 dark:text-white md:dark:hover:text-blue-500">
                                    ইউজার
                                </a>
                            </li>
                        @endcan

                        @role('admin')
                            <li>
                                <a href="{{ route('monthly.reports') }}"
                                   class="block py-2 px-3 text-gray-900 hover:text-blue-700 rounded-sm md:p-0 dark:text-white md:dark:hover:text-blue-500">
                                    রিপোর্ট
                                </a>
                            </li>
                        @endrole

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="block py-2 px-3 text-gray-900 hover:text-blue-700 rounded-sm md:p-0 dark:text-white md:dark:hover:text-blue-500">
                                    লগআউট
                                </button>
                            </form>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}"
                               class="block py-2 px-3 text-gray-900 hover:text-blue-700 rounded-sm md:p-0 dark:text-white md:dark:hover:text-blue-500">
                                লগইন
                            </a>
                        </li>
                    @endauth

                </ul>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-center py-4 text-sm text-gray-600 dark:text-gray-400">
        © {{ date('Y') }} রোমান ইলেকট্রনিক্স ও ফার্নিচার
    </footer>

    {{-- Flowbite Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @stack('scripts')
    @yield('scripts')

</body>
</html>
