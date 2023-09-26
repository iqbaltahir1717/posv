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
<div class="row">
    <div class="col-md-12">
        <?php if(!empty(flashdata())){ echo flashdata(); }?>
        <a href="tambah.php" class="btn btn-primary" role="button">Add Operasional</a>
        <br><br>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cari Laporan Per Bulan</h3>							
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="post" action="index.php?page=operasional&cari=ok">
                    <div class="row">
                        <div class="col-sm-3">
                            <select name="bln" class="form-control mb-2">
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
                        <div class="col-sm-3">
                            <?php
                                $now=date('Y');
                                echo "<select name='thn' class='form-control mb-2'>";
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
                        <div class="col-sm-6">
                            <input type="hidden" name="periode" value="ya">
                            <div class="btn-group mr-2 mb-2 btn-block" role="group" aria-label="First group">
                                <button class="btn btn-primary btn-flat">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="index.php?page=operasional" class="btn btn-success btn-flat">
                                    <i class="fas fa-sync"></i> Refresh</a>
                                <?php if(!empty(getGet('cari', true))){?>
                                    <a href="<?= $baseURL;?>helper/cetak/cash.php?cari=yes&bln=<?=getPost('bln', true);?>&thn=<?=getPost('thn', true);?>" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-print"></i>
                                    Print Cashflow</a>
                                <?php }else{?>
                                    <a href="<?= $baseURL;?>helper/cetak/cash.php" class="btn btn-primary btn-flat" target="_blank"><i class="fas fa-print"></i>
                                    Print Cashflow</a>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                Daftar Operasional
                <?php if(!empty(getGet('cari', true))){ ?>
                    <?= $bulan_tes[getPost('bln', true)];?> <?= getPost('thn', true);?>
                <?php }else{?>
                    <?= $bulan_tes[date('m')];?> <?= date('Y');?>
                <?php }?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="example1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Operasional</th>
                                <th>Status</th>
                                <th>Harga</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $no =1;
                                if(!empty(getPost('bln', true))){
                                    $periode = getPost('thn', true).'-'.getPost('bln', true);
                                    $sql = "SELECT * FROM operasional WHERE tgl_input LIKE '%$periode%' ORDER BY id DESC";
                                }else{
                                    $periode = date('Y-m');
                                    $sql = "SELECT * FROM operasional WHERE tgl_input LIKE '%$periode%' ORDER BY id DESC";
                                }
                                $row = $connectdb->prepare($sql);
                                $row->execute();
                                $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                                $total_masuk = 0;
                                $total_keluar = 0;
                                foreach($hasil as $r){
                            ?>
                            <tr>
                                <td><?= $no;?></td>
                                <td><?=$r->nama_operasional;?></td>      
                                <td><?=$r->status_operasional;?></td>         
                                <td>Rp<?=number_format($r->harga_operasional);?></td>      
                                <td><?=$r->tgl_input;?></td>       
                                <td>
                                    <a href="<?= "edit.php?id=".$r->id;?>" 
                                        class="btn btn-success btn-sm" title="Edit">
                                        <i class="fa fa-edit"></i> 
                                    </a>  
                                    <a href="<?= "proses.php?aksi=delete&id=".$r->id;?>" 
                                        class="btn btn-danger btn-sm" 
                                        onclick="javascript:return confirm(`Data ingin dihapus ?`);" title="Delete">
                                        <i class="fa fa-times"></i> 
                                    </a>
                                </td>
                            </tr>
                            <?php $no++; if($r->status_operasional == 'Pemasukan') { $total_masuk += $r->harga_operasional; }else{ $total_keluar += $r->harga_operasional; }}?>
                        </tbody>
                    </table>
                </div>
            </div>
             <?php 
                if(!empty(getGet('cari', true))){
                    $periode = getPost('thn', true).'-'.getPost('bln', true);
                    $sql = "SELECT SUM(jumlah) as qty, SUM(beli) as beli, SUM(total) as jual 
                            FROM penjualan 
                            WHERE penjualan.periode = ? ORDER BY id DESC";
                    $row = $connectdb->prepare($sql);
                    $row->execute(array($periode));
                    $hasil = $row->fetch(PDO::FETCH_OBJ);
                }else{
                    $sql = "SELECT SUM(jumlah) as qty, SUM(beli) as beli, SUM(total) as jual 
                            FROM penjualan 
                            WHERE penjualan.periode = ? ORDER BY id DESC";
                    $row = $connectdb->prepare($sql);
                    $row->execute(array(date('Y-m')));
                    $hasil = $row->fetch(PDO::FETCH_OBJ);
                }
                $qty = $hasil->qty;
                $jual = $hasil->jual;
            ?>
            <?php 
                if(!empty(getGet('cari', true))){
                    $periode = getPost('thn', true).'-'.getPost('bln', true);
                    $sql = "SELECT SUM(jumlah) as qty, SUM(beli) as beli
                            FROM pembelian 
                            WHERE pembelian.periode = ? ORDER BY id DESC";
                    $row = $connectdb->prepare($sql);
                    $row->execute(array($periode));
                    $hasil = $row->fetch(PDO::FETCH_OBJ);
                }else{
                    $sql = "SELECT SUM(jumlah) as qty, SUM(beli) as beli
                            FROM pembelian 
                            WHERE pembelian.periode = ? ORDER BY id DESC";
                    $row = $connectdb->prepare($sql);
                    $row->execute(array(date('Y-m')));
                    $hasil = $row->fetch(PDO::FETCH_OBJ);
                }
                $qty_beli = $hasil->qty;
                $beli = $hasil->beli;
            ?>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-6 ml-auto">
                        <div class="row">
                            <div class="col-sm-6">Total Pemasukan</div>
                            <div class="col-sm-6"><b>Rp<?= number_format($total_masuk?? 0);?>,-</b></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">Total Pengeluaran</div>
                            <div class="col-sm-6"><b>Rp<?= number_format($total_keluar?? 0);?>,-</b></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">Total Penjualan</div>
                            <div class="col-sm-6"><b>Rp<?= number_format($jual?? 0);?>,-</b></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">Total Pembelian</div>
                            <div class="col-sm-6"><b>Rp<?= number_format($beli?? 0);?>,-</b></div>
                        </div>
                        <?php
                            $total = (($total_masuk + $jual) - ($beli + $total_keluar));
                        ?>
                        <div class="row">
                            <div class="col-sm-6">Laba Bersih</div>
                            <div class="col-sm-6"><b>Rp<?= number_format($total?? 0);?>,-</b></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">Total Qty Terjual</div>
                            <div class="col-sm-6"><b><?= $qty ?? 0;?></b></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">Total Qty Pembelian</div>
                            <div class="col-sm-6"><b><?= $qty_beli ?? 0;?></b></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>