<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">Account Management</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="/home/index">Account</a></li>
            <li><a href="/account/manage">Manage</a></li>
            <li class="active">Edit</li>
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
            <form class="reg-page" action="/account/editprofile" method="post" enctype="multipart/form-data">
                <? CreateHiddenAntiCSRFTokenField(); ?>

                <div class="reg-header">
                    <h2>Edit your Profile</h2>
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
                    <textarea name="Description" class="form-control margin-bottom-20" ><? echo $model != null ? $model->Description : '' ?></textarea>
                    <? ValidationErrorMessage("Description", $viewModel) ?>
                </div>

                <label>Account <span class="color-green">*</span></label>

                <div class="row margin-bottom-20">
                    <div class="col-sm-6">
                        <span><input type="radio" value="Standard" <? echo ($model->Role->Name == 'Standard') ? 'checked="checked"' : ''; ?> name="Role" > Standard</span>
                    </div>
                    <div class="col-sm-6">
                        <span><input type="radio" value="Premium" <? echo ($model->Role->Name == 'Premium') ? 'checked="checked"' : ''; ?> name="Role"  > Premium</span>
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
                    <div class="col-lg-6 text-right">
                        <button class="btn-u" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div><!--/container-->
<!--=== End Content Part ===-->