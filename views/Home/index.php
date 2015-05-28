
<!--=== Purchase Block ===-->
<div class="purchase">
    <div class="container">
        <div class="row">
            <div class="col-md-9 animated fadeInLeft">
                <span style="margin-top: 30px;"><?
                    if(!IsUserLoggedOn()) {
                        ?>
                        Welcome to Secure File Manager!
                    <?
                    }else{
                        ?>
                        Hi <? echo $viewModel->get("username") ?>! Welcome to Secure File Manager!
                    <?
                    }
                    ?></span>
            </div>
            <?
            if(!IsUserLoggedOn()) {
            ?>
            <div class="col-md-3 btn-buy animated fadeInRight">
                <a href="/account/login" class="btn-u btn-u-lg"><i class="fa fa-user"></i> Login</a>
                <a href="/account/register" class="btn-u btn-u-lg"><i class="fa fa-bolt"></i> Register</a>
            </div>
            <? } else { ?>
                <div class="col-md-3 btn-buy animated fadeInRight">
                    <a href="/files/index" class="btn-u btn-u-lg"><i class="fa fa-file"></i> Files</a>
                    <a href="/forum/index" class="btn-u btn-u-lg"><i class="fa fa-users"></i> Forum</a>
                </div>
            <? } ?>
        </div>
    </div>
</div><!--/row-->
<!-- End Purchase Block -->

<!--=== Content Part ===-->
<div class="container content">
    <div class="title-box">
        <div class="title-box-text">We <span class="color-green">Do It</span> Secure!</div>
        <p></p>
    </div>

    <div class="margin-bottom-40"></div>

    <!-- Info Blocks -->
    <div class="row">
        <div class="col-sm-4 info-blocks">
            <i class="icon-info-blocks fa fa-file"></i>
            <div class="info-blocks-in">
                <h3>Files</h3>
                <p>Upload, Donwload Files</p>
            </div>
        </div>
        <div class="col-sm-4 info-blocks">
            <i class="icon-info-blocks fa fa-users"></i>
            <div class="info-blocks-in">
                <h3>Forum</h3>
                <p>Forum Threads</p>
            </div>
        </div>
        <div class="col-sm-4 info-blocks">
            <i class="icon-info-blocks fa fa-key"></i>
            <div class="info-blocks-in">
                <h3>Secure Profiles</h3>
                <p>Standard, Premium</p>
            </div>
        </div>
    </div>
    <!-- End Info Blocks -->

    <div class="row">
        <div class="col-sm-4 info-blocks">
            <i class="icon-info-blocks fa fa-upload"></i>
            <div class="info-blocks-in">
                <h3>Upload</h3>
                <p>Upload Private, Public Files</p>
            </div>
        </div>
        <div class="col-sm-4 info-blocks">
            <i class="icon-info-blocks fa fa-download"></i>
            <div class="info-blocks-in">
                <h3>Download</h3>
                <p>Files from Users</p>
            </div>
        </div>
        <div class="col-sm-4 info-blocks">
            <i class="icon-info-blocks fa fa-database"></i>
            <div class="info-blocks-in">
                <h3>Threads</h3>
                <p>Forum</p>
            </div>
        </div>
    </div>

</div>