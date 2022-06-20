<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{asset('/images/mainLogo.ico')}}" alt="logo" class="navbar-brand" width="45" height="50">
  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  @stack('style')
</head>

<body>
  <div id="main">
    @include('inc.navbar')
    @include('inc.messages')
    <main class="py-4 container mx-auto">
      @yield('content')
    </main>
  </div>
  <script>
    // This code is for setting timer for the messages that appear when needed
    setTimeout(function() {
      $('#alert-success').alert('close');
    }, 3000);

    // needed for the side menu to show and hide
    function openNav() {
      $("#mySidenav").css("width", "250px");
      $("#main").css("margin-left", "250px");
    }

    function closeNav() {
      $("#mySidenav").css("width", "0");
      $("#main").css("margin-left", "0");
    }
  </script>
  @stack('script')
</body>

</html>
