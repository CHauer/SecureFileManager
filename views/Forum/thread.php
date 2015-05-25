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
        if(isset($_SESSION["createdThread"])) {
            echo '<h3 class="color-green">' . $_SESSION["createdThread"] . '</h3>';
            $_SESSION["createdThread"] = null;
        }

        if(isset($_SESSION["createdEntry"])) {
            echo '<h3 class="color-green">' . $_SESSION["createdEntry"] . '</h3>';
            $_SESSION["createdEntry"] = null;
        }

        if(!is_null($thread)) {
            echo "<h1>" . $thread->Title ."</h1>";
            echo "<p>" . $thread->Description . "</p>";
        }
        ?>
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
                            <td><? echo $data["Created"] . " by " . $data["Username"] ?></td>
                            <td>

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