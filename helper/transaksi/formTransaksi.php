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
    $sql = "SELECT barang.id_barang as idb, barang.gambar, keranjang.* 
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
        $grantotal += ($r->jual * $r->jumlah) - $r->diskon;
    }
?>
<form method="post" id="prosesTransaksi">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <?php
                    $sql = "SELECT * FROM penjualan ORDER BY id DESC LIMIT 1";
                    $row = $connectdb->prepare($sql);
                    $row->execute();
                    $edit = $row->fetch(PDO::FETCH_OBJ);
                    if(isset($edit->id)) {
                        $cekid = $edit->id + 1;
                    } else {
                        $cekid = 1;
                    }
                    $kode = 'TR00'.$cekid;
                ?>
                <div class="col-sm-6 mb-3">
                    <h6 class="text-right pt-1">No Transaksi</h6>
                </div>
                <div class="col-sm-6 mb-3">
                    <input type="text" autocomplete="off" class="form-control" required name="notrx"
                        value="<?= $kode;?>">
                </div>
                <div class="col-sm-6 mb-3">
                    <h6 class="text-right pt-1">Pelanggan <small class="text-danger">* Opsional</small></h6>
                </div>
                <div class="col-sm-4 mb-3">
                    <div class="form-group">
                        <select class="form-control select2" name="id_pelanggan" style="width: 100%;">
                            <option selected="selected" value="0">- pilih -</option>
                            <?php
                                $no =1;
                                $sql = "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC";
                                $row = $connectdb->prepare($sql);
                                $row->execute();
                                $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                                foreach($hasil as $r) {
                            ?>
                            <option value="<?= $r->id;?>"><?= $r->nama_pelanggan;?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2 mb-3">
                    <div class="form-group">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-md btn-block" data-toggle="modal"
                            data-target="#modelIdPelanggan">
                            <i class="fas fa-user-plus"></i>
                        </button>
                        <!-- Modal -->
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <h6 class="text-right mt-2">Grand Total</h6>
                </div>
                <div class="col-sm-6 mb-3">
                    <input type="text" id="GrandTotal" autocomplete="off" class="form-control" placeholder=""
                        name="grandtotal" required value="<?= number_format($grantotal, 0, '', '.');?>">
                </div>
                <div class="col-sm-6 mb-3">
                    <h6 class="text-right mt-2">Jumlah Bayar</h6>
                </div>
                <div class="col-sm-6 mb-3">
                    <input type="text" id="JmlTotal" autocomplete="off" class="form-control"
                        placeholder="Masukan Jumlah Uang" name="bayar" value="">
                </div>
                <div class="col-sm-6 mb-3">
                    <h6 class="text-right mt-2">Kembali</h6>
                </div>
                <div class="col-sm-6 mb-3">
                    <input type="text" value="0" autocomplete="off" class="form-control" id="kembaliJml" name="kembali"
                        value="">
                </div>
                <div class="col-sm-6 mb-3">
                    <!-- <p class="text-right">Aksi</p>   -->
                </div>
                <div class="col-sm-6 mb-3">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="fa fa-save mr-1"></i> Proses Transaksi
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
$('.dibayaraja').hide();
$(document).ready(function() {
    var JmlTotal = document.getElementById('JmlTotal');
    JmlTotal.addEventListener('keyup', function(e) {
        JmlTotal.value = formatRupiah(this.value, '');
    });
    var GrandTotal = document.getElementById('GrandTotal');
    GrandTotal.addEventListener('keyup', function(e) {
        GrandTotal.value = formatRupiah(this.value, '');
    });
});
</script>
<script>
// AJAX call for autocomplete 
$(document).ready(function() {

    $('#JmlTotal').bind("keyup change", function() {
        var GrandTotal = $("#GrandTotal").val();
        var HrgTot = GrandTotal.replace(/\D/g, '');
        var JmlTotal = $(this).val();
        var HrgJmlTotal = JmlTotal.replace(/\D/g, '');
        // hitung diskon, pajak, voucher
        var totalTot = (HrgJmlTotal - HrgTot);
        var totHv = totalTot;

        if (totHv < 1000) {
            var totTot = (totHv).toFixed();
        } else {
            var totTot = (totHv / 1000).toFixed(3);
        }
        $("#kembaliJml").val(totTot);
    });

    $('#prosesTransaksi').submit(function(e) {
        // alert('<?= $baseURL."../cetak/detail_jual.php"?>');
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
                        url: "<?= $baseURL."../cetak/detail_jual.php"?>",
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