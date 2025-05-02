<!doctype html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Roman Electronic & Furnitures
        @yield('title')
    </title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bangla Font --}}
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">

    <style>
        body {
            font-family: 'SolaimanLipi', sans-serif;
        }
    </style>
</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Roman Emi System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">

                {{-- Left Menu --}}
                @auth
                    <ul class="navbar-nav me-auto">
                        {{-- কাস্টমার --}}
                        <li class="nav-item dropdown {{ Route::is('customers.*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" id="customerDropdown"
                                data-bs-toggle="dropdown">কাস্টমার</a>
                            <ul class="dropdown-menu">
                                @can('customer-list')
                                    <li><a class="dropdown-item {{ Route::is('customers.index') ? 'active' : '' }}"
                                            href="{{ route('customers.index') }}">সব কাস্টমার</a></li>
                                @endcan
                                @can('customer-create')
                                    <li><a class="dropdown-item {{ Route::is('customers.create') ? 'active' : '' }}"
                                            href="{{ route('customers.create') }}">নতুন কাস্টমার</a></li>
                                @endcan
                            </ul>
                        </li>

                        {{-- পারচেস --}}
                        <li class="nav-item dropdown {{ Route::is('purchases.*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" id="purchaseDropdown"
                                data-bs-toggle="dropdown">পারচেস</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item {{ Route::is('purchases.index') ? 'active' : '' }}"
                                        href="{{ route('purchases.index') }}">সব পারচেস</a></li>
                                <li><a class="dropdown-item {{ Route::is('purchases.create') ? 'active' : '' }}"
                                        href="{{ route('purchases.create') }}">নতুন পারচেস</a></li>
                            </ul>
                        </li>

                        {{-- লোকেশন --}}
                        <li class="nav-item dropdown {{ Route::is('locations.*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" id="locationDropdown"
                                data-bs-toggle="dropdown">লোকেশন</a>
                            <ul class="dropdown-menu">
                                @can('location-list')
                                    <li><a class="dropdown-item {{ Route::is('locations.index') ? 'active' : '' }}"
                                            href="{{ route('locations.index') }}">সব লোকেশন</a></li>
                                @endcan
                                @can('location-create')
                                    <li><a class="dropdown-item {{ Route::is('locations.create') ? 'active' : '' }}"
                                            href="{{ route('locations.create') }}">নতুন লোকেশন</a></li>
                                @endcan
                            </ul>
                        </li>

                        {{-- ইউজার --}}
                        <li class="nav-item dropdown {{ Route::is('users.*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown"
                                data-bs-toggle="dropdown">ইউজার</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item {{ Route::is('users.index') ? 'active' : '' }}"
                                        href="{{ route('users.index') }}">সব ইউজার</a></li>
                                <li><a class="dropdown-item {{ Route::is('users.create') ? 'active' : '' }}"
                                        href="{{ route('users.create') }}">নতুন ইউজার</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <a class="dropdown-item {{ Route::is('roles.index') ? 'active' : '' }}"
                                    href="{{ route('roles.index') }}">রোল ম্যানেজমেন্ট</a>
                                <a class="dropdown-item {{ Route::is('roles.create') ? 'active' : '' }}"
                                    href="{{ route('roles.create') }}">নতুন রোল</a>
                            </ul>
                        </li>

                        {{-- পণ্য --}}
                        <li
                            class="nav-item dropdown {{ Route::is('products.*') || Route::is('product-models.*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" id="productDropdown"
                                data-bs-toggle="dropdown">পণ্য</a>
                            <ul class="dropdown-menu">
                                @can('product-list')
                                    <li><a class="dropdown-item {{ Route::is('products.index') ? 'active' : '' }}"
                                            href="{{ route('products.index') }}">সব পণ্য</a></li>
                                @endcan
                                @can('product-create')
                                    <li><a class="dropdown-item {{ Route::is('products.create') ? 'active' : '' }}"
                                            href="{{ route('products.create') }}">নতুন পণ্য</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endcan
                                @can('product-model-list')
                                    <li><a class="dropdown-item {{ Route::is('product-models.index') ? 'active' : '' }}"
                                            href="{{ route('models.index') }}">মডেল তালিকা</a></li>
                                @endcan
                                @can('product-model-create')
                                    <li><a class="dropdown-item {{ Route::is('product-models.create') ? 'active' : '' }}"
                                            href="{{ route('models.create') }}">নতুন মডেল</a></li>
                                @endcan
                            </ul>
                        </li>

                        {{-- রিপোর্ট --}}
                        <li class="nav-item dropdown {{ Request::is('reports/*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" id="reportDropdown"
                                data-bs-toggle="dropdown">রিপোর্ট</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item {{ Request::is('reports/monthly') ? 'active' : '' }}"
                                        href="{{ route('monthly.reports') }}">মাসিক রিপোর্ট</a></li>
                            </ul>
                        </li>
                    </ul>
                @endauth

                {{-- Right-side Auth Menu --}}
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="authDropdown" data-bs-toggle="dropdown">
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
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">লগইন</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">রেজিস্টার</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>


    {{-- Main Content --}}
    <main class="container mb-5">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-light text-center py-3">
        <small>© {{ date('Y') }} রোমান ইলেকট্রনিক্স ও ফার্নিচার</small>
    </footer>

    {{-- Bootstrap Bundle JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Stack/Extra Scripts --}}
    @stack('scripts')
    @yield('scripts')

</body>

</html>
