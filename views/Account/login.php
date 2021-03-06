<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">Login</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="/home/index">Home</a></li>
            <li class="active">Login</li>
        </ul>
    </div><!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->
<?
$username = '';

if($viewModel->exists("model"))
{
    $username = $viewModel->get("model");
}
?>

<!--=== Content Part ===-->
<div class="container content">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <form class="reg-page" action="/account/login" method="post">
                <? CreateHiddenAntiCSRFTokenField(); ?>

                <div class="reg-header">
                    <h2>Login to your account</h2>
                </div>

                <?
                if($viewModel->exists("error"))
                {
                    echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
                }
                ?>

                <div class="input-group margin-bottom-20">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" name="Username" placeholder="Username" class="form-control" <?  echo ' value="' . $username . '" ' ?>>
                </div>
                <div class="input-group margin-bottom-20">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="Password" placeholder="Password" class="form-control">
                </div>

                <div class="row">
                    <div class="col-md-6 checkbox">
                        <label><input type="checkbox" name="RememberMe"> Stay signed in</label>
                    </div>
                    <div class="col-md-6">
                        <button class="btn-u pull-right" type="submit">Login</button>
                    </div>
                </div>

                <hr>

                <h4>Forget your Password ?</h4>
                <p>no worries, <a class="color-green" href="/account/resetPassword">click here</a> to reset your password.</p>
            </form>
        </div>
    </div><!--/row-->
</div><!--/container-->
<!--=== End Content Part ===-->