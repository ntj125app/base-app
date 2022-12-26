@extends('base-components.base')
@section('title', 'Server Log')

@section('body')
  @parent
  <pg-server-log
  app-name="{{ config('app.name') }}"
  greetings="{{ Auth::user()?->name }}"
  ></pg-server-log>
@endsection