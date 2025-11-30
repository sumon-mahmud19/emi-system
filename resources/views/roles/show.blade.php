@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Role Details Page') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="container">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1>Role Informations:</h1>

                                    <div class="card border">
                                        <div class="card-body">
                                            <h4 class="card-title">
                                                {{ $roles->name }}
                                            </h4>
                                            @foreach ($roles->permissions as $permission)
                                                <p class="card-text">
                                                    {{ $permission->name }}

                                                </p>
                                            @endforeach
                                        </div>
                                    </div>

                                    @can('role-list')
                                        <div class="my-3 ">
                                            <a href="{{ route('roles.index') }}" class="btn btn-info">Back</a>
                                        </div>
                                    @endcan

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
