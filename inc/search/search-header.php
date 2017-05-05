<?php
    require __DIR__ .'/../../core/init.php';

    if (isset($_GET['user'])) {
        $username = $_GET['user'];
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="Keeping the Internet Cute.">
    <meta name="author" content="Carter Cochran">
    <link rel="canonical" href="http://petstapost.com/"/>
    <meta property="og:site_name" content="Petstapost"/>
    <meta property="og:title" content="Post pics of your pet!"/>
    <meta property="og:description" content="Keeping the Internet Cute.">
    <meta property="og:url" content="http://petstapost.com/"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image" content="http://petstapost.com/img/og-image.png"/>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@petstapost">
    <meta name="twitter:title" content="Petstapost">
    <meta name="twitter:description" content="Keeping the Internet Cute.">
    <meta name="twitter:image:src" content="http://petstapost.com/img/og-image.png">

    <title>Petstapost - <?php echo $username?></title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/cropper.min.css" rel="stylesheet">
    <link href="css/petstapost.css" rel="stylesheet">
    <link href="css/magnific-popup.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/ico" href="http://petstapost.com/img/favicon.ico?v=2"/>

    <!-- Custom Fonts -->
    <link href="foundation-icons/foundation-icons.css" rel="stylesheet" type="text/css">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js does not work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>

</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <?php
        require_once 'inc/search/check-verified-nav.php';
    ?>