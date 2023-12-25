<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Bona') }}</title>

    <!-- Font -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">
	<!-- Stylesheets -->
    <link href="{{ asset('assets/frontend/css/bootstrap.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/frontend/css/ionicons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/swiper.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('assets/frontend/css/toastr.min.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{asset('assets/backend')}}/css/toastr.min.css">
    @stack('css')
    {{-- <link href="{{ asset('assets/frontend/css/home/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/home/responsive.css') }}" rel="stylesheet"> --}}



</head>
<body>

    @include('layouts.frontend.partial.header')

	@yield('content')

    @include('layouts.frontend.partial.footer')

    <!-- SCRIPTS -->
    <script src="{{ asset('assets/frontend/js/jquery-3.1.1.min.js') }}"></script>
	<script src="{{ asset('assets/frontend/js/tether.min.js') }}"></script>
	<script src="{{ asset('assets/frontend/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>

    {{-- Toastr --}}
    {{-- <script src="{{ asset('assets/frontend/js/toastr.min.js') }}"></script> --}}

    <script src="{{ asset('assets/backend') }}/js/toastr.min.js"></script>

    @if(Session::has('success'))
        <script>
            $.toast({
                heading: 'Success',
                text: "{{ session('success') }}",
                closeButton: true,
                progressBar: true,
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
        </script>

    @endif
    @if(Session::has('error'))
        <script>
            $.toast({
                heading: 'Error',
                text: "{{ session('error') }}",
                closeButton: true,
                progressBar: true,
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 3000,
                stack: 6
            });
        </script>
    @endif
    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                $.toast({
                    heading: '{{ $error }}',
                    text: "{{ session('error') }}",
                    position: 'top-right',
                    loaderBg:'#ff6849',
                    icon: 'error',
                    hideAfter: 3000,
                    stack: 6
                });
            @endforeach
        @endif
    </script>

	<script src="{{ asset('assets/frontend/js/scripts.js') }}"></script>
    @stack('js')

</body>
</html>
