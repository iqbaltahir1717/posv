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

    $id =  strip_tags(getGet("no", true));
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
<div class="modal-body">
    <center>
        <b> <?= $toko->nama_toko;?></b><br>
        <?= $toko->alamat_toko;?>
        <br>
        Telp. <?= $toko->tlp;?>
    </center>
    <div class="clearfix mt-3"></div>
    <div class="table-responsive">
        <table class="w-100">
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
    </div>
    <div class="clearfix mt-3"></div>
    <div class="table-responsive">
        <table class="table table-bordered table-sm w-100">
            <tr>
                <td><b>Nama</b></td>
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
                <td><?=$r->nama_barang;?></td>
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
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <a href="<?= "../helper/cetak/cetak.php?no=".$id?>" target="_blank" class="btn btn-primary"><i
            class="fas fa-print mr-1"></i>
        Print</a>
</div>