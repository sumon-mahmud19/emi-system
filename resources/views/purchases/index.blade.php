@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">সকল গ্রাহকের EMI</h2>

            @can('purchase-create')
                <a href="{{ route('purchases.create') }}" class="btn btn-success">নতুন ক্রয়</a>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h5 class="mb-4">মোট ক্রয়: <strong>{{ $totalPurchases }}</strong></h5>

        @forelse ($customers as $customer)
            <div class="card mb-4 shadow rounded-4 border-0">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0">
                        <i class="bi bi-person-fill"></i> {{ $customer->customer_name }}
                        <small class="ms-2 text-light">({{ $customer->location->name ?? 'লোকেশন নেই' }})</small>
                    </h5>
                </div>
                <div class="card-body bg-light">
                    @forelse ($customer->purchases as $purchase)
                        <div class="mb-4 border rounded p-3 bg-white">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-1"><i class="bi bi-box-seam"></i> {{ $purchase->product->product_name ?? 'পণ্য নেই' }}</h6>
                                    <p class="mb-0 text-muted">
                                        মোট: <strong>{{ number_format($purchase->total_price, 2) }} ৳</strong>,
                                        ডাউন: <strong>{{ number_format($purchase->down_payment, 2) }} ৳</strong>,
                                        EMI: <strong>{{ $purchase->emi_plan }} মাস</strong>
                                    </p>
                                </div>
                                <div>
                                    @can('purchase-edit')
                                        <a href="{{ route('purchases.edit', $purchase->id) }}"
                                           class="btn btn-sm btn-outline-primary">এডিট</a>
                                    @endcan
                                    @can('purchase-delete')
                                        <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">ডিলিট</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>

                            {{-- EMI Section --}}
                            <div class="accordion" id="accordionEMI{{ $purchase->id }}">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="heading{{ $purchase->id }}">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $purchase->id }}"
                                                aria-expanded="false" aria-controls="collapse{{ $purchase->id }}">
                                            <i class="bi bi-calendar-week me-2"></i> EMI বিস্তারিত দেখুন
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $purchase->id }}" class="accordion-collapse collapse"
                                         aria-labelledby="heading{{ $purchase->id }}"
                                         data-bs-parent="#accordionEMI{{ $purchase->id }}">
                                        <div class="accordion-body">
                                            @if ($purchase->installments->isEmpty())
                                                <p class="text-muted">এই ক্রয়ের কোনো EMI নেই।</p>
                                            @else
                                                <div class="row row-cols-1 row-cols-md-3 g-3">
                                                    @foreach ($purchase->installments as $installment)
                                                        <div class="col">
                                                            <div class="card h-100 border {{ $installment->is_paid ? 'border-success' : 'border-danger' }}">
                                                                <div class="card-body">
                                                                    <h6 class="card-subtitle mb-2 text-muted">
                                                                        {{ \Carbon\Carbon::parse($installment->due_date)->format('F Y') }}
                                                                    </h6>
                                                                    <p class="fw-bold mb-1">{{ number_format($installment->amount, 2) }} ৳</p>
                                                                    <span class="badge {{ $installment->is_paid ? 'bg-success' : 'bg-danger' }}">
                                                                        {{ $installment->is_paid ? 'পরিশোধিত' : 'বাকি' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @empty
                        <p class="text-muted">এই গ্রাহকের কোনো ক্রয় পাওয়া যায়নি।</p>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="alert alert-info">কোনো গ্রাহক পাওয়া যায়নি।</div>
        @endforelse
    </div>
@endsection
