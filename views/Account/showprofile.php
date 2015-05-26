<?
if($viewModel->exists("model"))
{
    $model = $viewModel->get("model");

    $ownprofile=false;

    //check if own profile
    if($model->UserId == $viewModel->get("userid")){
        $ownprofile = true;
    }
}
?>

<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <? if($ownprofile){ ?>
            <h1 class="pull-left">Your Profile</h1>
        <?
        }
        else
        { ?>
            <h1 class="pull-left">Profile <? echo $model->Firstname . ' ' . $model->Lastname ?></h1>
        <? } ?>
        <ul class="pull-right breadcrumb">
            <li><a href="/home/index">Account</a></li>
            <li class="active"><a href="/account/manage">Profile</a></li>
        </ul>
    </div><!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container content">
    <div class="row portfolio-item margin-bottom-50">
        <!-- Content Info -->
        <div class="col-md-8 md-margin-bottom-40">
            <h2><? echo $model->Firstname . ' ' . $model->Lastname ?></h2>
            <div class="row portfolio-item1">
                <div class="col-xs-6">
                    <ul class="list-unstyled">
                        <li><i class="fa fa-user color-green"></i>Username:&nbsp;<? echo $model->Username?></li>
                        <li><i class="fa fa-birthday-cake color-green"></i>Birthdate:&nbsp;<? echo $model->BirthDate?></li>
                    </ul>
                </div>
                <div class="col-xs-6">
                    <ul class="list-unstyled">
                        <li><i class="fa fa-envelope color-green"></i>EMail:&nbsp;<? echo $model->EMail?></li>
                        <li><i class="fa fa-circle color-green"></i>Name:&nbsp;<? echo $model->Firstname . ' ' .$model->Lastname ?></li>
                    </ul>
                </div>
            </div>
            <p><? echo $model->Description?></p>

            <? if( $ownprofile){ ?>
                <a href="/account/editprofile" class="btn-u btn-u-large">Edit your profile</a>
            <? } ?>
        </div>
        <!-- End Content Info -->

        <!-- user picture -->
        <div class="col-md-4">
            <img alt="Picture Link" src="<? echo $model->PictureLink ?>">
        </div>
        <!-- End picture -->
    </div><!--/row-->

    <div class="tag-box tag-box-v2">
        <p></p>
    </div>
</div><!--/container-->
<!--=== End Content Part ===-->