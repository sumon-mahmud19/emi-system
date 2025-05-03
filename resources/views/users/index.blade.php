@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <h1>Users List:</h1>

                        <div class="table-responsive">
                            <table class="table">

                                @can('user-create')
                                    <a href="{{ route('users.create') }}" class="mb-3 btn btn-success">Create User</a>
                                @endcan

                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Roles</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr class="">
                                            <td scope="row">{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @foreach ($user->getRoleNames() as $role)
                                                    <button class="btn btn-outline-secondary">{{ $role }}</button>
                                                @endforeach
                                            </td>
                                            <td class="d-flex">

                                                @can('user-edit')
                                                    <a href="{{ route('users.edit', $user->id) }}"
                                                        class="btn btn-success">Edit</a>
                                                @endcan

                                                <a href="{{ route('users.show', $user->id) }}"
                                                    class="btn btn-primary mx-2">View</a>

                                                @can('user-delete')
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="post">
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

                            {{ $users->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
