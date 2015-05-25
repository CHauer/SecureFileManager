<div class="container content">
    <?
    if($viewModel->exists("error")){
        echo '<h3 class="color-red">' . $viewModel->get("error") . '</h3>';
    }
    ?>

    <h1>Forum!</h1>
    <p>A forum is  gonna be here soon!</p>

    <div class="row md-margin-bottom-30">
        <h4>Start a new thread.</h4>
        <p><a class="color-green" href="/forum/newThread">click here</a> to start a new thread.</p>
    </div>
</div>