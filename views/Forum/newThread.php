<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">NewThread</h1>
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
            <form class="reg-page" action="forum/newThread" method="post">
                <div class="reg-header">
                    <h2>Post a new thread</h2>
                </div>

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
            </form>
        </div>
    </div><!--/row-->
</div><!--/container-->
<!--=== End Content Part ===-->