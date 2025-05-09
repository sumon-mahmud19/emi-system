<!doctype html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Roman Electronic & Furnitures @yield('title')</title>

    {{-- Flowbite & Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    {{-- Bangla Font --}}
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">

    {{-- Custom Styles --}}
    <style>
        body {
            font-family: 'SolaimanLipi', sans-serif;
        }

        .navbar-nav .nav-link.active {
            font-weight: bold;
            color: #fff !important;
        }
    </style>
</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Roman System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                {{-- Left Menu --}}
                @auth
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        {{-- কাস্টমার --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">কাস্টমার</a>
                            <ul class="dropdown-menu">
                                @can('customer-list')
                                    <li><a class="dropdown-item" href="{{ route('customers.index') }}">সব কাস্টমার</a></li>
                                @endcan
                                @can('customer-create')
                                    <li><a class="dropdown-item" href="{{ route('customers.create') }}">নতুন কাস্টমার</a></li>
                                @endcan
                            </ul>
                        </li>

                        {{-- পারচেস --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">পারচেস</a>
                            <ul class="dropdown-menu">
                                @can('purchase-list')
                                    <li><a class="dropdown-item" href="{{ route('purchases.index') }}">সব পারচেস</a></li>
                                @endcan
                                @can('purchase-create')
                                    <li><a class="dropdown-item" href="{{ route('purchases.create') }}">নতুন পারচেস</a></li>
                                @endcan
                            </ul>
                        </li>

                        {{-- লোকেশন --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">লোকেশন</a>
                            <ul class="dropdown-menu">
                                @can('location-list')
                                    <li><a class="dropdown-item" href="{{ route('locations.index') }}">সব লোকেশন</a></li>
                                @endcan
                                @can('location-create')
                                    <li><a class="dropdown-item" href="{{ route('locations.create') }}">নতুন লোকেশন</a></li>
                                @endcan
                            </ul>
                        </li>

                        {{-- ইউজার --}}
                        @can('user-list')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">ইউজার</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('users.index') }}">সব ইউজার</a></li>
                                    @can('user-create')
                                        <li><a class="dropdown-item" href="{{ route('users.create') }}">নতুন ইউজার</a></li>
                                    @endcan
                                    @can('role-list')
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('roles.index') }}">রোল ম্যানেজমেন্ট</a></li>
                                    @endcan
                                    @can('role-create')
                                        <li><a class="dropdown-item" href="{{ route('roles.create') }}">নতুন রোল</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan

                        {{-- পণ্য --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">পণ্য</a>
                            <ul class="dropdown-menu">
                                @can('product-list')
                                    <li><a class="dropdown-item" href="{{ route('products.index') }}">সব পণ্য</a></li>
                                @endcan
                                @can('product-create')
                                    <li><a class="dropdown-item" href="{{ route('products.create') }}">নতুন পণ্য</a></li>
                                @endcan
                                @can('product-model-list')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('models.index') }}">মডেল তালিকা</a></li>
                                @endcan
                                @can('product-model-create')
                                    <li><a class="dropdown-item" href="{{ route('models.create') }}">নতুন মডেল</a></li>
                                @endcan
                            </ul>
                        </li>

                        {{-- রিপোর্ট --}}
                        @role('admin')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">রিপোর্ট</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('monthly.reports') }}">মাসিক রিপোর্ট</a></li>
                                </ul>
                            </li>
                        @endrole
                    </ul>
                @endauth

                {{-- Right Menu --}}
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item">লগআউট</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth

                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">লগইন</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="container mt-4 mb-5">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-light text-center py-3 mt-auto">
        <small>© {{ date('Y') }} রোমান ইলেকট্রনিক্স ও ফার্নিচার</small>
    </footer>

    {{-- Scripts --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @stack('scripts')
    @yield('scripts')
</body>

</html>
