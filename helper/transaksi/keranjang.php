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
?>
<div class="table-responsive pt-4">
    <table class="table table-striped text-sm table-sm" id="example5">
        <thead>
            <tr>
                <!-- <th>No</th> -->
                <th>ID Barang</th>
                <!-- <th>Image</th> -->
                <th>Barang</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Diskon ( Rp )</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $no =1;
                $sql = "SELECT barang.id as idba, barang.id_barang as idb, barang.harga_jual, barang.gambar, keranjang.* 
                        FROM keranjang  
                        LEFT JOIN barang 
                        ON keranjang.id_barang=barang.id 
                        WHERE id_member = ?
                        ORDER BY keranjang.id ASC";
                $row = $connectdb->prepare($sql);
                $row->execute(array($users->id));
                $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                $grantotal = 0;
                foreach($hasil as $r) {
            ?>
            <tr>
                <!-- <td><?= $no;?></td> -->
                <td><?=$r->idb;?></td>
                <!-- <td>
                    <a href="<?= url_images($baseURL, 'barang', $r->gambar);?>"
                        data-toggle="lightbox" data-title="<?=$r->nama_barang;?>"
                        data-gallery="gallery">
                        <img src="<?= url_images($baseURL, 'barang/', $r->gambar);?>"
                            alt="<?=$r->nama_barang;?>" class="img-fluid" width="80" />
                    </a>
                </td> -->
                <td><?=$r->nama_barang;?></td>
                <td>
                    <?=$r->harga_jual;?>
                </td>
                <!-- <form method="post" action="proses.php?aksi=editkeranjang"> -->
                <td>
                    <input type="number" class="form-control form-control-sm" name="qty" data-id="<?= $r->id;?>"
                        data-diskon="<?= $r->diskon;?>" data-barang="<?= $r->idba;?>" id="qtyCart<?= $r->id;?>" min="1"
                        style="width:80px !important;" value="<?=$r->jumlah;?>">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" name="diskon" data-id="<?= $r->id;?>"
                        data-qty="<?= $r->jumlah;?>" id="discCart<?= $r->id;?>" data-barang="<?= $r->idba;?>" min="0"
                        style="width:150px !important;" value="<?=$r->diskon;?>">
                </td>
                <td>Rp<?= number_format((($r->jual * $r->jumlah) - $r->diskon) ?? 0);?>,-
                </td>
                <td>
                    <input type="hidden" value="<?=$r->id;?>" class="form-control" name="id" id="id">
                    <input type="hidden" value="<?=$r->id_barang;?>" class="form-control" name="id_barang"
                        id="id_barang">
                    <div class="btn-group">
                        <!-- <button type="submit" class="btn btn-success btn-sm" title="Edit Keranjang">
                            <i class="fa fa-edit"></i>
                        </button> -->
                        <a href="<?= "proses.php?aksi=delete&id=".$r->id;?>" class="btn btn-danger btn-sm"
                            title="Delete">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                    <!-- <button type="submit" class="btn btn-success d-lg-none btn-sm mb-2"
                            title="Edit Keranjang">
                            <i class="fa fa-edit"></i>
                        </button> -->
                </td>
                <!-- </form> -->
            </tr>
            <?php $no++; $grantotal += ($r->jual * $r->jumlah) - $r->diskon; }?>
        </tbody>
    </table>
</div>
<?php foreach($hasil as $r) { ?>
<script>
$('#qtyCart<?= $r->id;?>').on("change", function(e) {
    var qty = $(this).val();
    var diskon = $(this).attr('data-diskon');
    var id = $(this).attr('data-id');
    var id_barang = $(this).attr('data-barang');
    ajaxKeranjang(id_barang, qty, diskon, id);
});
$('#discCart<?= $r->id;?>').on("change", function(e) {
    var diskon = $(this).val();
    var qty = $(this).attr('data-qty');
    var id = $(this).attr('data-id');
    var id_barang = $(this).attr('data-barang');
    ajaxKeranjang(id_barang, qty, diskon, id);
});
</script>
<?php }?>
<script>
function ajaxKeranjang(id_barang, qty, diskon, id) {
    $.ajax({
        'type': 'POST',
        'url': '<?= $baseURL.'proses.php?aksi=edit_keranjang';?>',
        'data': {
            "id_barang": id_barang,
            "qty": qty,
            "diskon": diskon,
            "id": id
        },
        'dataType': 'json',
        'timeout': 60000,
        'success': function(data) {
            Swal.close();
            if (data.cek == 'error') {
                Swal.fire({
                    title: 'Failed !',
                    html: data.msg,
                    icon: 'warning',
                    confirmButtonText: 'Ok',
                });
            } else {
                $('#keranjangTransaksi').load('<?= $baseURL.'keranjang.php';?>');
                $('#formTransaksi').load('<?= $baseURL.'formTransaksi.php';?>');
            }
        },
        'error': function(xmlhttprequest, textstatus, message) {
            Swal.fire({
                title: 'Error Request Timeout !',
                text: message,
                icon: 'error',
                confirmButtonText: 'Oke',
            })
        }

    });
}
</script>