<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- <link rel="icon" type="image/webp" href="{{ asset('mix-images/icon.webp') }}"/> --}}

  <title>@yield('title')</title>
  </head>

  <body>
  @section('base')
  <noscript>
    <div class="grid content-center w-screen h-screen bg-slate-200">
    <div class="flex justify-center">
      <div class="bg-white rounded-lg p-1">
      <div class="m-auto antialiased p-5 text-9xl text-center">&#128245</div>
      <div class="m-auto antialiased pt-5 text-center">Error! JavaScript tidak aktif</div>
      <div class="m-auto antialiased pb-5 text-center">App ini memerlukan JavaScript agar dapat berfungsi</div>
      </div>
    </div>
    </div>
  </noscript>
  <div id="app" class="base-body">
    <cmp-app-set></cmp-app-set>
    @section('body')
    @show
  </div>
  @show
  
  @section('font')
    <style>
    @import url('https://fonts.bunny.net/css?family=Lato&family=Raleway&display=swap');
    </style>
  @show

  @section('css')
  @show
  
  @section('script')
    @vite(['resources/ts/app.ts'])
    
    @includeWhen(config('ga.enable'), 'base-components.ga')
  @show
  </body>
</html>