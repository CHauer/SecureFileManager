<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">New thread</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="/forum/index">Forum</a></li>
            <li class="active">New thread</li>
        </ul>
    </div><!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container content">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <form class="reg-page" action="newThread" method="post">
                <div class="reg-header">
                    <h2>Post a new thread</h2>
                </div>

                <?
                if($viewModel->exists("error")){
                    echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
                }
                ?>

                <div class="form-group <? ValidationErrorClass("Title", $viewModel) ?> ">
                    <label>Title <span class="color-red">*</span></label>
                    <input type="text" name="Title" class="form-control margin-bottom-20" maxlength="200">
                    <? ValidationErrorMessage("Title", $viewModel) ?>
                </div>

                <div class="form-group <? ValidationErrorClass("Description", $viewModel) ?> ">
                    <label>Description <span class="color-red">*</span></label>
                    <input type="text" name="Description" class="form-control margin-bottom-20" maxlength="1000">
                    <? ValidationErrorMessage("Description", $viewModel) ?>
                </div>

                <div class="col-lg-6 text-right">
                    <button class="btn-u" type="submit">Post</button>
                </div>
            </form>
        </div>
    </div><!--/row-->
</div><!--/container-->
<!--=== End Content Part ===-->