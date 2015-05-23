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
    $model = $viewModel->get("model");
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

        <div class="row margin-bottom-20">
            <div class="job-img-inputs">
                <!-- Serach -->
                <div class="col-sm-4 md-margin-bottom-10">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input type="text" placeholder="What file are you looking for?" class="form-control">
                    </div>
                </div>
                <div class="col-sm-4 md-margin-bottom-10">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        <input type="text" placeholder="Which user are you looking for?" class="form-control">
                    </div>
                </div>
                <div class="col-sm-3">
                    <button type="button" class="btn-u btn-block btn-u-dark"> Search Files</button>
                </div>
            </div>
        </div>

        <div class="row margin-bottom-20">
            <div class="col-sm-6">
                <h3>Show: </h3>
                <div class="btn-group open">
                    <select name="Count">
                        <option value="All">All</option>
                        <option value="20">20</option>
                        <option value="10" selected>10</option>
                        <option value="5">5</option>
                    </select>
                </div>
            </div>
            <div>
                <select name="Order">
                    <option value="User">User Name</option>
                    <option value="Date" selected>Upload Date</option>
                </select>
            </div>
        </div>

    <div class="row"></div>
</div><!--/container-->
<!--=== End Content Part ===-->