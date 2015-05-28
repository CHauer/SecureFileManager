<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">My Files</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li class="active">My Files</li>
        </ul>
    </div>
    <!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<?
$files = null;

if($viewModel->exists("model"))
{
    $files = $viewModel->get("model");
}
?>

<!--=== Content Part ===-->
<div class="container content">
        <?
        if(IsPremiumUser()) {
            ?>
            <div class="row margin-bottom-10">
                <div class="margin-left-5">
                    <h4>Upload a file</h4>
                    <p><a class="color-green" href="/files/upload">click here</a> to upload a new file.</p>
                </div>
            </div>
        <?
        }
        ?>

        <?
        if($viewModel->exists("error")){
            echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
        }
        ?>
        <?
        if(isset($_SESSION["error"])){
            echo '<h3 class="color-red">' . $_SESSION["error"] . '</h3>';
            $_SESSION["error"] = null;
        }
        ?>
        <?
        if(isset($_SESSION["deleteFile"])){
            echo '<h3 class="color-green">' . $_SESSION["deleteFile"] . '</h3>';
            $_SESSION["deleteFile"] = null;
        }
        ?>

        <form class="page-search-form" action="/files/myfiles" method="post">
            <div class="row margin-bottom-40">
                <div class="job-img-inputs">
                    <!-- Serach -->
                    <div class="col-sm-4 md-margin-bottom-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                            <input type="text" placeholder="What file are you looking for?" class="form-control" name="Name" maxlength="20">
                        </div>
                    </div>
                    <div class="col-sm-3 md-margin-bottom-10">
                        <div class="input-group">
                        <label class="select state-success">
                            <select name="SortBy" class="valid">
                                <option value='Uploaded'>Uploaded</option>
                                <option value='Username'>Private</option>
                            </select>
                        </label>
                            </div>
                    </div>
                    <div class="col-sm-3">
                        <button class="btn-u btn-block btn-u-dark" type="submit">Search Files</button>
                    </div>
                </div>
            </div>
        </form>

    <div class="row">
        <div class="col-md-9">
        <?php
            if (!is_null($files))
            {
                foreach($files as $data)
                {
                    ?>
                        <div class="row margin-bottom-5">
                            <div class="col-sm-7 news-v3">
                                <div class="news-v3-in-sm no-padding">
                                    <ul class="list-inline posted-info">
                                        <li>Uploaded <?php echo ModelDateTimeValue($data['Uploaded']) ?></li>
                                        <?php if ($data['IsPrivate'] == '1') { ?>
                                        <li>private</li>
                                        <?php } ?>
                                    </ul>
                                    <h2><a href="/files/details/<?php echo $data['UserFileId']?>"><?php echo $data['Name']?> </a></h2>
                                    <p style="word-wrap: normal"><?php echo $data['Description']?></p>
                                </div>
                                <ul class="post-shares">
                                    <li>
                                        <a href="#">
                                            <i class="rounded-x icon-speech"></i>
                                            <span><?php echo $data['CommentCount']?></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/files/download/<?php echo $data['UserFileId']?>">
                                            <i class="rounded-x icon-cloud-download"></i>
                                        </a>
                                    </li>
                                    <?php
                                    if (isFileOwner($_SESSION["userid"], $data['UserFileId'] ))
                                    {?>
                                    <li>
                                        <a href="/files/delete/<?php echo $data['UserFileId']?>">
                                            <i class="rounded-x icon-trash"></i>
                                        </a>
                                    </li>
                                    <?}?>
                                </ul>
                            </div>
                        </div>
                        <hr/>
                    <?php
                }
            }
        ?>
        </div>
    </div>
</div><!--/container-->
<!--=== End Content Part ===-->