<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>@yield('title', config('app.name')) - {{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Icon -->
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon-small.jpg') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome/css/font-awesome.css') }}">

<!-- Toastr style -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/toastr/toastr.min.css') }}">

<!-- Gritter -->
<link rel="stylesheet" type="text/css" href="{{ asset('js/plugins/gritter/jquery.gritter.css') }}">

<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/dataTables/datatables.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('css/animate.css') }}">

<!-- Custom css -->
@yield('head')

<!-- Template css -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

<!-- App css -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
