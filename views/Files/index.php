<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left">Upload List</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li><a href="">Pages</a></li>
            <li class="active">Upload List</li>
        </ul>
    </div>
    <!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container content">
    <div class="job-img-inputs">
        <div class="row">
            <!-- Serach -->
            <div class="col-sm-4 md-margin-bottom-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                    <input type="text" placeholder="which user you are looking for" class="form-control">
                </div>
            </div>
            <div class="col-sm-4">
                <button type="button" class="btn-u btn-block btn-u-dark"> Search User</button>
            </div>
            <div class="col-sm-4">
                <ul class="list-inline clear-both">
                    <li class="sort-list-btn">
                        <h3>Show :</h3>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                20 <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">All</a></li>
                                <li><a href="#">10</a></li>
                                <li><a href="#">5</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
<!--        --><?php
//        foreach($this->_['files'] as $file){
//        ?>
<!--        <div class="col-md-9">-->
<!--            <!-- News v3 -->-->
<!--            <div class="row margin-bottom-20">-->
<!--                <div class="col-sm-7 news-v3">-->
<!--                    <div class="news-v3-in-sm no-padding">-->
<!--                        <ul class="list-inline posted-info">-->
<!--                            <li>By XYZ</li>-->
<!--                            <li>Uploaded --><?php //echo $file['Uploaded']; ?><!--</li>-->
<!--                        </ul>-->
<!--                        <h2>--><?php //echo $file['Name']; ?><!--</h2>-->
<!--                        <p>--><?php //echo $file['Description']; ?><!--</p>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        --><?php
//        }
//        ?>
    </div>
</div><!--/container-->
<!--=== End Content Part ===-->