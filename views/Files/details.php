<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">File Details</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="/home/index">Home</a></li>
            <li><a href="/files/index">Upload List</a></li>
            <li class="active">File Details</li>
        </ul>
    </div><!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<?
$comments = null;

if($viewModel->exists("model"))
{
    $model = $viewModel->get("model");
}

if($viewModel->exists("comments"))
{
    $comments = $viewModel->get("comments");
}
?>

<!--=== Content Part ===-->
<div class="container content profile">
    <div class="row">

        <? CreateHiddenAntiCSRFTokenField(); ?>

        <!--Left Sidebar-->
        <div class="col-md-3 md-margin-bottom-40">

            <div class="form-group">
                <label class="control-label" >File Name: </label><br/>
                <label class="margin-bottom-20 control-label" style="white-space: pre-line"><?php echo $model->Name ?></label>
            </div>

            <div class="form-group">
                <label class="control-label" >File Description: </label><br/>
                <label class="margin-bottom-20 control-label" style="white-space: pre-line" ><?php echo $model->Description ?></label>
            </div>

        </div>
        <!--End Left Sidebar-->

        <div class="col-md-9">

            <?
            if($viewModel->exists("error")){
                echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
            }
            ?>

            <?php
            if (!is_null($comments)) {
                foreach ($comments as $data) {
                    ?>
                    <div class="profile-body">
                        <div class="panel panel-profile">
                            <div class="panel-heading overflow-h">
                                <h2 class="panel-title heading-sm pull-left">
                                    Users Comments</h2>
                            </div>
                            <div class="panel-body margin-bottom-10">
                                <div class="media media-v2">
                                    <a class="pull-left">
                                        <img class="media-object rounded-x" src="<?php echo $data['PictureLink'] ?>"
                                             alt="">
                                    </a>

                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <strong><?php echo $data['Username'] ?></strong>
                                            <small><?php echo ModelDateTimeValue($data['Created']) ?></small>
                                        </h4>
                                        <p><?php echo $data['Message'] ?></p>
                                    </div>
                                </div>
                                <!--/end media media v2-->
                            </div>
                        </div>
                    </div>
                <?php
                }
            }
            ?>
        </div>
    </div>
</div><!--/container-->
<!--=== End Content Part ===-->