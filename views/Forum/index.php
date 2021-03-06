<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">Forum</h1>
        <ul class="pull-right breadcrumb">
            <li class="active">Forum</li>
        </ul>
    </div>
    <!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container content">
    <?
    if($viewModel->exists("error")){
        echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
    }

    if(isset($_SESSION["redirectError"])){
        echo '<h3 class="color-red">' . $_SESSION["redirectError"] . '</h3>';
        $_SESSION["redirectError"] = null;
    }

    if(isset($_SESSION["redirectSuccess"])){
        echo '<h3 class="color-green">' . $_SESSION["redirectSuccess"] . '</h3>';
        $_SESSION["redirectSuccess"] = null;
    }

    $threads = null;

    if($viewModel->exists("threads"))
    {
        $threads = $viewModel->get("threads");
    }
    ?>
    <div class="panel panel-u margin-bottom-20">
        <div class="panel-heading
            <h3 class="panel-title"><i class="fa fa-tasks"></i> Threads</h3>
        </div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Created</th>
                <th>Answers</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?
            if (!is_null($threads)) {
                foreach ($threads as $data) {
                    ?>
                    <tr>
                        <td><? echo $data["Title"] ?></td>
                        <td><? echo $data["Description"] ?></td>
                        <td><? echo $data["Created"] . ' by <a href="/account/showprofile/' . $data["UserId"] . '">' . $data["Username"] . '</a>' ?></td>
                        <td><? echo $data["EntryCount"] ?></td>
                        <td>
                            <?
                            echo '<a href="/forum/thread/' . $data["ForumThreadId"] . '"><span class="glyphicon glyphicon-eye-open btn-u-red">View</span></a>&nbsp;&nbsp;&nbsp;';
                            if($_SESSION["userid"] == $data["UserId"]) {
                                echo '<a href="/forum/delete/' . $data["ForumThreadId"] . '"><span class="glyphicon glyphicon-remove btn-u-red">Delete</span></a>';
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
    $role = $viewModel->get("role");

    if($role->WriteForum)
    {
    ?>
        <div class="md-margin-bottom-30">
            <h4>Start a new thread.</h4>

            <p><a class="color-green" href="/forum/newThread">click here</a> to start a new thread.</p>
        </div>
    <?
    }
    ?>
</div>