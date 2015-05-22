<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">Account Management</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="/home/index">Account</a></li>
            <li class="active">Manage</li>
        </ul>
    </div><!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container content">
    <div class="row" style="height: 500px;">
        <div class="col-md-8">

            <div class="reg-header">
                <h2>Welcome <? echo $viewModel->get("fullname") ?> to your Account Management Portal!</h2>
            </div>

            <div class="row">
                <a class="btn btn-default col-md-5" href="/account/editprofile" >Edit Profile</a>
                <p class="col-md-7" >Here you can edit your Profile, Username, Password, Role, Description and User Picture.</p>
            </div>

            <div class="row">
                <a class="btn btn-danger col-md-5" href="/account/deactivate">Deactivate your Account</a>
                <p class="col-md-7" >Here you can deactivate your Account. The Account is deactivated until the next login.</p>
            </div>

        </div>
    </div>
</div><!--/container-->
<!--=== End Content Part ===-->