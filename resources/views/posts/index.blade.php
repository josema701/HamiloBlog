@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Posts</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Posts</li>
                </ol>
            </div>
        </div>
    </div>
</div>


<div class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">

                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ url('/posts/registrar') }}" class="btn btn-primary btn-sm">Nuevo post</a>
                    </div>
                </div>

                @include('includes.alertas')

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>IMAGEN</th>
                                <th>TITULO</th>
                                <th>FECHA PUBLICACIÓN</th>
                                <th>ESTADO</th>
                                <th>USUARIO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($blogs as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <img src="{{ $item->getImagenUrl() }}" alt="" height="40px" >
                                    </td>
                                    <td>{{ $item->titulo }}</td>
                                    <td>
                                        {{ $item->fecha_publicacion }} <br>
                                        <small>{{ \Carbon\Carbon::parse($item->fecha_publicacion)->diffForHumans(now()) }}</small>
                                    </td>
                                    <td>
                                        @if ($item->estado == true)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->usuario->name }}</td>
                                    <td>
                                        <a href="{{ url('/posts/ver/' . $item->id) }}" class="btn btn-dark btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ url('/posts/actualizar/' . $item->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if ($item->estado == true)
                                            <a href="{{ url('/posts/estado/' . $item->id) }}" class="btn btn-danger btn-sm">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        @else
                                            <a href="{{ url('/posts/estado/' . $item->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $blogs->links("pagination::bootstrap-5") }}
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
