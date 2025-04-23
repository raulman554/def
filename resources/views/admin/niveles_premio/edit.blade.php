@extends('layouts.admin')
@section('header', 'Editar Premio (Nivel ' . $nivel->nivel . ')')
@section('content')
<div class="mb-3">
  <a href="{{ route('tipos-sorteo.niveles-premio.index', $tipo) }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver a Premios
  </a>
</div>

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('tipos-sorteo.niveles-premio.update', [$tipo,$nivel]) }}">
      @csrf @method('PUT')
      @include('admin.niveles_premio._form', ['nivel'=>$nivel])
      <div class="mt-4">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle"></i> Actualizar Premio
        </button>
      </div>
    </form>
  </div>
</div>
@endsection