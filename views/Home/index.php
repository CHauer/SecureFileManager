<link rel="stylesheet" href="assets/plugins/flexslider/flexslider.css">
<link rel="stylesheet" href="assets/plugins/parallax-slider/css/parallax-slider.css">

    <!--=== Slider ===-->
    <div class="slider-inner">
        <div id="da-slider" class="da-slider">
            <div class="da-slide">

                <?
                if(!IsUserLoggedOn()) {
                    ?>
                    <h2>Welcome to Secure File Manager!</h2>
                <?
                }else{
                    ?>
                    <h2>Hi <? echo $viewModel->get("username") ?>! Welcome to Secure File Manager!</h2>
                <?
                }
                ?>
                <p><i>Lorem ipsum dolor amet</i> <br /> <i>tempor incididunt ut</i> <br /> <i>veniam omnis </i></p>
                <div class="da-img"><img class="img-responsive" src="assets/plugins/parallax-slider/img/1.png" alt=""></div>
            </div>
            <div class="da-slide">
                <h2><i>RESPONSIVE VIDEO</i> <br /> <i>SUPPORT AND</i> <br /> <i>MANY MORE</i></h2>
                <p><i>Lorem ipsum dolor amet</i> <br /> <i>tempor incididunt ut</i></p>
                <div class="da-img">
                    <iframe src="http://player.vimeo.com/video/47911018" width="530" height="300" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                </div>
            </div>
            <div class="da-slide">
                <h2><i>USING BEST WEB</i> <br /> <i>SOLUTIONS WITH</i> <br /> <i>HTML5/CSS3</i></h2>
                <p><i>Lorem ipsum dolor amet</i> <br /> <i>tempor incididunt ut</i> <br /> <i>veniam omnis </i></p>
                <div class="da-img"><img src="assets/plugins/parallax-slider/img/html5andcss3.png" alt="image01" /></div>
            </div>
            <div class="da-arrows">
                <span class="da-arrows-prev"></span>
                <span class="da-arrows-next"></span>
            </div>
        </div>
    </div><!--/slider-->
    <!--=== End Slider ===-->

    <!--=== Purchase Block ===-->
    <div class="purchase">
        <div class="container">
            <div class="row">
                <div class="col-md-9 animated fadeInLeft">
                    <span>Unify is a clean and fully responsive incredible Template.</span>
                    <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi  vehicula sem ut volutpat. Ut non libero magna fusce condimentum eleifend enim a feugiat corrupti quos.</p>
                </div>
                <div class="col-md-3 btn-buy animated fadeInRight">
                    <a href="#" class="btn-u btn-u-lg"><i class="fa fa-cloud-download"></i> Download Now</a>
                </div>
            </div>
        </div>
    </div><!--/row-->
    <!-- End Purchase Block -->

    <!--=== Content Part ===-->
    <div class="container content">
        <div class="title-box">
            <div class="title-box-text">We <span class="color-green">Do</span> Awesome Design</div>
            <p>Creative freedom matters user experience, we minimize the gap between technology and its audience.</p>
        </div>

        <div class="margin-bottom-40"></div>

        <!-- Info Blocks -->
        <div class="row">
            <div class="col-sm-4 info-blocks">
                <i class="icon-info-blocks fa fa-bell-o"></i>
                <div class="info-blocks-in">
                    <h3>FREE Updates</h3>
                    <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum animi..</p>
                </div>
            </div>
            <div class="col-sm-4 info-blocks">
                <i class="icon-info-blocks fa fa-hdd-o"></i>
                <div class="info-blocks-in">
                    <h3>1000+ Icons</h3>
                    <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum animi..</p>
                </div>
            </div>
            <div class="col-sm-4 info-blocks">
                <i class="icon-info-blocks fa fa-lightbulb-o"></i>
                <div class="info-blocks-in">
                    <h3>Creative Ideas</h3>
                    <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum animi..</p>
                </div>
            </div>
        </div>

        <div class="row margin-bottom-60">
            <div class="col-sm-4 info-blocks">
                <i class="icon-info-blocks fa fa-code"></i>
                <div class="info-blocks-in">
                    <h3>SEO Ready</h3>
                    <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum animi..</p>
                </div>
            </div>
            <div class="col-sm-4 info-blocks">
                <i class="icon-info-blocks fa fa-compress"></i>
                <div class="info-blocks-in">
                    <h3>Fully Responsive</h3>
                    <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum animi..</p>
                </div>
            </div>
            <div class="col-sm-4 info-blocks">
                <i class="icon-info-blocks fa fa-html5"></i>
                <div class="info-blocks-in">
                    <h3>CSS3 + HTML5</h3>
                    <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum animi..</p>
                </div>
            </div>
        </div>
        <!-- End Info Blocks -->