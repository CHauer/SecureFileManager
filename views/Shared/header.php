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
                </li>
                <li class="topbar-devider"></li>-->
                <li><a href="/home/faq">Help</a></li>
                <?
                if(!IsUserLoggedOn())
                { ?>
                <li class="topbar-devider"></li>
                <li><a href="/account/login">Login</a></li>
                <? } ?>
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
                <li class="dropdown <? CurrentActive("files", "index") ?> <? CurrentActive("files", "myfiles") ?>" >
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                        Files
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/files/index">Public Files</a></li>
                        <li><a href="/files/myfiles">My Files</a></li>
                    </ul>
                </li>
                <!-- End Files -->

                <!-- Forum -->
                <li class="<? CurrentActive("forum", "index") ?>">
                    <a href="/forum/index" >
                        Forum
                    </a>

                </li>
                <!-- End Forum -->
                <?

                    if(IsUserAdministrator()) {
                    ?>

                    <li class="dropdown <? CurrentActive("admin", "index") ?>">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" >
                            Administrator
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin/users">Userlist</a></li>
                            <li><a href="/admin/log">Log</a></li>
                        </ul>
                    </li>

                    <?}?>

                    <li class="<? CurrentActive("account", "logoff") ?>" style="back">
                        <a href="/account/logoff">
                            Logoff
                        </a>
                    </li>
                <?
                }
                else
                {
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

                <!-- User Block -->
                <?
                if(IsUserLoggedOn())
                {?>
                <li>
                    <i class="search fa fa-user search-btn"></i>
                    <div class="search-open">
                        <div class="input-group animated fadeInDown row " style="width: 300px">
                                <img class="col-md-4 col-xs-3" src="<? echo $viewModel->get('userimage') ?>" />
                                <div class="col-md-8 col-xs-5">
                                    <p><a href="/account/showprofile"><? echo $viewModel->get('username') ?></a><br/>
                                    <? echo $viewModel->get('fullname') ?><br/>
                                    <? echo $viewModel->get('email') ?></p>
                                    <a href="/account/manage" class="btn-u hidden-xs" >
                                        Account Management
                                    </a>
                                </div>
                                <a href="/account/manage" class="btn-u hidden-lg hidden-md hidden-sm col-xs-4" >
                                    Manage
                                </a>
                        </div>
                    </div>
                </li>
                <? } ?>
                <!-- End user Block -->
            </ul>
        </div><!--/end container-->
    </div><!--/navbar-collapse-->
</div>
<!--=== End Header ===-->