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
    ?>

    <h1>Showing thread!</h1>
        <?
        if(!is_null($thread)) {
            echo "<p>" . $thread->Title . "</p>";
        }
    }
    ?>

</div>