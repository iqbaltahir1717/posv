<?php
session_start();
include '../../setting.php';
include '../../helper.php';
if(!empty($_SESSION['codekop_session'])) {
    $uid =  (int)$_SESSION['codekop_session']['id'];
    $sql_users = "SELECT * FROM users WHERE id = ?";
    $row_users = $connectdb->prepare($sql_users);
    $row_users->execute(array($uid));
    $users = $row_users->fetch(PDO::FETCH_OBJ);
} else {
    redirect($baseURL.'login.php');
}

$id =  getGet("no", true);
$sql = "SELECT users.name, pembelian.* 
            FROM pembelian 
            LEFT JOIN users 
            ON pembelian.id_member = users.id 
            WHERE pembelian.no_trx = ?";
$row = $connectdb->prepare($sql);
$row->execute(array($id));
$edit = $row->fetch(PDO::FETCH_OBJ);
if(empty($edit)) {
    redirect($baseURL.'index.php');
}

$sql_toko =  "SELECT * FROM toko WHERE id = 1";
$row_toko = $connectdb->prepare($sql_toko);
$row_toko->execute();
$toko = $row_toko->fetch(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="id" moznomarginboxes mozdisallowselectionprint>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Software pembelian">
    <meta name="author" content="Codekop">

    <title>Cetak Nota</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet"
        href="<?= $baseURL;?>../../assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="<?= $baseURL;?>../../assets/dist/css/adminlte.min.css">
    <style>
    * {
        font-size: 12pt;
        font-family: 'Arial';
    }

    td,
    th,
    tr,
    table {
        border-collapse: collapse;
    }

    td.description,
    th.description {
        text-align: left;
        width: 275px;
        max-width: 200px;
    }

    td.no,
    th.no {
        width: 40px;
        max-width: 40px;
        text-align: center;
        word-break: break-all;
    }

    td.quantity,
    th.quantity {
        width: 50px;
        max-width: 50px;
        text-align: center;
        word-break: break-all;
    }

    td.price,
    th.price {
        width: 150px;
        max-width: 150px;
        word-break: break-all;
    }

    .centered {
        text-align: center;
        align-content: center;
    }

    .ticket {
        max-width: 400px;
    }

    img {
        max-width: inherit;
        width: inherit;
    }

    @media print {

        .hidden-print,
        .hidden-print * {
            display: none !important;
        }
    }
    </style>
</head>

<body class="hold-transition sidebar-collapse" style="-webkit-print-color-adjust: exact !important;"
    onload="window.print()">
    <div class="wrapper">
        <section class="content">
            <div class="container">
                <div id="laporan">
                    <div class="mt-4">
                        <h4 class="text-center font-weight-bold">
                            <?= $toko->nama_toko;?>
                        </h4>
                        <p class="text-center"><?= $toko->alamat_toko;?> <br><?= $toko->tlp;?></p>
                        <div class="table-resposive-sm">
                            <span class="float-left">Akun : <?= $edit->name;?></span>
                            <br>
                            <span class="float-left">Supplier : <?= $edit->nm_supplier;?></span>
                            <span class="float-right"><?= $edit->created_at;?></span>
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="font-weight-bold">
                                        <th class="quantity">No</th>
                                        <th>KETERANGAN</th>
                                        <th>JML</th>
                                        <th>HARGA</th>
                                        <th class="price">SUB TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $no =1;
                                        $sql = "SELECT * FROM pembelian_detail WHERE no_trx = ? ORDER BY id ASC";
                                        $row = $connectdb->prepare($sql);
                                        $row->execute(array($id));
                                        $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                                        foreach($hasil as $r) {
                                    ?>
                                    <tr>
                                        <td><?= $no;?></td>
                                        <td>(<?=$r->idb;?>) - <?=$r->nama_barang;?></td>
                                        <td><?=$r->qty;?></td>
                                        <td>Rp<?=number_format($r->beli);?>,-</td>
                                        <td>Rp<?=number_format($r->total);?>,-</td>
                                    </tr>
                                    <?php $no++; }?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total Pembelian</th>
                                        <th>Rp<?= number_format($edit->beli ?? 0);?>,</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <p class="text-center mt-4"> </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>

</html>