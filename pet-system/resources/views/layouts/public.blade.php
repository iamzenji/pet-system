<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- FONTS -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">


    @stack('styles')
    <style>
        .top-bar {
            background: #157347;
            padding: 10px 0;
            color: white;
            text-align: center;
        }
        .navbar {
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar-nav .nav-link {
            color: #157347 !important;
            font-weight: bold;
        }
        .hero-section {
            background: url('your-background-image.jpg') no-repeat center center/cover;
            padding: 100px 0;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .btn-custom {
            background: #157347;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
        }

        /* about us */
        body {
            font-family: Arial, sans-serif;
        }
        .hero-section {
            padding: 60px 0;
        }
        .hero-image img {
            max-width: 100%;
            height: auto;
        }
        .hero-content h1 {
            font-weight: bold;
        }
        .hero-content p {
            color: #666;
        }
        .list-icon {
            color: #28a745;
            margin-right: 10px;
        }
        .read-more {
            color: #000;
            font-weight: bold;
            text-decoration: none;
        }
        .read-more:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://fastly.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

    <!-- SWEET ALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://fastly.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    @stack('scripts')
</body>
</html>
