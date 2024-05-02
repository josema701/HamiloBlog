@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Usuarios</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Usuarios</li>
                </ol>
            </div>
        </div>
    </div>
</div>


<div class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-body">

                @include('includes.alertas')

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NOMBRE</th>
                                <th>EMAIL</th>
                                <th>FECHA REGISTRO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        {{ $item->created_at }} <br>
                                        <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans(now()) }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $usuarios->links("pagination::bootstrap-5") }}
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
