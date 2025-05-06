<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.0/css/all.min.css" integrity="sha512-3PN6gfRNZEX4YFyz+sIyTF6pGlQiryJu9NlGhu9LrLMQ7eDjNgudQoFDK3WSNAayeIKc6B8WXXpo4a7HqxjKwg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ $settings['app_name'] }}</title>
    <link rel="icon" type="image/x-icon" href="{{env('APP_URL')}}{{ $settings['app_favicon'] }}">
    {{--this is link for frontend csss --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <!-- Popper.js and Bootstrap JS -->


    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css.map') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/select2/select2.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}" />




    <!-- <style>
        * {
            font-family: "Jost"
        }

        a {
            font-size: 15px;
        }
    </style> -->
      @routes
      @vite(['resources/js/Frontend/app.js'])
      @inertiaHead
</head>

<body>
    @inertia
   <script src="{{ asset('frontend/js/bootstrap.js') }}"></script>
   <script src="{{ asset('frontend/js/custom.js') }}"></script>
   <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

   <script src="{{ asset('backend/assets/libs/select2/select2.js') }}"></script>
</body>

</html>
