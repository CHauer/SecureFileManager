<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">View thread</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="/forum/index">Forum</a></li>
            <li class="active">View thread</li>
        </ul>
    </div>
    <!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container content">
    <?
    $thread = null;

    if($viewModel->exists("thread"))
    {
        $thread = $viewModel->get("thread");
    }

    $entries = null;

    if($viewModel->exists("entries"))
    {
        $entries = $viewModel->get("entries");
    }
    ?>

    <?
    if($viewModel->exists("error")){
        echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
    } else {
        if(isset($_SESSION["redirectError"])) {
            echo '<h3 class="color-red">' . $_SESSION["redirectError"] . '</h3>';
            $_SESSION["redirectError"] = null;
        }

        if(isset($_SESSION["redirectSuccess"])) {
            echo '<h3 class="color-green">' . $_SESSION["redirectSuccess"] . '</h3>';
            $_SESSION["redirectSuccess"] = null;
        }

        if(!is_null($thread)) {
            echo "<h1>" . $thread->Title ."</h1>";
            echo '<p class="margin-bottom-10">' . $thread->Description . '</p>';
            if(IsThreadOwner($thread->ForumThreadId, $_SESSION["userid"])) {
                echo '<a href="/forum/delete/' . $thread->ForumThreadId . '"><span class="glyphicon glyphicon-remove btn-u-red margin-bottom-30" style="font-size:18px;">Delete</span></a>';
            }
        }
        ?>
        <ul class="timeline-v2">
            <?
            if (!is_null($entries)) {
                foreach ($entries as $data) {
                ?>
                <li>
                    <time class="cbp_tmtime" datetime=""><span style="padding-top: 8px;"><? echo $data["Created"] ?></span></time>
                    <i class="cbp_tmicon rounded-x hidden-xs"></i>
                    <div class="cbp_tmlabel">
                        <h2><? echo '<a href="/account/showprofile/' . $data["UserId"] . '">' . $data["Username"] . '</a>'; ?></h2>
                        <div class="row">
                            <div class="col-md-3">
                                <img class="img-responsive" src="<? echo $data["PictureLink"] ?>" alt="userimage" style="max-width: 50px;">
                                <div class="md-margin-bottom-20"></div>
                            </div>
                            <div class="col-md-9">
                                <p><? echo $data["Message"] ?></p>
                            </div>
                        </div>
                        <?
                        if($_SESSION["userid"] == $data["UserId"]) { ?>
                            <hr/>
                            <div class="row">
                            <? echo '<a href="/forum/deleteEntry/' . $data["EntryId"] . '"><span class="glyphicon glyphicon-remove color-red" >Delete</span></a>'; ?>
                            </div>
                            <?
                        }
                        ?>
                    </div>
                </li>
                <?
                }
            }
            ?>
        <? /* <div class="panel panel-light-green margin-bottom-20">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-tasks"></i> Answers</h3>
            </div>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Message</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?
                if (!is_null($entries)) {
                    foreach ($entries as $data) {
                        ?>
                        <tr>
                            <td><? echo $data["Message"] ?></td>
                            <td><? echo $data["Created"] . ' by <a href="/account/showprofile/' . $data["UserId"] . '">' . $data["Username"] . '</a>' ?></td>
                            <td>
                                <?
                                if($_SESSION["userid"] == $data["UserId"]) {
                                    echo '<a href="/forum/deleteEntry/' . $data["EntryId"] . '"><span class="glyphicon glyphicon-remove btn-u-red">Delete</span></a>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?
            }
            }
                ?>
                </tbody>
            </table>
        </div> */ ?>
        <?
        $userrepo = new UserRepository();
        $rolerepo = new RoleRepository();

        $user = $userrepo->GetUser(intval($_SESSION['userid']));
        $user->Role = $rolerepo->GetRole($user->RoleId);


        if ($user->Role->WriteForum) {
        ?>
        <form class="reg-page" action="" method="post">
            <div class="reg-header">
                <h2>Post a new answer</h2>
            </div>

            <div class="form-group <? ValidationErrorClass("Message", $viewModel) ?> ">
                <label>Message <span class="color-red">*</span></label>
                <input type="text" name="Message" class="form-control margin-bottom-20" maxlength="200">
                <? ValidationErrorMessage("Message", $viewModel) ?>
            </div>

            <div class="col-lg-6 text-right">
                <button class="btn-u" type="submit">Post answer</button>
            </div>
        </form>

    <?
        }
    }
    ?>
    <div class="col-lg-6 text-right">
        <p><a class="color-green" href="/forum/index">Return to forum.</a></p>
    </div>
</div>