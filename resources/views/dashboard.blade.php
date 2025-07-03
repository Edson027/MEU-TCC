@extends('Template.app')


@section('content')



  <p><strong>Tipo de Usuário:</strong> {{Auth::user()->type}}</p>

@endsection

