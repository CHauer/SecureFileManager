<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">Delete File</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="/home/index">Home</a></li>
            <li><a href="/files/index">Upload List</a></li>
            <li class="active">File Details</li>
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
                <? CreateHiddenAntiCSRFTokenField(); ?>

                <div class="reg-header">
                    <h2>Show File Details</h2>
                </div>

                <?
                if($viewModel->exists("error")){
                    echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
                }
                ?>

                <div class="form-group">
                    <label class="control-label" >File Name: </label><br/>
                    <label class="margin-bottom-20 control-label" ><?php echo $model->Name ?></label>
                </div>

                <div class="form-group">
                    <label class="control-label" >File Description: </label><br/>
                    <label class="margin-bottom-20 control-label"><?php echo $model->Description ?></label>
                </div>

                <hr>
        </div>
    </div>
</div><!--/container-->
<!--=== End Content Part ===-->