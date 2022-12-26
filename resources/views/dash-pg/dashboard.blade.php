@extends('base-components.base')
@section('title', 'Dashboard')

@section('body')
  @parent
  <pg-dashboard
  app-name="{{ config('app.name') }}"
  greetings="{{ Auth::user()?->name }}"
  ></pg-dashboard>
@endsection