<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>@yield('title') - {{ config('app.name', 'Bona') }}</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <link href="{{ asset('assets/backend/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/backend/plugins/node-waves/waves.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/backend/plugins/animate-css/animate.css') }}" rel="stylesheet" />

    <!-- DataTables -->
    <link href="{{asset('assets/backend')}}/plugins/datatables/new/datatables.min.css" rel="stylesheet" type="text/css" />

    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset('assets/backend')}}/css/toastr.min.css">

    @stack('css')
    <link href="{{ asset('assets/backend/css/themes/all-themes.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/backend/css/style.css') }}" rel="stylesheet">

</head>

<body class="theme-red">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    @include('layouts.backend.partial.navbar')
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        @include('layouts.backend.partial.sidebar')
        <!-- #END# Left Sidebar -->
    </section>

    @yield('content')

    <!-- Jquery Core Js -->
    <script src="{{ asset('assets/backend/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/node-waves/waves.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-countto/jquery.countTo.js') }}"></script>


    <!-- Data Tables -->
    {{-- <script src="{{ asset('assets/backend/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script> --}}
    <script src="{{asset('assets/backend')}}/plugins/datatables/new/jquery.dataTables.min.js"></script>
    <script src="{{asset('assets/backend')}}/plugins/datatables/new/datatables.min.js"></script>


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

    @stack('js')
    <!-- ChartJs -->
    <script src="{{ asset('assets/backend/plugins/chartjs/Chart.bundle.js') }}"></script>
    <!-- Flot Charts Plugin Js -->
    <script src="{{ asset('assets/backend/plugins/flot-charts/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/flot-charts/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/flot-charts/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/flot-charts/jquery.flot.categories.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/flot-charts/jquery.flot.time.js') }}"></script>
    <!-- Sparkline Chart Plugin Js -->
    <script src="{{ asset('assets/backend/plugins/jquery-sparkline/jquery.sparkline.js') }}"></script>
    <!-- Custom Js -->
    <script src="{{ asset('assets/backend/js/admin.js') }}"></script>

    {{-- <script src="{{ asset('assets/backend/js/pages/tables/jquery-datatable.js') }}"></script> --}}
    <script>
        // $(function () {
        //     $('#dataTable').DataTable({
        //         responsive: true
        //     });

        //     $('.js-basic-example').DataTable({
        //         responsive: true
        //     });

        //     //Exportable table
        //     $('.js-exportable').DataTable({
        //         dom: 'Bfrtip',
        //         responsive: true,
        //         buttons: [
        //             'copy', 'csv', 'excel', 'pdf', 'print'
        //         ]
        //     });
        // });
    </script>


    <script src="{{ asset('assets/backend/js/pages/index.js') }}"></script>
    <!-- Demo Js -->
    <script src="{{ asset('assets/backend/js/demo.js') }}"></script>

</body>
</html>
