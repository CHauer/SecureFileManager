<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">View thread</h1>
        <ul class="pull-right breadcrumb">
            <li>Administrator</li>
            <li class="active">Log</li>
        </ul>
    </div>
    <!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<?
$logs = $viewModel->get("model");
?>

<!--=== Content Part ===-->
<div class="container content">
    <h2>Users</h2>
    <div class="panel panel-red margin-bottom-20">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> User</h3>
        </div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Created</th>
                <th>Message</th>
                <th>Typ</th>
            </tr>
            </thead>
            <tbody>
            <?
            if (!is_null($logs))
            {
                foreach ($logs as $log)
                {
                    ?>
                    <tr>
                        <td><? echo EchoModelValue($log, 'Created') ?></td>
                        <td><? echo EchoModelValue($log, 'Message') ?></td>
                        <td><? echo GetLogTypeName(EchoModelValue($log, 'Typ')) ?></td>
                    </tr>
                <?
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>