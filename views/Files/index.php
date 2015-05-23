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

<!--=== Content Part ===-->
<div class="container content">

    <?
    if(IsPremiumUser()) {
        ?>
        <div class="row">
            <div class="md-margin-bottom-20 margin-left-5">
                <h4>Upload a file</h4>
                <p><a class="color-green" href="/files/upload">click here</a> to upload a new file.</p>
            </div>
        </div>
    <?
    }
    ?>

    <div class="row md-margin-bottom-20">
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


    <div class="row"></div>
</div><!--/container-->
<!--=== End Content Part ===-->