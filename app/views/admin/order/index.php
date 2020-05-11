<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Orders list</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= ADMIN; ?>"> Home</a></li>
                    <li class="breadcrumb-item active"><a href="<?= ADMIN; ?>/order"> Orders list</a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>
                                    User
                                </th>
                                <th>
                                    status
                                </th>
                                <th>
                                    Amount
                                </th>
                                <th>
                                    Date added
                                </th>
                                <th>
                                    Date updated
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($orders as $order): ?>
                                <?php $class = $order['status'] ? ' class="success"' : ''; ?>
                                <tr<?=$class;?>>
                                    <td><?=$order['id'];?></td>
                                    <td><?=($order['name'] != '') ? $order['name'] : 'Guest';?></td>
                                    <td><?=$order['status'] ? 'Completed' : 'New'; ?></td>
                                    <td><?=$order['sum'];?></td>
                                    <td><?=$order['date_added'];?></td>
                                    <td><?=$order['date_updated'];?></td>
                                    <td><a href="<?=ADMIN;?>/order/view?id=1"><i class="fa fa-fw fa-eye"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <p>(<?=count($orders);?> Orders)</p>
                        <?php if ($pagination->getCountPages() > 1): ?>
                            <?=$pagination;?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->