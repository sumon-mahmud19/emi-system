@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Roles') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <h1>Roles List:</h1>

                        <div class="table-responsive">
                            <table class="table">

                                @can('role-create')
                                    <a href="{{ route('roles.create') }}" class="mb-3 btn btn-success">Create Role</a>
                                @endcan

                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr class="">
                                            <td scope="row">{{ $loop->iteration }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td class="d-flex">

                                                @can('role-edit')
                                                    <a href="{{ route('roles.edit', $role->id) }}"
                                                        class="btn btn-success">Edit</a>
                                                @endcan

                                                @can('role-show')
                                                    <a href="{{ route('roles.show', $role->id) }}"
                                                        class="btn btn-primary mx-2">View</a>
                                                @endcan

                                                @can('role-delete')
                                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                        onsubmit="return confirm('আপনি কি নিশ্চিতভাবে ডিলিট করতে চান?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="submit" value="Delete" class="btn btn-danger">
                                                    </form>
                                                @endcan

                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                            {{ $roles->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
