@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>সব লোকেশন</h5>

            @can('location-create')
                <a href="{{ route('locations.create') }}" class="btn btn-sm btn-success">নতুন লোকেশন</a>
            @endcan

        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>আইডি</th>
                        <th>লোকেশন নাম</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $location)
                        <tr>
                            <td>{{ $location->id }}</td>
                            <td>{{ $location->name }}</td>
                            <td>
                                @can('location-edit')
                                    <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-sm btn-primary">এডিট</a>
                                @endcan

                                @can('location-delete')
                                    <form action="{{ route('locations.destroy', $location->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে মুছতে চান?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">ডিলিট</button>
                                    </form>
                                @endcan

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">কোনো লোকেশন পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
