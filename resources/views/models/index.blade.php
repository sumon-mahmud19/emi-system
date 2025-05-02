@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>সব পণ্যের মডেল</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3">
            @can('product-model-create')
                <a href="{{ route('models.create') }}" class="btn btn-success">নতুন মডেল যোগ করুন</a>
            @endcan
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>পণ্যের নাম</th>
                    <th>মডেল নাম</th>
                    <th>তৈরির সময়</th>
                    <th>অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($models as $model)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $model->product->product_name }}</td>
                        <td>{{ $model->model_name }}</td>
                        <td>{{ $model->created_at->format('d-m-Y') }}</td>
                        <td>

                            @can('product-model-edit')
                                <a href="{{ route('models.edit', $model->id) }}" class="btn btn-sm btn-primary">এডিট</a>
                            @endcan

                            @can('product-model-delete')
                                <form action="{{ route('models.destroy', $model->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('আপনি কি নিশ্চিত?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">ডিলিট</button>
                                </form>
                            @endcan

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">কোনো মডেল পাওয়া যায়নি।</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
