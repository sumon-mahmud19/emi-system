
<style>
    .alert {
        animation: slideFade 0.5s ease-out;
    }

    @keyframes slideFade {
        0% {
            opacity: 0;
            transform: translateY(-10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>


@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm rounded-3 px-4 py-3 mt-3" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm rounded-3 px-4 py-3 mt-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
