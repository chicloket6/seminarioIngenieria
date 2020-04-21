@extends('errors.layout')

@php
  $error_number = 403;
@endphp

@section('title')
  Prohibido el acceso, su rol no permite seguir
@endsection

@section('description')
  @php
    $default_error_message = "Porfavor <a href='javascript:history.back()''>vaya atras</a> o regrese a <a href='".url('')."'>inicio</a>.";
  @endphp
  {!! isset($exception)? ($exception->getMessage()?$exception->getMessage():$default_error_message): $default_error_message !!}
@endsection