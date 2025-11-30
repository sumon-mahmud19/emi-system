@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Role') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h1>edit role:</h1>

                        <div class="table-responsive">
                            <table class="table table-primary">

                                @if (Session::has('errors'))
                                    <div class="alert alert-danger" role="alert">
                                        <strong>Error:</strong> {{ Session::get('errors') }}
                                    </div>
                                @endif

                                <a href="{{ route('roles.index') }}" class="mb-3 btn btn-success">Back</a>

                                <form action="{{ route('roles.update', $roles->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required
                                            value="{{ old('name', $roles->name) }}">
                                    </div>

                                    @error('name')
                                        <div class="alert alert-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror


                                    <div class="mb-3">
                                        <h2>Permissions:</h2>
                                        @foreach ($permissions as $permission)
                                            <label><input type="checkbox" name="permissions[{{$permission->name}}]" value="{{$permission->name}}" {{ $roles->hasPermissionTo($permission->name) ? 'checked':'' }}> {{ $permission->name }} </label><br>
                                        @endforeach
                                    </div>

                                    <button type="submit" class="btn btn-primary">update role</button>
                                </form>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection