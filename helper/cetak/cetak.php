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
    $sql = "SELECT users.name, pelanggan.nama_pelanggan, penjualan.* 
                FROM penjualan 
                LEFT JOIN users 
                ON penjualan.id_member = users.id 
                LEFT JOIN pelanggan 
                ON penjualan.id_pelanggan=pelanggan.id
                WHERE penjualan.no_trx = ?";
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Print Struk</title>
    <style>
    html {
        font-family: sans-serif;
        font-size: 7pt;
        line-height: 8pt !important;
    }

    p {
        line-height: 3pt !important;
    }

    table {
        width: 100%;
        margin: 0;
        font-size: 7pt;
        line-height: 3pt;
    }

    tr td {
        padding-top: 3px;
    }

    .right {
        text-align: right;
    }

    center {
        margin: 0;
    }

    .doted {
        border-bottom: 1px dotted #333;
        width: 100%;
        margin-top: 3px;
        margin-bottom: 3px;
    }
    </style>

    <script>
    window.print();
    </script>
</head>

<body class="receipt">
    <section>
        --
        <center>
            <b> <?= $toko->nama_toko;?></b><br>
            <?= $toko->alamat_toko;?>
            <br>
        </center>
        Telp. <?= $toko->tlp;?>
        <div class="doted"></div>
        <table>
            <tr>
                <td>
                    TRX
                </td>
                <td>
                    :
                </td>
                <td>
                    <?= $edit->no_trx;?>
                </td>
            </tr>
            <tr>
                <td>
                    Kasir
                </td>
                <td>
                    :
                </td>
                <td>
                    <?= $edit->name;?>
                </td>
            </tr>
            <!-- <tr>
                <td>
                    Pelanggan
                </td>
                <td>
                    :
                </td>
                <td>
                    <?= $edit->nama_pelanggan ?? '-';?>
                </td>
            </tr> -->
            <tr>
                <td>
                    Tanggal
                </td>
                <td>
                    :
                </td>
                <td>
                    <?= $edit->created_at ?? '-';?>
                </td>
            </tr>
        </table>
        <div class="doted"></div>
        <table>
            <tr>
                <td><b>Harga</b></td>
                <td><b>Qty</b></td>
                <td><b>Total</b></td>
            </tr>
            <?php
                $no =1;
                $diskon = 0;
                $sql = "SELECT * FROM penjualan_detail 
                                    WHERE no_trx = ?
                                    ORDER BY id ASC";
                $row = $connectdb->prepare($sql);
                $row->execute(array($id));
                $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                foreach($hasil as $r) {
            ?>
            <tr>
                <td colspan="3"><?=$r->nama_barang;?></td>
            </tr>
            <tr>
                <td><?= number_format($r->jual);?> x </td>
                <td><?=$r->qty;?></td>
                <td>Rp<?=number_format($r->total + $r->diskon);?>,-</td>
            </tr>
            <?php $no++; $diskon += $r->diskon; }?>
        </table>
        <div class="doted"></div>
        <table>
            <tr>
                <td><b>Total Diskon</b></td>
                <td>:</td>
                <td>Rp<?= number_format($diskon ?? 0);?>,-</td>
            </tr>
            <tr>
                <td><b>Total Bayar</b></td>
                <td>:</td>
                <td>Rp<?= number_format($edit->total ?? 0);?>,-</td>
            </tr>
            <tr>
                <td><b>Dibayar</b></td>
                <td>:</td>
                <td>Rp<?= number_format($edit->bayar ?? 0);?>,-</td>
            </tr>
            <tr>
                <td><b>Kembali</b></td>
                <td>:</td>
                <td>Rp<?= number_format(($edit->bayar-$edit->total) ?? 0);?>,-</td>
            </tr>
        </table>
        <div class="doted"></div>
        <center>
            <b>TERIMA KASIH <br> ATAS KUNJUNGAN ANDA</b>
        </center>
        <br>
        <br>
    </section>
</body>
</html>