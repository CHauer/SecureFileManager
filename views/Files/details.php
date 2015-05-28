<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">File Details</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="/home/index">Home</a></li>
            <li><a href="/files/index">File List</a></li>
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

if($viewModel->exists("comment"))
{
    $comment = $viewModel->get("comment");
}
?>

<!--=== Content Part ===-->
<div class="container content profile">
    <div class="row">

        <? CreateHiddenAntiCSRFTokenField(); ?>

        <!--Left Sidebar-->
        <div class="col-md-3 md-margin-bottom-40">

            <div class="overflow-h">
                <label class="control-label" >File Name: </label><br/>
                <label class="margin-bottom-20" ><?php echo $model->Name ?>  </label>
            </div>

            <div class="post-shares">
                <a href="/files/download/<?php echo $model->UserFileId?>">
                    <i class="rounded-x icon-cloud-download"></i>
                </a>
                <?php
                if (isFileOwner($data['UserId']))
                {?>
                        <a href="/files/delete/<?php $model->UserFileId?>">
                            <i class="rounded-x icon-trash"></i>
                        </a>
                <?}?>
            </div>

            <div class="overflow-h">
                <label class="control-label" >File Description: </label><br/>
                <label class="margin-bottom-20" style="word-wrap: break-word"><?php echo $model->Description ?></label>
            </div>
        </div>
        <!--End Left Sidebar-->

        <div class="col-md-9">

            <?
            if($viewModel->exists("error")){
                echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
            }

            if (!is_null($comments)) {
                ?>

                <div class="profile-body">
                    <div class="panel panel-profile">
                        <div class="reg-header">
                            <h2>User Comments</h2>
                        </div>
                        <?php
                        foreach ($comments as $data) {
                            ?>
                            <div class="panel-body margin-bottom-10">
                                <div class="media media-v2">
                                    <a class="pull-left">
                                        <img class="media-object rounded-x" src="<?php echo $data['PictureLink'] ?>"
                                             alt="">
                                    </a>

                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <strong><?php echo $data['Username'] ?>, </strong>
                                            <small><?php echo $data['Created'] ?></small>
                                        </h4>
                                        <p style="word-wrap: break-word"><?php echo $data['Message'] ?></p>
                                    </div>
                                </div>
                                <!--/end media media v2-->
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>

            <?
            if(IsPremiumUser()) {
                ?>
                <form class="reg-page" action="/files/details/<?php echo $model->UserFileId ?>" method="post">
                    <div class="col-md-9">
                        <div class="margin-left-5">

                            <div class="reg-header">
                                <h2>Comment the file</h2>
                            </div>

                            <div class="form-group <? ValidationErrorClass("Message", $viewModel) ?> ">
                                <label class="control-label">Message <span class="color-red">*</span></label>
                                <textarea name="Message" class="form-control margin-bottom-20" <? ModelValue($comment, 'Message')?> maxlength="500"></textarea>
                                <? ValidationErrorMessage("Message", $viewModel) ?>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-lg-6 text-right">
                                    <button class="btn-u" type="submit">Comment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?
            }
            ?>
        </div>
    </div>
</div><!--/container-->
<!--=== End Content Part ===-->