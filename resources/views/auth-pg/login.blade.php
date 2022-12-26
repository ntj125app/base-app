@extends('base-components.base')
@section('title', 'Login')

@section('body')
  @parent
  <pg-login
  app-name="{{ config('app.name') }}"
  ></pg-login>
@endsection