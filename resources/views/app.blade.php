<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-navbar-fixed layout-menu-fixed">
@php
$settings = [
'app_name' => App\Models\Settings::where(['key' => 'app_name'])->first()->value,
'app_description' => App\Models\Settings::where(['key' => 'app_description'])->first()->value,
'app_keywords' => '',
'app_author' => '',
'app_favicon' => App\Models\Settings::where(['key'=>'app_favicon'])->first()->value,
'app_logo' => '',
];
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title inertia>Admin Panel - {{ $settings['app_name'] }}</title>
    <link rel="icon" type="image/x-icon" href="{{env('APP_URL')}}{{ $settings['app_favicon'] }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/flag-icons.css') }}" />
    <link rel="stylesheet" href="{{asset('frontend/css/icons.css')}}">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/theme-default.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/select2/select2.css') }}" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/formvalidation/dist/css/formValidation.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/datatables-rowreorder-bs5/rowReorder.bootstrap5.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/assets/css/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/flatpickr.css') }}">

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/pages/page-auth.css') }}" />

    {{-- Custom styles --}}
    <link rel="stylesheet" href="{{ asset('backend/assets/css/app-calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}" />

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>


    <!-- Styles -->
    {{-- <link rel="stylesheet" href="{{ mix('css/backend/app.css') }}"> --}}




    <!-- Scripts -->
    @routes
    @vite(['resources/js/Backend/app.js'])
    @inertiaHead
</head>

<body>
    @inertia
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('backend/assets/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('backend/assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('backend/assets/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/select2/select2.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- <script src="{{ asset('backend/assets/js/menu.js') }}"></script> --}}

    <!-- Vendors JS -->
    <script src="{{ asset('/backend/assets/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('/backend/assets/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/backend/assets/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('/backend/assets/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('/backend/assets/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('/backend/assets/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('/backend/assets/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('/backend/assets/libs/datatables-buttons/datatables-buttons.js') }}"></script>
    <script src="{{ asset('/backend/assets/libs/datatables-buttons-bs5/buttons.bootstrap5.js') }}"></script>
    <script src="{{ asset('/backend/assets/libs/datatables-rowgroup/datatables.rowgroup.js') }}"></script>
    <script src="{{ asset('/backend/assets/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.js') }}"></script>
    <script src="{{ asset('/backend/assets/libs/datatables-rowreorder-bs5/rowReorder.bootstrap5.min.js') }}"></script>

    <script src="{{ asset('backend/assets/js/moment.js') }}"></script>
    {{-- <script src="{{ asset('backend/assets/js/fullcalendar.js') }}"></script> --}}
    <script src="{{ asset('backend/assets/js/flatpickr.js') }}"></script>

    <script src="{{ asset('/backend/assets/js/menu.js')}}"></script>
    <!-- Main JS -->
    <script src="{{ asset('/backend/assets/js/main.js')}}"></script>
    <script src="{{ asset('/backend/assets/js/forms-extras.js')}}"></script>
    <script src="{{ asset('backend/assets/js/app-calendar-events.js') }}"></script>
    {{-- <script src="{{ asset('backend/assets/js/app-calendar.js') }}"></script> --}}

</body>

</html>
