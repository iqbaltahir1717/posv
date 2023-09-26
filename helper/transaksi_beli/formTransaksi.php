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

    $sql = "SELECT barang.id_barang as idb, barang.gambar, keranjang_beli.* 
                FROM keranjang_beli  
                LEFT JOIN barang 
                ON keranjang_beli.id_barang=barang.id 
                WHERE id_member = ?
                ORDER BY keranjang_beli.id ASC";
    $row = $connectdb->prepare($sql);
    $row->execute(array($users->id));
    $hasil = $row->fetchAll(PDO::FETCH_OBJ);
    $grantotal = 0;
    foreach($hasil as $r) {
        $grantotal += ($r->beli * $r->jumlah);
    }
    ?>
<form method="post" id="prosesTransaksi">
    <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-6 text-right">
            <div class="row">
                <?php
                    $sql = "SELECT * FROM pembelian ORDER BY id DESC LIMIT 1";
                    $row = $connectdb->prepare($sql);
                    $row->execute();
                    $edit = $row->fetch(PDO::FETCH_OBJ);
                    if (isset($edit->id)) {
                        $cekid = $edit->id + 1;
                    } else {
                        $cekid = 1;
                    }
                    $kode = 'OR00'.$cekid;
                ?>
                <div class="col-sm-6 mb-3">
                    <h5 class="text-right pt-1">No Transaksi</h5>
                </div>
                <div class="col-sm-6 mb-3">
                    <input type="text" autocomplete="off" class="form-control" required name="notrx"
                        value="<?= $kode;?>">
                </div>
                <div class="col-sm-6 mb-3">
                    <h5 class="text-right pt-1">Grand Total</h5>
                </div>
                <div class="col-sm-6 mb-3">
                    <h5>Rp<?=number_format($grantotal);?>,-</h5>
                </div>
                <div class="col-sm-6 mb-3">
                    <h5 class="text-right pt-1">Supplier</h5>
                </div>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <select class="form-control" required name="supplier" id="supplier">
                            <option value="">- pilih -</option>
                            <?php
                                $no =1;
                                $sql = "SELECT * FROM supplier ORDER BY id DESC";
                                $row = $connectdb->prepare($sql);
                                $row->execute();
                                $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                                foreach ($hasil as $r) {
                            ?>
                            <option><?= $r->nama_supplier; ?></option>
                            <?php  }?>
                        </select>
                    </div>
                    <input type="hidden" autocomplete="off" class="form-control" name="total" value="<?= $grantotal;?>">
                </div>
                <div class="col-sm-6 mb-3">
                    <!-- <p class="text-right">Aksi</p>   -->
                </div>
                <div class="col-sm-6 mb-3">
                    <button type="submit" name="" id="" class="btn btn-primary btn-lg btn-block">
                        <i class="fa fa-save mr-1"></i> Order Barang
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="modal fade" id="modelIdDetail" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="detail_html"></div>
        </div>
    </div>
</div>
<script>
// AJAX call for autocomplete 
$(document).ready(function() {
    $('#prosesTransaksi').submit(function(e) {
        e.preventDefault();
        $.ajax({
            'type': 'POST',
            'url': "<?= $baseURL.'proses.php?aksi=tambah';?>",
            'data': new FormData(this),
            'contentType': false,
            'cache': false,
            'processData': false,
            'dataType': 'json',
            'timeout': 60000,
            'beforeSend': function() {
                Swal.fire({
                    title: 'Loading...',
                    didOpen: () => {
                        Swal.showLoading();
                    },
                })
            },
            'success': function(data) {
                Swal.close();
                if (data.cek == 'error') {
                    Swal.fire({
                        title: 'Failed !',
                        html: data.msg,
                        icon: 'warning',
                        confirmButtonText: 'Oke',
                    });
                } else {
                    var id = data.id;
                    $('#modelIdDetail').modal('show');
                    $.ajax({
                        type: "GET",
                        url: "<?= $baseURL."../cetak/detail_beli.php"?>",
                        data: {
                            no: id
                        },
                        success: function(html) {
                            if (html == '404') {
                                Swal.fire({
                                    title: 'Failed !',
                                    html: "Data Tidak Ditemukan !",
                                    icon: 'warning',
                                    confirmButtonText: 'Oke',
                                });
                            } else {
                                $('#detail_html').html(html);
                            }
                        }
                    });
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
    });
    $('#modelIdDetail').on('hidden.bs.modal', function() {
        window.location.reload();
    });
});
</script>