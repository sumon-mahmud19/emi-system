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

                        <h1>Create role:</h1>

                        <div class="table-responsive">
                            <table class="table table-primary">

                                @if (Session::has('errors'))
                                    <div class="alert alert-danger" role="alert">
                                        <strong>Error:</strong> {{ Session::get('errors') }}
                                    </div>
                                @endif

                                <a href="{{ route('roles.index') }}" class="mb-3 btn btn-success">Return</a>

                                <form action="{{ route('roles.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required
                                            value="{{ old('name') }}">
                                    </div>

                                    @error('name')
                                        <div class="alert alert-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror


                                    <div class="mb-3">
                                        <h2>Permissions:</h2>
                                        @foreach ($permissions as $permission)
                                            <label><input type="checkbox" name="permissions[{{$permission->name}}]" value="{{$permission->name}}"> {{ $permission->name }} </label><br>
                                        @endforeach
                                    </div>

                                    <button type="submit" class="btn btn-primary">Create role</button>
                                </form>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection