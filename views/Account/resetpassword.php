<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">Password Reset</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="/home/index">Home</a></li>
            <li class="active">Reset Password</li>
        </ul>
    </div><!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<?
if($viewModel->exists("model"))
{
    $model = $viewModel->get("model");
}
?>

<!--=== Content Part ===-->
<div class="container content">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <form class="reg-page" action="/account/resetpassword" method="post" >
                <? CreateHiddenAntiCSRFTokenField(); ?>

                <div class="reg-header">
                    <h2>Reset your Password</h2>
                </div>

                <?
                if($viewModel->exists("error")){
                    echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
                }
                ?>

                <div class="form-group <? ValidationErrorClass("Username", $viewModel) ?> ">
                    <label class="control-label" >Username <span class="color-green">*</span></label>
                    <input type="text" name="Username" class="form-control margin-bottom-20" <? ModelValue($model, 'Username')?> >
                    <? ValidationErrorMessage("Username", $viewModel) ?>
                </div>

                <div class="form-group <? ValidationErrorClass("EMail", $viewModel) ?> ">
                    <label class="control-label" >EMail <span class="color-green">*</span></label>
                    <input type="text" name="EMail" class="form-control margin-bottom-20" <? ModelValue($model, 'EMail')?> >
                    <? ValidationErrorMessage("EMail", $viewModel) ?>
                </div>

               <hr>

                <div class="row">
                    <div class="col-lg-6 text-right">
                        <button class="btn-u" type="submit">Reset Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div><!--/container-->
<!--=== End Content Part ===-->