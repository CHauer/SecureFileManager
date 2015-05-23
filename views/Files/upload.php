<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">File Upload</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="/home/index">Home</a></li>
            <li><a href="/files/index">Upload List</a></li>
            <li class="active">Upload</li>
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
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <form class="reg-page" action="/files/upload" method="post" enctype="multipart/form-data">
                <div class="reg-header">
                    <h2>Upload a new file</h2>
                </div>

                <?
                if($viewModel->exists("error")){
                    echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
                }
                ?>

                <div class="form-group <? ValidationErrorClass("Name", $viewModel) ?> ">
                    <label>File Name <span class="color-red">*</span></label>
                    <input type="text" name="Name" class="form-control margin-bottom-20" maxlength="200" <? ModelValue($model, 'Name')?>>
                    <? ValidationErrorMessage("Name", $viewModel) ?>
                </div>

                <div class="form-group <? ValidationErrorClass("FileLink", $viewModel) ?>">
                    <label class="control-label" >File Link  <span class="color-red">*</span></label>
                    <input type="file" name="FileLink" <? ModelValue($model, 'FileLink')?> class="margin-bottom-20">
                    <? ValidationErrorMessage("FileLink", $viewModel) ?>
                </div>

                <label>File Description </label>
                <textarea name="Description" class="form-control margin-bottom-20" maxlength="3000"<? ModelValue($model, 'Description')?> > </textarea>

                <div class="row">
                    <div class="col-lg-6 checkbox">
                        <label>
                            <input type="checkbox" Name="IsPrivate">Private file
                        </label>
                    </div>
                    <div class="col-lg-6 text-right">
                        <button class="btn-u" type="submit">Upload</button>
                    </div>
                </div>
            </form>
        </div>
    </div><!--/row-->
</div><!--/container-->
<!--=== End Content Part ===-->