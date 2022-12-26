@extends('base-components.base')
@section('title', 'Profile')

@section('body')
  @parent
  <pg-profile
  app-name="{{ config('app.name') }}"
  greetings="{{ Auth::user()?->name }}"
  user-name="{{ Auth::user()?->name }}"
  ></pg-profile>
@endsection