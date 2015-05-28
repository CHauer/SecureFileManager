<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">Account Management</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="/home/index">Account</a></li>
            <li><a href="/account/manage">Manage</a></li>
            <li class="active">Change Password</li>
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
            <form class="reg-page" action="/account/changepassword" method="post" >
                <? CreateHiddenAntiCSRFTokenField(); ?>

                <div class="reg-header">
                    <h2>Change your Password</h2>
                </div>

                <?
                if($viewModel->exists("error")){
                    echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
                }
                ?>

                <div class="form-group col-md-12  col-sm-12 <? ValidationErrorClass("CurrentPassword", $viewModel) ?> ">
                    <label class="control-label" >Current Password <span class="color-green">*</span></label>
                    <input type="Password"  name="CurrentPassword" class="form-control margin-bottom-20">
                    <? ValidationErrorMessage("CurrentPassword", $viewModel) ?>
                </div>

                <div class="row">
                    <div class="form-group col-md-6  col-sm-6 <? ValidationErrorClass("NewPassword", $viewModel) ?> ">
                        <label class="control-label" >New Password <span class="color-green">*</span></label>
                        <input type="Password"  name="NewPassword" class="form-control margin-bottom-20">
                        <? ValidationErrorMessage("NewPassword", $viewModel) ?>
                    </div>
                    <div class="form-group col-md-6  col-sm-6 <? ValidationErrorClass("PasswordConfirm", $viewModel) ?> ">
                        <label class="control-label" >Confirm New Password <span class="color-green">*</span></label>
                        <input type="Password" name="PasswordConfirm" class="form-control margin-bottom-20">
                        <? ValidationErrorMessage("PasswordConfirm", $viewModel) ?>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-lg-6 text-right">
                        <button class="btn-u" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div><!--/container-->
<!--=== End Content Part ===-->