<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">Registration</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="/home/index">Home</a></li>
            <li class="active">Registration</li>
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
            <form class="reg-page" action="/account/register" method="post" enctype="multipart/form-data">
                <? CreateHiddenAntiCSRFTokenField(); ?>

                <div class="reg-header">
                    <h2>Register a new account</h2>
                    <p>Already Signed Up? Click <a href="/account/login" class="color-green">Sign In</a> to login your account.</p>
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

                <div class="form-group <? ValidationErrorClass("Firstname", $viewModel) ?>">
                    <label class="control-label" >First name</label>
                    <input type="text" name="Firstname" class="form-control margin-bottom-20"  <? ModelValue($model, 'Firstname')?> >
                    <? ValidationErrorMessage("Firstname", $viewModel) ?>
                </div>

                <div class="form-group <? ValidationErrorClass("Lastname", $viewModel) ?>">
                    <label class="control-label" >Last name</label>
                    <input type="text" name="Lastname" class="form-control margin-bottom-20"  <? ModelValue($model, 'Lastname')?> >
                    <? ValidationErrorMessage("Lastname", $viewModel) ?>
                </div>

                <div class="form-group <? ValidationErrorClass("EMail", $viewModel) ?>">
                    <label class="control-label" >Email Address <span class="color-green">*</span></label>
                    <input type="text" name="EMail" class="form-control margin-bottom-20"  <? ModelValue($model, 'EMail')?> >
                    <? ValidationErrorMessage("EMail", $viewModel) ?>
                </div>

                <div class="form-group <? ValidationErrorClass("Picture", $viewModel) ?>">
                    <label class="control-label" >Profile Picture</label>
                    <input type="file" name="Picture" >
                    <? ValidationErrorMessage("Picture", $viewModel) ?>
                </div>

                <div class="form-group <? ValidationErrorClass("BirthDate", $viewModel) ?>">
                    <label class="control-label" >Birth date <span class="color-green">*</span></label>
                    <input type="text" name="BirthDate" class="form-control margin-bottom-20"  <? ModelDateValue($model, 'BirthDate')?> >
                    <? ValidationErrorMessage("BirthDate", $viewModel) ?>
                </div>

                <div class="form-group <? ValidationErrorClass("Description", $viewModel) ?>">
                    <label class="control-label" >Description</label>
                    <textarea name="Description" class="form-control margin-bottom-20"><? EchoModelValue($model, 'Description') ?></textarea>
                    <? ValidationErrorMessage("Description", $viewModel) ?>
                </div>

                <label>Account <span class="color-green">*</span></label>
                <div class="row margin-bottom-20">
                    <div class="col-sm-3">
                        <span><input type="radio" value="Standard" checked="checked" name="Role" > Standard</span>
                    </div>
                    <div class="col-sm-3">
                        <span><input type="radio" value="Premium" name="Role" > Premium</span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group  col-sm-6 <? ValidationErrorClass("Password", $viewModel) ?> ">
                        <label class="control-label" >Password <span class="color-green">*</span></label>
                        <input type="Password"  name="Password" class="form-control margin-bottom-20">
                        <? ValidationErrorMessage("Password", $viewModel) ?>
                    </div>
                    <div class="form-group  col-sm-6 <? ValidationErrorClass("PasswordConfirm", $viewModel) ?> ">
                        <label class="control-label" >Confirm Password <span class="color-green">*</span></label>
                        <input type="Password" name="PasswordConfirm" class="form-control margin-bottom-20">
                        <? ValidationErrorMessage("PasswordConfirm", $viewModel) ?>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-lg-6 checkbox">
                        <label>
                            <input type="checkbox" name="CheckTerms">
                            I read <a href="/home/terms" class="color-green">Terms and Conditions</a>
                            <? ValidationErrorMessage("CheckTerms", $viewModel) ?>
                        </label>
                    </div>
                    <div class="col-lg-6 text-right">
                        <button class="btn-u" type="submit">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div><!--/container-->
<!--=== End Content Part ===-->