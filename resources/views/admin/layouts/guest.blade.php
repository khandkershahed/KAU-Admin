<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">



<head>
    <base href="../../../">
    <title>{{ optional($setting)->website_name ?? config('app.name', 'Laravel') }}</title>
    <meta charset="utf-8" />
    <meta name="title" content="{{ optional($setting)->meta_title }}" />
    <meta name="description" content="{{ optional($setting)->meta_description }}" />
    <meta name="keywords" content="{{ optional($setting)->meta_keyword }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ optional($setting)->meta_title }}" />
    <meta property="og:url" content="{{ optional($setting)->site_url }}" />
    <meta property="og:site_name" content="{{ optional($setting)->website_name }}" />
    <link rel="canonical" href="{{ optional($setting)->site_url }}" />
    <link rel="shortcut icon"
        href="{{ !empty(optional($setting)->site_favicon) && file_exists(public_path('storage/' . optional($setting)->site_favicon)) ? asset('storage/' . optional($setting)->site_favicon) : asset('images/favicon.jpg') }}"
        type="image/x-icon" />

    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" /> --}}


    <link href="{{ asset('admin/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>



<body id="kt_body" class="bg-body">


    <div class="d-flex flex-column flex-root">
        {{ $slot }}
    </div>



    <script>
        var hostUrl = "assets/";
    </script>

    <script src="{{ asset('admin/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('admin/assets/js/scripts.bundle.js') }}"></script>


    <script src="{{ asset('admin/assets/js/custom/authentication/sign-in/general.js') }}"></script>
    <script src="{{ asset('admin/assets/js/custom/apps/user-management/users/view/update-password.js') }}"></script>

    @stack('scripts')

</body>


</html>
