<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title><?php echo $viewModel->get('pageTitle'); ?> - Secure File Manager</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico">

    <!-- Web Fonts -->
    <link rel='stylesheet' type='text/css' href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600&amp;subset=cyrillic,latin'>

    <!-- CSS Global Compulsory -->
    <link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- CSS Header and Footer -->
    <link rel="stylesheet" href="/assets/css/headers/header-default.css">
    <link rel="stylesheet" href="/assets/css/footers/footer-v1.css">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="/assets/plugins/animate.css">
    <link rel="stylesheet" href="/assets/plugins/line-icons/line-icons.css">
    <link rel="stylesheet" href="/assets/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/plugins/owl-carousel/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="/assets/plugins/layer-slider/layerslider/css/layerslider.css">

    <!-- CSS Customization -->
    <link rel="stylesheet" href="/assets/css/custom.css">
</head>

<body>

<div class="wrapper">
    <?php require("Shared/header.php");?>

    <?php require($this->viewFile);?>

    <?php require("Shared/footer.php");?>

</div><!--/wrapper-->

<!-- JS Global Compulsory -->
<script type="text/javascript" src="/assets/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery/jquery-migrate.min.js"></script>
<script type="text/javascript" src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- JS Implementing Plugins -->
<script type="text/javascript" src="/assets/plugins/back-to-top.js"></script>
<script type="text/javascript" src="/assets/plugins/smoothScroll.js"></script>
<!--
<script type="text/javascript" src="/assets/plugins/owl-carousel/owl-carousel/owl.carousel.js"></script>
<script type="text/javascript" src="/assets/plugins/layer-slider/layerslider/js/greensock.js"></script>
<script type="text/javascript" src="/assets/plugins/layer-slider/layerslider/js/layerslider.transitions.js"></script>
<script type="text/javascript" src="/assets/plugins/layer-slider/layerslider/js/layerslider.kreaturamedia.jquery.js"></script>
 -->
<!-- JS Customization -->
<script type="text/javascript" src="/assets/js/custom.js"></script>
<!-- JS Page Level -->
<script type="text/javascript" src="/assets/js/app.js"></script>
<!--
<script type="text/javascript" src="/assets/js/plugins/layer-slider.js"></script>
<script type="text/javascript" src="/assets/js/plugins/owl-carousel.js"></script>
<script type="text/javascript" src="/assets/js/plugins/owl-recent-works.js"></script>
-->

<script type="text/javascript">
    jQuery(document).ready(function() {
        App.init();
        /* LayerSlider.initLayerSlider();
        OwlCarousel.initOwlCarousel();
        OwlRecentWorks.initOwlRecentWorksV2(); */
    });
</script>

<!--[if lt IE 9]>
<script src="/assets/plugins/respond.js"></script>
<script src="/assets/plugins/html5shiv.js"></script>
<script src="/assets/plugins/placeholder-IE-fixes.js"></script>
<![endif]-->

</body>
</html>