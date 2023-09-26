<!-- Page content -->
<div class="row">
    <div class="col-sm-6 mx-auto">
        <?php if (!empty(flashdata())) {
            echo flashdata();
        }?>
        <div class="card mb-3">
            <!-- Card header -->
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fa fa-print mr-1"></i>
                    Print Struk
                </h4>
            </div>
            <div class="card-body">
                Transaksi Telah Berhasil Silahkan Cetak Faktur Pembelian !
                <h5 class="mt-3">Total Belanja : <b>Rp<?= number_format($_GET['kembali']);?>,-</b></h5>
            </div>
            <div class="card-footer">
                <div class="btn-group">
                    <a class="btn btn-danger" href="<?= $baseURL.'transaksi_beli/index.php';?>">
                        <span class="fas fa-backspace mr-2"></span>Kembali</a>
                    <a class="btn btn-success" href="<?= $baseURL.'helper/cetak/cetak_beli.php?no='.$_GET['notrx'];?>"
                        target="_blank">
                        <span class="fas fa-print mr-2"></span>Cetak</a>
                </div>
            </div>
        </div>
    </div>
</div>