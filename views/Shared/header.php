<!--=== Header ===-->
<div class="header">
    <div class="container">
        <!-- Logo -->
        <a class="logo" href="/home/index">
            <img src="/assets/img/logo1-default.png" alt="Logo">
        </a>
        <!-- End Logo -->

        <!-- Topbar -->
        <div class="topbar">
            <ul class="loginbar pull-right">
                <!--<li class="hoverSelector">
                    <i class="fa fa-globe"></i>
                    <a>Languages</a>
                    <ul class="languages hoverSelectorBlock">
                        <li class="active">
                            <a href="#">English <i class="fa fa-check"></i></a>
                        </li>
                        <li><a href="#">Spanish</a></li>
                        <li><a href="#">Russian</a></li>
                        <li><a href="#">German</a></li>
                    </ul>
                </li>-->
                <li class="topbar-devider"></li>
                <li><a href="/home/faq">Help</a></li>
                <li class="topbar-devider"></li>
                <li><a href="/account/login">Login</a></li>
            </ul>
        </div>
        <!-- End Topbar -->

        <!-- Toggle get grouped for better mobile display -->
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="fa fa-bars"></span>
        </button>
        <!-- End Toggle -->
    </div><!--/end container-->

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse mega-menu navbar-responsive-collapse">
        <div class="container">
            <ul class="nav navbar-nav">
                <!-- Home -->
                <li class="<? CurrentActive("home", "index") ?> ">
                    <a href="/home/index">
                        Home
                    </a>

                </li>
                <!-- End Home -->

                <? if(IsUserLoggedOn()){ ?>

                <!-- Files -->
                <li class="<? CurrentActive("files", "index") ?>">
                    <a href="/files/index" >
                        Files
                    </a>

                </li>
                <!-- End Files -->

                <!-- Forum -->
                <li class="<? CurrentActive("forum", "index") ?>">
                    <a href="/forum/index" >
                        Forum
                    </a>

                </li>
                <!-- End Forum -->

                <? } ?>

                <?
                if(IsUserLoggedOn())
                {

                }else{
                ?>

                <!-- Login -->
                <li class="<? CurrentActive("account", "login") ?>">
                    <a href="/account/login">
                        Login
                    </a>

                </li>
                <!-- End Login -->

                <!-- Register -->
                <li class="<? CurrentActive("account", "register") ?>">
                    <a href="/account/register" >
                        Register
                    </a>

                </li>
                <!-- End Register -->

                <? } ?>

                <!-- Search Block -->
                <!-- <li>
                    <i class="search fa fa-search search-btn"></i>
                    <div class="search-open">
                        <div class="input-group animated fadeInDown">
                            <input type="text" class="form-control" placeholder="Search">
                                <span class="input-group-btn">
                                    <button class="btn-u" type="button">Go</button>
                                </span>
                        </div>
                    </div>
                </li> -->
                <!-- End Search Block -->
            </ul>
        </div><!--/end container-->
    </div><!--/navbar-collapse-->
</div>
<!--=== End Header ===-->