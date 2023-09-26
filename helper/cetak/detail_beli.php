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
<div class="modal-body">
    <div class="table-responsive">
        <h4 class="text-center font-weight-bold">
            <?= $toko->nama_toko;?>
        </h4>
        <p class="text-center"><?= $toko->alamat_toko;?> <br><?= $toko->tlp;?></p>
        <div class="table-resposive-sm">
            <span class="float-left">Akun : <?= $edit->name;?></span>
            <br>
            <span class="float-left">Supplier : <?= $edit->nm_supplier;?></span>
            <span class="float-right"><?= $edit->created_at;?></span>
            <div class="clearfix mt-3"></div>
            <table class="table table-bordered table-sm w-100">
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
                        $sql = "SELECT * FROM pembelian_detail 
                                                    WHERE no_trx = ?
                                                    ORDER BY id ASC";
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
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <a href="<?= "../helper/cetak/cetak_beli.php?no=".$id?>" target="_blank" class="btn btn-primary">
        <i class="fas fa-print mr-1"></i> Print</a>
</div>