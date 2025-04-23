@extends('layouts.admin')
@section('header', 'Nuevo Tipo de Sorteo')
@section('content')

<div class="mb-3">
  <a href="{{ route('tipos-sorteo.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver a Lista
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
    <form method="POST" action="{{ route('tipos-sorteo.store') }}">
      @include('admin.tipos_sorteo._form')
      <div class="mt-4">
        <button type="submit" class="btn btn-success">
          <i class="bi bi-check-circle"></i> Guardar Tipo de Sorteo
        </button>
      </div>
    </form>
  </div>
</div>
@endsection