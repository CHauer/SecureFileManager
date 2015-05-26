<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">Account Management</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="/home/index">Account</a></li>
            <li><a href="/account/manage">Manage</a></li>
            <li class="active">Change User Picture</li>
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
            <form class="reg-page" action="/account/changeuserpicture" method="post" enctype="multipart/form-data">
                <? CreateHiddenAntiCSRFTokenField(); ?>

                <div class="reg-header">
                    <h2>Edit your Picture</h2>
                </div>

                <?
                if($viewModel->exists("error")){
                    echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
                }
                ?>

                <div class="form-group <? ValidationErrorClass("Picture", $viewModel) ?>">
                    <label class="control-label" >Profile Picture</label>
                    <input type="file" name="Picture" >
                    <? ValidationErrorMessage("Picture", $viewModel) ?>
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