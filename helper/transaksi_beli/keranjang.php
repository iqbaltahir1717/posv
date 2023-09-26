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
                <th>No</th>
                <!-- <th>Gambar</th> -->
                <th>Barang</th>
                <th>Harga_Jual</th>
                <th>Harga_Beli</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $no =1;
                $sql = "SELECT barang.id as idba, barang.id_barang as idb, barang.gambar, keranjang_beli.* 
                        FROM keranjang_beli  
                        LEFT JOIN barang 
                        ON keranjang_beli.id_barang=barang.id 
                        WHERE id_member = ?
                        ORDER BY keranjang_beli.id ASC";
                $row = $connectdb->prepare($sql);
                $row->execute(array($users->id));
                $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                $grantotal = 0;
                foreach ($hasil as $r) {
            ?>
            <tr>
                <td><?= $no; ?></td>
                <!-- <td>
                    <a href="<?= url_images($baseURL, 'barang', $r->gambar); ?>" data-toggle="lightbox"
                        data-title="<?=$r->nama_barang; ?>" data-gallery="gallery">
                        <img src="<?= url_images($baseURL, 'barang/', $r->gambar); ?>" alt="<?=$r->nama_barang; ?>"
                            class="img-fluid" width="80" />
                    </a>
                </td> -->
                <td>(<?=$r->idb; ?>) <br> <?=$r->nama_barang; ?></td>
                <td>
                    <input type="number" class="form-control" name="jual" style="width:150px !important;"
                        data-id="<?= $r->id;?>" id="klikJual<?= $r->id?>" value="<?=$r->jual; ?>">
                </td>
                <td>
                    <input type="number" class="form-control" name="beli" style="width:150px !important;"
                        data-id="<?= $r->id;?>" id="klikBeli<?= $r->id?>" data-qty="<?= $r->jumlah;?>"
                        data-jual="<?=$r->jual; ?>" data-barang="<?= $r->idba;?>" value="<?=$r->beli; ?>">
                </td>
                <td>
                    <input type="number" style="width:80px !important;" min="1" class="form-control" name="qty"
                        data-id="<?= $r->id;?>" id="klikQty<?= $r->id?>" data-barang="<?= $r->idba;?>"
                        data-beli="<?=$r->beli; ?>" data-jual="<?=$r->jual; ?>" value="<?=$r->jumlah; ?>">
                </td>
                <td>Rp<?= number_format(($r->beli * $r->jumlah) ?? 0);?>,-</td>
                <td>
                    <a href="<?= "proses.php?aksi=delete&id=".$r->id;?>" class="btn btn-danger btn-sm" title="Delete">
                        <i class="fa fa-times"></i>
                    </a>
                </td>
            </tr>
            <?php $no++; $grantotal += ($r->beli * $r->jumlah); }?>
        </tbody>
    </table>
</div>

<?php foreach($hasil as $r) { ?>
<script>
$('#klikBeli<?= $r->id;?>').on("change", function(e) {
    var id = $(this).attr('data-id');
    var qty = $(this).attr('data-qty');
    var id_barang = $(this).attr('data-barang');
    var jual = $(this).attr('data-jual');
    var beli = $(this).val();
    ajaxKeranjang(id_barang, qty, beli, jual, id);
});
$('#klikQty<?= $r->id;?>').on("change", function(e) {
    var id = $(this).attr('data-id');
    var qty = $(this).val();
    var id_barang = $(this).attr('data-barang');
    var jual = $(this).attr('data-jual');
    var beli = $(this).attr('data-beli');
    ajaxKeranjang(id_barang, qty, beli, jual, id);
});

$('#klikJual<?= $r->id;?>').on("change", function(e) {
    var id = $(this).attr('data-id');
    var jual = $(this).val();
    ajaxKeranjangJual(jual, 'retail', id);
});
</script>
<?php }?>
<script>
function ajaxKeranjangJual(jual, tipe, id) {
    $.ajax({
        'type': 'POST',
        'url': '<?= $baseURL.'proses.php?aksi=edit_jual';?>',
        'data': {
            'tipe': tipe,
            "jual": jual,
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

function ajaxKeranjang(id_barang, qty, beli, jual, id) {
    $.ajax({
        'type': 'POST',
        'url': '<?= $baseURL.'proses.php?aksi=edit_keranjang';?>',
        'data': {
            "id_barang": id_barang,
            "qty": qty,
            "beli": beli,
            "jual": jual,
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