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
                    <li class="breadcrumb-item active">Orders list</li>
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
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
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
                                        <td><?=$order['date'];?></td>
                                        <td><?=$order['update_at'] ? $order['update_at'] : '--'; ?></td>
                                        <td>
                                            <a href="<?=ADMIN;?>/order/view?id=<?=$order['id'];?>"><i class="fa fa-fw fa-eye"></i></a>
                                            <a href="<?=ADMIN;?>/order/delete?id=<?=$order['id'];?>" class="delete"><i class="fa fa-fw fa-window-close text-danger"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-center clearfix">
                            <p>(<?=count($orders);?> Orders)</p>
                            <?php if ($pagination->getCountPages() > 1): ?>
                                <?=$pagination;?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->