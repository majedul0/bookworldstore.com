<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta charset="utf-8">
  <title>White North Inc</title>
  {{-- <meta name="description" content=""> --}}
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Icons -->
  <link rel="shortcut icon" href="https://placehold.it/32x32">

  <link rel="stylesheet" href="{{asset('back/css/normalize.css')}}">
  <link rel="stylesheet" href="{{asset('back/css/main.css')}}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="{{asset('back/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('back/css/responsive.css')}}">

  <!-- fontawesome -->
  <script src="https://kit.fontawesome.com/9c65216417.js" crossorigin="anonymous"></script>

  <meta name="theme-color" content="#fafafa">

  @yield('head')

  <style>
      .main{min-height: 100vh;background-color: var(--primary_color)}
  </style>
</head>

<body>
  <div class="main">
    <div class="container">

        <div class="text-center pt-5">
            <img class="pt-5" src="https://i.postimg.cc/TwcShy7Q/screenshot-5.png" alt="">
        </div>

        @yield('master')

        <div class="text-center mt-5">
            <p class="mb-0">Copyright &copy; {{date('Y')}}. All right reserved</p>
        </div>
    </div>
  </div>

  <script src="{{asset('back/js/vendor/modernizr-3.11.2.min.js')}}"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <script src="{{asset('back/js/plugins.js')}}"></script>
  <script src="{{asset('back/js/main.js')}}"></script>

  @yield('footer')
</body>

</html>
