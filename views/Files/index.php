<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">Upload List</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li class="active">Upload List</li>
        </ul>
    </div>
    <!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<?
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

        <form class="page-search-form" action="/files/index" method="post">
            <div class="row margin-bottom-20">
                <div class="job-img-inputs">
                    <!-- Serach -->
                    <div class="col-sm-4 md-margin-bottom-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                            <input type="text" placeholder="What file are you looking for?" class="form-control" name="Name">
                        </div>
                    </div>
                    <div class="col-sm-4 md-margin-bottom-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                            <input type="text" placeholder="Which user are you looking for?" class="form-control" name="User">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <button class="btn-u btn-block btn-u-dark" type="submit">Register</button>
                    </div>
                </div>
            </div>
        </form>

    <div class="row">
        <?php
        foreach($data as $files)
        {
            ?>

            <p><?php echo ModelValue($data, 'Name') ?></p>

        <?php
        }
        ?>
    </div>
</div><!--/container-->
<!--=== End Content Part ===-->