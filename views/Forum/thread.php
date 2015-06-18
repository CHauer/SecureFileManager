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
            <li>
                <time class="cbp_tmtime" datetime=""><span>4/1/08</span> <span>January</span></time>
                <i class="cbp_tmicon rounded-x hidden-xs"></i>
                <div class="cbp_tmlabel">
                    <h2>Our first step</h2>
                    <div class="row">
                        <div class="col-md-4">
                            <img class="img-responsive" src="assets/img/main/img1.jpg" alt="">
                            <div class="md-margin-bottom-20"></div>
                        </div>
                        <div class="col-md-8">
                            <p>Winter purslane courgette pumpkin quandong komatsuna fennel green bean cucumber watercress. Pea sprouts wattle seed rutabaga okra yarrow cress avocado grape.</p>
                            <p>Cabbage lentil cucumber chickpea sorrel gram garbanzo plantain lotus root bok choy squash cress potato.</p>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <time class="cbp_tmtime" datetime=""><span>7/2/09</span> <span>February</span></time>
                <i class="cbp_tmicon rounded-x hidden-xs"></i>
                <div class="cbp_tmlabel">
                    <h2>First achievements</h2>
                    <p>Caulie dandelion maize lentil collard greens radish arugula sweet pepper water spinach kombu courgette lettuce. Celery coriander bitterleaf epazote radicchio shallot winter purslane collard greens spring onion squash lentil. Artichoke salad bamboo shoot black-eyed pea brussels sprout garlic kohlrabi.</p>
                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check color-green"></i> Donec id elit non mi porta gravida</li>
                                <li><i class="fa fa-check color-green"></i> Corporate and Creative</li>
                                <li><i class="fa fa-check color-green"></i> Responsive Bootstrap Template</li>
                                <li><i class="fa fa-check color-green"></i> Corporate and Creative</li>
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check color-green"></i> Donec id elit non mi porta gravida</li>
                                <li><i class="fa fa-check color-green"></i> Corporate and Creative</li>
                                <li><i class="fa fa-check color-green"></i> Responsive Bootstrap Template</li>
                                <li><i class="fa fa-check color-green"></i> Corporate and Creative</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <div class="panel panel-light-green margin-bottom-20">
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
        </div>
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