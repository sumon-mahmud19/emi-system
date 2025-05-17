<!doctype html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Roman Electronic & Furnitures @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Bangla Font -->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <style>

        /* Tailwind-style override colors */
        :root {
            --tw-purple-700: #7e22ce;
            --tw-purple-600: #9333ea;
            /* slightly lighter purple for hover */
            --tw-slate-100: #f1f5f9;
            --tw-slate-800: #1e293b;
            --tw-gray-700: #374151;
        }

        body {
            font-family: 'SolaimanLipi', sans-serif;
            background-color: var(--tw-slate-100);
            color: var(--tw-gray-700);
        }

        /* Navbar overrides */
        .navbar {
            background-color: var(--tw-purple-700) !important;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: white;
        }

        .navbar-dark .navbar-nav .nav-link.active,
        .navbar-dark .navbar-nav .nav-link:hover {
            color: #e9d5ff !important;
            /* light purple */
        }

        /* Dropdown menu styling */
        .dropdown-menu {
            background-color: white;
            border: 1px solid var(--tw-slate-800);
        }

        .dropdown-item:hover,
        .dropdown-item.active {
            background-color: var(--tw-purple-700);
            color: white !important;
        }

        /* Footer */
        .footer {
            background-color: var(--tw-slate-100);
            color: var(--tw-gray-700);
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--tw-purple-700);
            border-color: var(--tw-purple-700);
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: var(--tw-purple-600);
            border-color: var(--tw-purple-600);
        }

    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top"
        style="background-color: #8813b6; border-radius: 0 0 12px 12px;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">Roman EMI System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                @auth
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        {{-- কাস্টমার --}}
                        <li class="nav-item dropdown {{ request()->is('customers*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown">কাস্টমার</a>
                            <ul class="dropdown-menu dropdown-menu-light">
                                @can('customer-list')
                                    <li><a class="dropdown-item {{ request()->is('customers') ? 'active' : '' }}"
                                            href="{{ route('customers.index') }}">সব কাস্টমার</a></li>
                                @endcan
                                @can('customer-create')
                                    <li><a class="dropdown-item {{ request()->is('customers/create') ? 'active' : '' }}"
                                            href="{{ route('customers.create') }}">নতুন কাস্টমার</a></li>
                                @endcan
                            </ul>
                        </li>

                        {{-- পারচেস --}}
                        <li class="nav-item dropdown {{ request()->is('purchases*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">পারচেস</a>
                            <ul class="dropdown-menu">
                                @can('purchase-list')
                                    <li><a class="dropdown-item {{ request()->is('purchases') ? 'active' : '' }}"
                                            href="{{ route('purchases.index') }}">সব পারচেস</a></li>
                                @endcan
                                @can('purchase-create')
                                    <li><a class="dropdown-item {{ request()->is('purchases/create') ? 'active' : '' }}"
                                            href="{{ route('purchases.create') }}">নতুন পারচেস</a></li>
                                @endcan
                            </ul>
                        </li>

                        {{-- লোকেশন --}}
                        <li class="nav-item dropdown {{ request()->is('locations*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">লোকেশন</a>
                            <ul class="dropdown-menu">
                                @can('location-list')
                                    <li><a class="dropdown-item {{ request()->is('locations') ? 'active' : '' }}"
                                            href="{{ route('locations.index') }}">সব লোকেশন</a></li>
                                @endcan
                                @can('location-create')
                                    <li><a class="dropdown-item {{ request()->is('locations/create') ? 'active' : '' }}"
                                            href="{{ route('locations.create') }}">নতুন লোকেশন</a></li>
                                @endcan
                            </ul>
                        </li>

                        {{-- ইউজার --}}
                        @can('user-list')
                            <li class="nav-item dropdown {{ request()->is('users*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">ইউজার</a>
                                <ul class="dropdown-menu">
                                    @can('user-list')
                                        <li><a class="dropdown-item {{ request()->is('users') ? 'active' : '' }}"
                                                href="{{ route('users.index') }}">সব ইউজার</a></li>
                                    @endcan
                                    @can('user-create')
                                        <li><a class="dropdown-item {{ request()->is('users/create') ? 'active' : '' }}"
                                                href="{{ route('users.create') }}">নতুন ইউজার</a></li>
                                    @endcan
                                    @can('role-list')
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item {{ request()->is('roles') ? 'active' : '' }}"
                                                href="{{ route('roles.index') }}">রোল ম্যানেজমেন্ট</a></li>
                                    @endcan
                                    @can('role-create')
                                        <li><a class="dropdown-item {{ request()->is('roles/create') ? 'active' : '' }}"
                                                href="{{ route('roles.create') }}">নতুন রোল</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan

                        {{-- পণ্য --}}
                        <li
                            class="nav-item dropdown {{ request()->is('products*') || request()->is('product-models*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">পণ্য</a>
                            <ul class="dropdown-menu">
                                @can('product-list')
                                    <li><a class="dropdown-item {{ request()->is('products') ? 'active' : '' }}"
                                            href="{{ route('products.index') }}">সব পণ্য</a></li>
                                @endcan
                                @can('product-create')
                                    <li><a class="dropdown-item {{ request()->is('products/create') ? 'active' : '' }}"
                                            href="{{ route('products.create') }}">নতুন পণ্য</a></li>
                                @endcan
                                @can('product-model-list')
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item {{ request()->is('product-models') ? 'active' : '' }}"
                                            href="{{ route('models.index') }}">মডেল তালিকা</a></li>
                                @endcan
                                @can('product-model-create')
                                    <li><a class="dropdown-item {{ request()->is('product-models/create') ? 'active' : '' }}"
                                            href="{{ route('models.create') }}">নতুন মডেল</a></li>
                                @endcan
                            </ul>
                        </li>

                        {{-- রিপোর্ট --}}
                        @role('admin')
                            <li class="nav-item dropdown {{ request()->is('reports/*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">রিপোর্ট</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item {{ request()->is('reports/monthly') ? 'active' : '' }}"
                                            href="{{ route('monthly.reports') }}">মাসিক রিপোর্ট</a></li>
                                </ul>
                            </li>
                        @endrole
                    </ul>
                @endauth

                {{-- Right side --}}
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-semibold" href="#" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">লগআউট</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth

                    @guest
                        <li class="nav-item"><a class="nav-link fw-semibold" href="{{ route('login') }}">লগইন</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="container mt-3">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-light text-center py-3">
        <small>© {{ date('Y') }} রোমান ইলেকট্রনিক্স ও ফার্নিচার</small>
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    @yield('scripts')

</body>

</html>
