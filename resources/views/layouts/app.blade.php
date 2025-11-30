<!doctype html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Roman Electronic & Furnitures @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Bangla Font -->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <style>
        body {
            font-family: 'solaimanlipi', sans-serif;
        }

        :root {
            /* Primary (Tailwind Blue) */
            --bs-primary: #222222;
            /* blue-500 */
            --bs-primary-hover: #000;
            /* blue-600 */

            /* Success (Tailwind Green) */
            --bs-success: #10b981;
            /* emerald-500 */
            --bs-success-hover: #059669;
            /* emerald-600 */

            /* Info (Tailwind Sky) */
            --bs-info: #0ea5e9;
            /* sky-500 */
            --bs-info-hover: #0284c7;
            /* sky-600 */

            /* Warning (Tailwind Yellow) */
            --bs-warning: #f59e0b;
            /* amber-500 */
            --bs-warning-hover: #d97706;
            /* amber-600 */

            /* Danger (Tailwind Red) */
            --bs-danger: #ef4444;
            /* red-500 */
            --bs-danger-hover: #dc2626;
            /* red-600 */
        }

        /* Bootstrap class overrides using Tailwind colors */
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: var(--bs-primary-hover);
            border-color: var(--bs-primary-hover);
        }

        .btn-success {
            background-color: var(--bs-success);
            border-color: var(--bs-success);
        }

        .btn-success:hover,
        .btn-success:focus,
        .btn-success:active {
            background-color: var(--bs-success-hover);
            border-color: var(--bs-success-hover);
        }

        .btn-info {
            background-color: var(--bs-info);
            border-color: var(--bs-info);
        }

        .btn-info:hover,
        .btn-info:focus,
        .btn-info:active {
            background-color: var(--bs-info-hover);
            border-color: var(--bs-info-hover);
        }

        .btn-warning {
            background-color: var(--bs-warning);
            border-color: var(--bs-warning);
        }

        .btn-warning:hover,
        .btn-warning:focus,
        .btn-warning:active {
            background-color: var(--bs-warning-hover);
            border-color: var(--bs-warning-hover);
        }

        .btn-danger {
            background-color: var(--bs-danger);
            border-color: var(--bs-danger);
        }

        .btn-danger:hover,
        .btn-danger:focus,
        .btn-danger:active {
            background-color: var(--bs-danger-hover);
            border-color: var(--bs-danger-hover);
        }
    </style>
</head>

<body>

    <!-- Scrolling Notices Bar -->
    <div class="bg-warning py-2 px-2 shadow-sm" style="overflow: hidden;">
        <div class="container position-relative d-flex align-items-center">

            <div class="scrolling-text">
                @foreach ($notices as $notice)
                    <h4 class="me-5">{{ $notice->name }}</h4>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Scrolling Text CSS -->
    <style>
        .scrolling-text {
            display: inline-block;
            white-space: nowrap;
            animation: scrollText 20s linear infinite;
            padding-left: 100%;
        }

        @keyframes scrollText {
            0% {
                transform: translateX(0%);
            }

            100% {
                transform: translateX(-100%);
            }
        }
    </style>


    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top"
        style="background-color: var(--bs-primary); border-radius: 0 0 12px 12px;">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    @yield('scripts')

</body>

</html>
