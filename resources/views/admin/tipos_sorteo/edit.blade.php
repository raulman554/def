@extends('layouts.admin')

@section('title', 'Editar Tipo de Sorteo')

@section('header', 'Editar Tipo de Sorteo')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Datos del Tipo de Sorteo</h5>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tipos-sorteo.update', $tipo) }}">
            @method('PUT')
            @include('admin.tipos_sorteo._form', ['tipo'=>$tipo])
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar Tipo de Sorteo
                </button>
                <a href="{{ route('tipos-sorteo.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection