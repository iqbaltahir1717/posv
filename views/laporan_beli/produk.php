<?php if(!empty(in_array($_SESSION['codekop_session']['akses'], [1,6]))){ }else{redirect($baseURL); }?>
<?php 
    $bulan_tes =array(
        '01'=>"Januari",
        '02'=>"Februari",
        '03'=>"Maret",
        '04'=>"April",
        '05'=>"Mei",
        '06'=>"Juni",
        '07'=>"Juli",
        '08'=>"Agustus",
        '09'=>"September",
        '10'=>"Oktober",
        '11'=>"November",
        '12'=>"Desember"
    );
?>
<!-- Page content -->
<div class="row">
    <div class="col-sm-12">
        <?php if(!empty(flashdata())){ echo flashdata(); }?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cari Laporan Per Bulan</h3>							
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="post" action="produk.php?page=laporan&cari=ok">
                    <div class="row">
                        <div class="col-sm-4">
                            <select name="bln" class="form-control">
                                <option selected="selected">Bulan</option>
                                <?php
                                    $bulan=array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                                    $jlh_bln=count($bulan);
                                    $bln1 = array('01','02','03','04','05','06','07','08','09','10','11','12');
                                    $no=1;
                                    for($c=0; $c<$jlh_bln; $c+=1){

                                        if(!empty(getGet('cari', true))){
                                            if(getPost('bln', true) == $bln1[$c]){
                                                echo"<option value='$bln1[$c]' selected> $bulan[$c] </option>";
                                            }else{
                                                echo"<option value='$bln1[$c]'> $bulan[$c] </option>";
                                            }
                                        }else{
                                            if(date('m') == $bln1[$c]){
                                                echo"<option value='$bln1[$c]' selected> $bulan[$c] </option>";
                                            }else{
                                                echo"<option value='$bln1[$c]'> $bulan[$c] </option>";
                                            }

                                        }
                                    $no++;}
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <?php
                                $now=date('Y');
                                echo "<select name='thn' class='form-control'>";
                                echo '
                                <option>Tahun</option>';
                                for ($a=2021;$a<=$now;$a++)
                                {
                                    if(!empty(getGet('cari', true))){
                                        if(getPost('thn', true) == $a){
                                            echo "<option value='$a' selected>$a</option>";
                                        }else{
                                            echo "<option value='$a'>$a</option>";
                                        }
                                    }else{
                                        if(date('Y') == $a){
                                            echo "<option value='$a' selected>$a</option>";
                                        }else{
                                            echo "<option value='$a'>$a</option>";
                                        }
                                    }
                                }
                                echo "</select>";
                            ?>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="periode" value="ya">
                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                <button class="btn btn-primary btn-flat">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="produk.php?page=laporan" class="btn btn-success btn-flat">
                                    <i class="fas fa-sync"></i> Refresh</a>
                                    
                                <?php if(!empty(getGet('cari', true))){?>
                                    <a href="<?= $baseURL;?>helper/cetak/excel_beli_produk.php?excel=yes&cari=yes&bln=<?=getPost('bln', true);?>&thn=<?=getPost('thn', true);?>" class="btn btn-info"><i class="fas fa-file-excel"></i>
                                    Excel</a>
                                    <a href="<?= $baseURL;?>helper/cetak/excel_beli_produk.php?cari=yes&bln=<?=getPost('bln', true);?>&thn=<?=getPost('thn', true);?>" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-print"></i>
                                    Print</a>
                                <?php }else{?>
                                    <a href="<?= $baseURL;?>helper/cetak/excel_beli_produk.php?excel=yes" class="btn btn-info btn-flat"><i class="fas fa-file-excel"></i>
                                    Excel</a>
                                    <a href="<?= $baseURL;?>helper/cetak/excel_beli_produk.php" class="btn btn-primary btn-flat" target="_blank"><i class="fas fa-print"></i>
                                    Print</a>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cari Laporan Per Hari</h3>							
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="get" action="produk.php">
                    <?php
                        if(!empty(getGet('hari', true))){
                            $tgla = getGet('tgla', true);
                            $tglb = getGet('tglb', true);
                        }else{
                            $tgla = "";
                            $tglb = "";
                        }
                    ?>
                    <input type="hidden" name="hari" value="yes">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Tanggal Awal</label>
                                <input type="date" value="<?= $tgla;?>" class="form-control w-100" name="tgla">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Tanggal Akhir</label>
                                <input type="date" value="<?= $tglb;?>" class="form-control w-100" name="tglb">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="periode" value="ya">
                            <div class="btn-group mt-4 pt-2" role="group" aria-label="First group">
                                <button class="btn btn-primary btn-flat">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="index.php?page=laporan" class="btn btn-success btn-flat">
                                    <i class="fas fa-sync"></i> Refresh
                                </a>
                                <?php if(!empty(getGet('hari', true))){?>
                                <a href="<?= $baseURL;?>helper/cetak/excel_beli_produk.php?excel=yes&hari=cek&tgla=<?= getGet('tgla', true);?>&tglb=<?= getGet('tglb', true);?>"
                                    target="_blank" class="btn btn-info btn-flat">
                                    <i class="fas fa-file-excel"></i>
                                    Excel</a>
                                <a href="<?= $baseURL;?>helper/cetak/excel_beli_produk.php?hari=cek&tgla=<?= getGet('tgla', true);?>&tglb=<?= getGet('tglb', true);?>"
                                    target="_blank" class="btn btn-primary btn-flat">
                                    <i class="fas fa-print"></i>
                                    Print</a>
                                <?php }else{?>
                                <a href="<?= $baseURL;?>helper/cetak/excel_beli_produk.php?excel=yes"
                                    class="btn btn-info btn-flat" target="_blank"><i class="fas fa-file-excel"></i>
                                    Excel</a>
                                <a href="<?= $baseURL;?>helper/cetak/excel_beli_produk.php" class="btn btn-primary btn-flat"
                                    target="_blank"><i class="fas fa-print"></i>
                                    Print</a>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <!-- /.card-body -->
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?php if(!empty(getGet('cari', true))){ ?>
                        Data Laporan Pembelian Barang <?= $bulan_tes[getPost('bln', true)];?> <?= getPost('thn', true);?>
                    <?php }elseif(!empty(getGet('hari', true))){?>
                        Data Laporan Pembelian Barang 
                    <?php }else{?>
                        Data Laporan Pembelian Barang <?= $bulan_tes[date('m')];?> <?= date('Y');?>
                    <?php }?>
                </h3>							
            </div>
            <?php 
                if(!empty(getGet('cari', true))){
                    $periode = getPost('thn', true).'-'.getPost('bln', true);
                    $sql = "SELECT SUM(jumlah) as qty, SUM(beli) as beli
                            FROM pembelian 
                            WHERE pembelian.periode = ? ORDER BY id DESC";
                    $row = $connectdb->prepare($sql);
                    $row->execute(array($periode));
                    $hasil = $row->fetch(PDO::FETCH_OBJ);
                }elseif(!empty(getGet('tgla', true))){
                    $tgla = getGet('tgla', true);
                    $tglb = getGet('tglb', true);
                    $sql = "SELECT SUM(jumlah) as qty, SUM(beli) as beli
                            FROM pembelian 
                            WHERE pembelian.tanggal_input BETWEEN '$tgla' AND '$tglb' ORDER BY id DESC";
                    $row = $connectdb->prepare($sql);
                    $row->execute();
                    $hasil = $row->fetch(PDO::FETCH_OBJ);
                }else{
                    $sql = "SELECT SUM(jumlah) as qty, SUM(beli) as beli
                            FROM pembelian 
                            WHERE pembelian.periode = ? ORDER BY id DESC";
                    $row = $connectdb->prepare($sql);
                    $row->execute(array(date('Y-m')));
                    $hasil = $row->fetch(PDO::FETCH_OBJ);
                }
                $qty = $hasil->qty;
                $beli = $hasil->beli;
            ?>
            <div class="card-body">
                <div class="table-responsive-1">
                    <table class="table table-hover" id="table-artikel-query">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Total Beli</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-3 ml-auto">
                        <div class="row">
                            <div class="col-sm-6">Total Qty</div>
                            <div class="col-sm-6"><b><?= $qty ?? 0;?></b></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">Total Pembelian</div>
                            <div class="col-sm-6"><b>Rp<?= number_format($beli ?? 0);?>,-</b></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var tabel = null;
    $(document).ready(function() {
        tabel = $('#table-artikel-query').DataTable({
            "processing": true,
            "responsive":true,
            "serverSide": true,
            "ordering": true, // Set true agar bisa di sorting
            "order": [[ 0, 'DESC' ]], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
            "ajax":{
                <?php if(!empty(getPost('thn', true))){?>
                    "url": "<?= $baseURL.'/helper/data.php?aksi=nota-beli-produk&thn='.getPost('thn', true).'&bln='.getPost('bln', true);?>", // URL file untuk proses select datanya
                <?php }elseif(!empty(getGet('hari', true))){?>
                    "url": "<?= $baseURL.'/helper/data.php?aksi=nota-beli-produk&hari=yes&tgla='.getGet('tgla', true).'&tglb='.getGet('tglb', true);?>", // URL file untuk proses select datanya
                <?php }else{?>
                    "url": "<?= $baseURL.'/helper/data.php?aksi=nota-beli-produk';?>", // URL file untuk proses select datanya
                <?php }?>
                "type": "POST"
            },
            "deferRender": true,
            "aLengthMenu": [[10,25,50],[ 10,25,50]], // Combobox Limit
            "columns": [
                {"data": 'id',"sortable": false, 
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }  
                },
                { "data": "idb" }, 
                { "data": "nama_barang" }, 
                { "data": "qty" },
                {
                    data: 'total',
                    render: $.fn.dataTable.render.number( ',', '.', 0 ,'Rp' )
                },
                { "data": "created_at" }, 
            ],
        });
    });
</script>