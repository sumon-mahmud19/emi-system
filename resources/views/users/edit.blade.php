@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h1>Edit user:</h1>

                        <div class="table-responsive">
                            <table class="table table-primary">

                                @if (Session::has('errors'))
                                    <div class="alert alert-danger" role="alert">
                                        <strong>Error:</strong> {{ Session::get('errors') }}
                                    </div>
                                @endif

                                <a href="{{ route('users.index') }}" class="mb-3 btn btn-success">Return</a>

                                <form action="{{ route('users.update', $users->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required
                                            value="{{ old('name', $users->name) }}">
                                    </div>

                                    @error('name')
                                        <div class="alert alert-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required
                                            value="{{ old('email', $users->email) }}">
                                    </div>

                                    @error('email')
                                        <div class="alert alert-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>

                                    @error('password')
                                        <div class="alert alert-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="mb-3">
                                        <label>Roles:</label>
                                        <select name="roles[]" id="" multiple class="form-select">
                                            
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}" {{ $users->hasRole($role->name) ? 'selected':''}}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update User</button>
                                </form>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection