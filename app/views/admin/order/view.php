<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Order №<?=$order['id'];?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= ADMIN; ?>"> Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= ADMIN; ?>/order"> Orders list</a></li>
                    <li class="breadcrumb-item active"> Order №<?=$order['id'];?></li>
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
                            <tbody>
                                <tr>
                                    <td>Order №</td>
                                    <td><?=$order['id'];?></td>
                                </tr>
                                <tr>
                                    <td>Order date</td>
                                    <td><?=$order['date'];?></td>
                                </tr>
                                <tr>
                                    <td>Order update</td>
                                    <td><?=$order['update_at'] ? $order['update_at'] : '--'; ?></td>
                                </tr>
                                <tr>
                                    <td>Quantity products</td>
                                    <td>--</td>
                                </tr>
                                <tr>
                                    <td>Order total</td>
                                    <td><?=$order['sum'];?> <?=$order['currency'];?></td>
                                </tr>
                                <tr>
                                    <td>Order name</td>
                                    <td><?=($order['name'] != '') ? $order['name'] : 'Guest';?></td>
                                </tr>
                                <tr>
                                    <td>Order status</td>
                                    <td><?=$order['status'] ? 'Completed' : 'New';?></td>
                                </tr>
                                <tr>
                                    <td>Order note</td>
                                    <td><?=$order['note'];?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h3>Order details</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $qty = 0; foreach ($orderProducts as $product): ?>
                                <tr>
                                    <td><?=$product->id;?></td>
                                    <td><?=$product->title;?></td>
                                    <td><?=$product->qty; $qty += $product->qty;?></td>
                                    <td><?=$product->price;?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="active">
                                <td colspan="2">
                                    <b>Total:</b>
                                </td>
                                <td><b><?=$qty;?></b></td>
                                <td><b><?=$order['sum'];?> <?=$order['currency'];?></b></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->