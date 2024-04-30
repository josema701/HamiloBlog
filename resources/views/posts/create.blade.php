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
    <form action="{{ url('/posts/registrar') }}" method="POST" enctype="multipart/form-data" >
        @csrf
        <div class="container-fluid">
            @include('includes.alertas')
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card m-1">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="imagen">Imagen</label>
                                <input type="file" name="imagen" value="{{ old('imagen') }}" class="form-control">
                                @error('imagen') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <label for="titulo">Titulo</label>
                                <input type="text" name="titulo" value="{{ old('titulo') }}" class="form-control">
                                @error('titulo') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card m-1">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="categoria_id">Categoría</label>
                                <select name="categoria_id" id="categoria_id" class="form-control">
                                    <option value="">Seleccione...</option>
                                    @foreach ($categorias as $cate)
                                        <option value="{{ $cate->id }}">{{ $cate->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('categoria_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label for="tags">#Tags</label>
                                <select name="tags" id="tags" class="form-control" multiple>
                                    <option value="">Seleccione...</option>
                                    @foreach ($tags as $ta)
                                        <option value="{{ $ta->nombre }}">{{ $ta->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('tags') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card m-1">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="fecha_publicacion">Fecha publicación</label>
                                <input type="datetime-local" name="fecha_publicacion" class="form-control">
                                @error('fecha_publicacion') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group mt-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="estado" class="custom-control-input" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1">Publicar?</label>
                                    @error('estado') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="text-center">
                                <a href="{{ url('/posts') }}" class="btn btn-primary ">Volver al listado</a>
                                <button type="submit" class="btn btn-success">Registrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card m-1">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="resumen">Resumen del post</label>
                                <textarea name="resumen" id="resumen" cols="30" rows="3" class="form-control"></textarea>
                                @error('resumen') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card m-1">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="contenido">Contenido del post</label>
                                <textarea name="contenido" id="contenido" cols="30" rows="15" class="form-control"></textarea>
                                @error('contenido') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>

@endsection
