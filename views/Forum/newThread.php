<?
if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
?>
    <p>$_POST["Name"]</p>
<?
} else {
    ?>

    <div class="container content">
        <div class="panel panel-blue margin-bottom-40">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-tasks"></i> Post a new thread</h3>
            </div>
            <div class="panel-body">
                <form class="margin-bottom-40" role="form">
                    <div class="form-group">
                        <label for="">Title</label>
                        <input class="form-control" id="inputTitle" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label for="inputDescription">Description</label>
                        <textarea class="form-control" id="inputDescription" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn-u btn-u-blue">Post</button>
                </form>
            </div>
        </div>
    </div>
<?
}
?>
