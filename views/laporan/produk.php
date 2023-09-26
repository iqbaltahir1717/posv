<?php if(!empty($_SESSION['codekop_session']['akses'] != 1)){ redirect($baseURL); }?>
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
                        <div class="col-sm-3">
                            <select name="kategori" class="form-control mb-2">
                                <option value="All">Semua Kategori</option>
                                <?php 
                                    $sqls = "SELECT * FROM barang_kategori";
                                    $rows = $connectdb->prepare($sqls);
                                    $rows->execute();
                                    $kategori = $rows->fetchAll(PDO::FETCH_OBJ);
                                    foreach($kategori as $r){
                                        if(!empty(getPost('kategori'))){
                                ?>
                                <option value="<?= $r->id;?>" <?= getPost('kategori', true) == $r->id ? 'selected' : ''?>>
                                    <?= $r->nama_kategori;?></option>
                                <?php }else{?>
                                <option value="<?= $r->id;?>"><?= $r->nama_kategori;?></option>
                                <?php }}?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="bln" class="form-control mb-2">
                                <option selected="selected">Bulan</option>
                                <?php
                                    $bulan   = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                                    $jlh_bln = count($bulan);
                                    $bln1    = array('01','02','03','04','05','06','07','08','09','10','11','12');
                                    $no      = 1;
                                    for($c=0; $c<$jlh_bln; $c+=1){

                                        if(!empty(getGet('cari'))){
                                            if(getPost('bln') == $bln1[$c]){
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
                        <div class="col-sm-3">
                            <?php
                                $now=date('Y');
                                echo "<select name='thn' class='form-control mb-2'>";
                                echo '
                                <option>Tahun</option>';
                                for ($a=2021;$a<=$now;$a++)
                                {
                                    if(!empty(getGet('cari'))){
                                        if(getPost('thn') == $a){
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
                        <div class="col-sm-3">
                            <input type="hidden" name="periode" value="ya">
                            <div class="btn-group mr-2 mb-2 btn-block" role="group" aria-label="First group">
                                <button class="btn btn-primary btn-flat">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="produk.php?page=laporan" class="btn btn-success btn-flat">
                                    <i class="fas fa-sync"></i> Refresh</a>

                                <?php if(!empty(getGet('cari', true))){?>
                                <a href="<?= $baseURL;?>helper/cetak/excel_jual.php?excel=yes&kategori=<?= getPost('kategori', true);?>&cari=yes&bln=<?=getPost('bln', true);?>&thn=<?=getPost('thn', true);?>"
                                    class="btn btn-info" target="_blank"><i class="fas fa-file-excel"></i>
                                    Excel</a>
                                <a href="<?= $baseURL;?>helper/cetak/excel_jual.php?cari=yes&kategori=<?= getPost('kategori', true);?>&bln=<?=getPost('bln', true);?>&thn=<?=getPost('thn', true);?>"
                                    target="_blank" class="btn btn-primary">
                                    <i class="fas fa-print"></i>
                                    Print</a>
                                <?php }else{?>
                                <a href="<?= $baseURL;?>helper/cetak/excel_jual.php?excel=yes"
                                    class="btn btn-info btn-flat" target="_blank"><i class="fas fa-file-excel"></i>
                                    Excel</a>
                                <a href="<?= $baseURL;?>helper/cetak/excel_jual.php" class="btn btn-primary btn-flat"
                                    target="_blank"><i class="fas fa-print"></i>
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
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="">Kategori</label>
                                <select name="kategori" class="form-control">
                                    <option value="All">Semua Kategori</option>
                                    <?php 
                                    $sqls = "SELECT * FROM barang_kategori";
                                    $rows = $connectdb->prepare($sqls);
                                    $rows->execute();
                                    $kategori = $rows->fetchAll(PDO::FETCH_OBJ);
                                    foreach($kategori as $r){
                                        $selected = "";
                                        if(!empty(getGet('kategori', true) == $r->id)){ $selected = "selected"; }
                                ?>
                                    <option value="<?= $r->id;?>" <?= $selected;?>><?= $r->nama_kategori;?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="">Tanggal Awal</label>
                                <input type="date" value="<?= $tgla;?>" class="form-control w-100" name="tgla">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="">Tanggal Akhir</label>
                                <input type="date" value="<?= $tglb;?>" class="form-control w-100" name="tglb">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="">Aksi</label>
                                <input type="hidden" name="periode" value="ya">
                                <div class="btn-group btn-block" role="group" aria-label="First group">
                                    <button class="btn btn-primary btn-flat">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="produk.php?page=laporan" class="btn btn-success btn-flat">
                                        <i class="fas fa-sync"></i> Refresh</a>

                                    <?php if(!empty(getGet('hari', true))){?>
                                    <a href="<?= $baseURL;?>helper/cetak/excel_jual.php?excel=yes&hari=cek&kategori=<?= getGet('kategori', true);?>&tgla=<?= getGet('tgla', true);?>&tglb=<?= getGet('tglb', true);?>"
                                        class="btn btn-info btn-flat" target="_blank"><i class="fas fa-file-excel"></i>
                                        Excel</a>
                                    <a href="<?= $baseURL;?>helper/cetak/excel_jual.php?hari=cek&hari=cek&kategori=<?= getGet('kategori', true);?>&tgla=<?= getGet('tgla', true);?>&tglb=<?= getGet('tglb', true);?>"
                                        class="btn btn-primary btn-flat" target="_blank">
                                        <i class="fas fa-print"></i>
                                        Print</a>
                                    <?php }else{?>
                                    <a href="<?= $baseURL;?>helper/cetak/excel_jual.php?excel=yes"
                                        class="btn btn-info btn-flat" target="_blank"><i class="fas fa-file-excel"></i>
                                        Excel</a>
                                    <a href="<?= $baseURL;?>helper/cetak/excel_jual.php"
                                        class="btn btn-primary btn-flat" target="_blank"><i class="fas fa-print"></i>
                                        Print</a>
                                    <?php }?>
                                </div>
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
                    Data Laporan Penjualan Barang <?= $bulan_tes[getPost('bln', true)];?> <?= getPost('thn', true);?>
                    <?php }elseif(!empty(getGet('hari', true))){?>
                    Data Laporan Penjualan Barang
                    <?php }else{?>
                    Data Laporan Penjualan Barang <?= $bulan_tes[date('m')];?> <?= date('Y');?>
                    <?php }?>
                </h3>
            </div>
            <div class="card-body">
                <?php 
                    if(!empty(getPost('kategori', true))){
                        if(getPost('kategori', true) != 'All'){
                            $sqkat = " AND barang.id_kategori = '".getPost('kategori', true)."'";
                        }else{
                            $sqkat = "";
                        }
                    }else{
                        if (!empty(getGet('kategori', true))) {
                            if (getGet('kategori', true) != 'All') {
                                $sqkat = " AND barang.id_kategori = '".getGet('kategori', true)."'";
                            } else {
                                $sqkat = "";
                            }
                        }else{
                            $sqkat = "";
                        }
                    }
                    if(!empty(getGet('cari', true))){
                        $periode = getPost('thn', true).'-'.getPost('bln', true);
                        $sql = "SELECT SUM(qty) as qty, SUM(beli * qty) as beli, SUM(total) as jual, barang.id_kategori 
                                FROM penjualan_detail 
                                LEFT JOIN barang ON penjualan_detail.id_barang=barang.id 
                                WHERE penjualan_detail.periode = ? $sqkat";
                        $row = $connectdb->prepare($sql);
                        $row->execute(array($periode));
                        $hasil = $row->fetch(PDO::FETCH_OBJ);
                    }elseif(!empty(getGet('tgla', true))){
                        $tgla = getGet('tgla', true);
                        $tglb = getGet('tglb', true);
                        $sql = "SELECT SUM(qty) as qty, SUM(beli * qty) as beli, SUM(total) as jual, barang.id_kategori 
                                FROM penjualan_detail 
                                LEFT JOIN barang ON penjualan_detail.id_barang=barang.id 
                                WHERE penjualan_detail.tgl_input BETWEEN '$tgla' and '$tglb' $sqkat";
                        $row = $connectdb->prepare($sql);
                        $row->execute();
                        $hasil = $row->fetch(PDO::FETCH_OBJ);
                    }else{
                        $sql = "SELECT SUM(qty) as qty, SUM(beli * qty) as beli, SUM(total) as jual, barang.id_kategori 
                                FROM penjualan_detail 
                                LEFT JOIN barang ON penjualan_detail.id_barang=barang.id 
                                WHERE penjualan_detail.periode = ? $sqkat";
                        $row = $connectdb->prepare($sql);
                        $row->execute(array(date('Y-m')));
                        $hasil = $row->fetch(PDO::FETCH_OBJ);
                    }
                    $qty = $hasil->qty;
                    $beli = $hasil->beli;
                    $jual = $hasil->jual;
                ?>
                <div class="table-responsive-1">
                    <table class="table table-hover" id="table-artikel-query">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Qty</th>
                                <th>Beli</th>
                                <th>Jual</th>
                                <th>Laba</th>
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
                            <div class="col-sm-6">Total Terjual</div>
                            <div class="col-sm-6"><b><?= $qty ?? 0;?></b></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">Total Modal</div>
                            <div class="col-sm-6"><b>Rp<?= number_format($beli ?? 0);?>,-</b></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">Total Jual</div>
                            <div class="col-sm-6"><b>Rp<?= number_format($jual ?? 0);?>,-</b></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">Keuntungan</div>
                            <div class="col-sm-6"><b>Rp<?= number_format(($jual-$beli ?? 0));?>,-</b></div>
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
        "responsive": true,
        "serverSide": true,
        "ordering": true, // Set true agar bisa di sorting
        "order": [
            [0, 'DESC']
        ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
        "ajax": {
            <?php if(!empty(getPost('thn', true))){?> "url": "<?= $baseURL.'/helper/data.php?aksi=nota-jual-produk&kategori='.getPost('kategori', true).'&thn='.getPost('thn', true).'&bln='.getPost('bln', true);?>", // URL file untuk proses select datanya
            <?php }elseif(!empty(getGet('tgla', true))){?> "url": "<?= $baseURL.'/helper/data.php?aksi=nota-jual-produk&hari=yes&kategori='.getGet('kategori', true).'&tgla='.getGet('tgla', true).'&tglb='.getGet('tglb', true);?>", // URL file untuk proses select datanya
            <?php }else{?> "url": "<?= $baseURL.'/helper/data.php?aksi=nota-jual-produk';?>", // URL file untuk proses select datanya
            <?php }?> "type": "POST"
        },
        "deferRender": true,
        "aLengthMenu": [
            [10, 25, 50],
            [10, 25, 50]
        ], // Combobox Limit
        "columns": [{
                "data": 'id',
                "sortable": false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "data": "idb"
            },
            {
                "data": "nama_barang"
            },
            {
                "data": "nama_kategori"
            },
            {
                "data": "qty"
            },

            {
                "data": "beli",
                "render": function(data, type, row, meta) {
                    var beli = row.beli * row.qty;
                    return $.fn.dataTable.render.number(',', '.', 0, 'Rp').display(beli);
                }
            },
            {
                "data": "jual",
                "render": function(data, type, row, meta) {
                    var jual = row.jual * row.qty;
                    return $.fn.dataTable.render.number(',', '.', 0, 'Rp').display(jual);
                }
            },
            {
                "data": null,
                "render": function(data, type, row, meta) {
                    var jl = row.jual * row.qty;
                    var be = row.beli * row.qty;
                    var bl = jl - be;
                    return $.fn.dataTable.render.number(',', '.', 0, 'Rp').display(bl);
                }
            },
            {
                "data": "created_at"
            },
        ],
    });
});
</script>