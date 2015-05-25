<div class="container content">
    <?
    if($viewModel->exists("error")){
        echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
    }
    ?>
    <?
    if(isset($_SESSION["redirectError"])){
        echo '<h3 class="color-red">' . $_SESSION["redirectError"] . '</h3>';
        $_SESSION["redirectError"] = null;
    }
    ?>

    <?
    $threads = null;

    if($viewModel->exists("threads"))
    {
        $threads = $viewModel->get("threads");
    }
    ?>

    <h1>Forum!</h1>
    <div class="panel panel-yellow margin-bottom-40">
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
                        <td><? echo $data["Created"] ?></td>
                        <td><? echo $data["EntryCount"] ?></td>
                    </tr>
                <?
                }
            }
            ?>
            </tbody>
        </table>
    </div>









    <?

    ?>
    <p>A forum is  gonna be here soon!</p>

    <div class="row md-margin-bottom-30">
        <h4>Start a new thread.</h4>
        <p><a class="color-green" href="/forum/newThread">click here</a> to start a new thread.</p>
    </div>
</div>