<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">View thread</h1>
        <ul class="pull-right breadcrumb">
            <li>Administrator</li>
            <li class="active">Users</li>
        </ul>
    </div>
    <!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<?

$users = $viewModel->get("model");

?>


<!--=== Content Part ===-->
<div class="container content">
    <h2>Users</h2>
    <div class="panel panel-light-green margin-bottom-20">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> User</h3>
        </div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Picture</th>
                <th>Username</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>BirthDate</th>
                <th>EMail</th>
                <th>LockoutEnabled</th>
                <th>LockoutEndDate</th>
                <th>Deactivated</th>
                <th>Role</th>
            </tr>
            </thead>
            <tbody>
            <?
            if (!is_null($users)) {
                foreach ($users as $user) {
                    ?>
                    <tr>
                        <td><img src="<? echo $user->PictureLink ?>" style="width:100px;" /></td>
                        <td><? echo $user->Username ?></td>
                        <td><? echo $user->Firstname ?></td>
                        <td><? echo $user->Lastname ?></td>
                        <td><? echo $user->BirthDate ?></td>
                        <td><? echo $user->EMail ?></td>
                        <td><? echo $user->LockoutEnabled ?></td>
                        <td><? echo $user->LockoutEndDate ?></td>
                        <td><? echo $user->Deactivated ?></td>
                        <td><? echo $user->Role->Name ?></td>
                    </tr>
                <?
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>