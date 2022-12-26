@extends('base-components.base')
@section('title', 'User Management')

@section('body')
  @parent
  <pg-user-man
  app-name="{{ config('app.name') }}"
  greetings="{{ Auth::user()?->name }}"
  ></pg-user-man>
@endsection