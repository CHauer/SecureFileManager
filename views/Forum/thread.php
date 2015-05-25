<div class="container content">
    <?
    $thread = null;

    if($viewModel->exists("thread"))
    {
        $thread = $viewModel->get("thread");
    }
    ?>

    <?
    if($viewModel->exists("error")){
        echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
    } else {
        if($viewModel->exists("success")) {
            echo '<h3 class="color-green">' . $viewModel->get("success") . '</h3>';
        }

        if(!is_null($thread)) {
            echo "<h1>" . $thread->Title ."</h1>";
            echo "<p>" . $thread->Description . "</p>";
        }
    }
    ?>

</div>