<!-- Page content -->
<div class="row">
    <div class="col-sm-12">
        <?php 
            $sql=" select * from barang where stok <= 5";
            $row = $connectdb -> prepare($sql);
            $row -> execute();
            $r = $row -> rowCount();
            if($r > 0){
                echo "
                <div class='alert alert-warning'>
                    <span class='glyphicon glyphicon-info-sign'></span> Ada <span style='color:red'>$r</span> barang yang Stok tersisa sudah kurang dari 5 items. silahkan pesan lagi !!
                    <span class='float-right'><a href='".$baseURL."barang/index.php?stok=yes' class='text-dark'>Cek Barang <i class='fa fa-angle-right'></i></a></span>
                </div>
                ";	
            }
        ?>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-primary elevation-1">
                <i class="fas fa-shopping-cart"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold"><?= $toko->nama_toko;?></span>
                <span class="info-box-text"><?= $toko->tlp;?></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-purple elevation-1"><i class="far fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">
                    <?php 
                        $tanggal= mktime(date("m"),date("d"),date("Y"));
                        $a = date ("H");
                        if (($a>=6) && ($a<=11)){
                        echo "Selamat Pagi";
                        }
                        else if(($a>11) && ($a<=14))
                        {
                        echo "Selamat Siang";}
                        else if (($a>14) && ($a<=18)){
                        echo "Selamat Sore";}
                        else { echo "Selamat Malam";}
                    ?>
                </span>
                <span class="info-box-number">
                    <span id="jam"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="clearfix hidden-md-up"></div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-success elevation-1">
                <i class="fas fa-calendar-alt"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Tanggal</span>
                <span class="info-box-number">
                    <?php 
                    function tgl_indo($tanggal){
                      $bulan = array (
                        1 =>   'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember'
                      );
                      $pecahkan = explode('-', $tanggal);
                      
                      // variabel pecahkan 0 = tanggal
                      // variabel pecahkan 1 = bulan
                      // variabel pecahkan 2 = tahun
                    
                      return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
                    }
                    
                    echo tgl_indo(date('Y-m-d'));
                ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-warning elevation-1"><i class="far fa-user-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pengguna</span>
                <span class="info-box-number"><?= $users->name;?></span>
            </div>
        </div>
    </div>
</div>
<br>
<?php if (!empty($_SESSION['codekop_session']['akses'] == 1)) {?>
<?php if(!empty($_POST['thn'])){ $thn = $_POST['thn'];  }else{ $thn = date('Y'); }?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary border-0">
                <h5 class="card-title">Grafik Penjualan & Pembelian Tahun <?= $thn;?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <form method="post" action="<?= $baseURL.'index.php'?>">
                            <div class="table-responsive">
                                <table>
                                    <tr>
                                        <td>
                                            <select name="thn" class="form-control">
                                                <option value="">- Pilih Tahun Grafik -</option>
                                                <?php
                                                    $thn_skr = date('Y');
                                                    for ($x = $thn_skr; $x >= 2021; $x--){
                                                ?>
                                                <option value="<?= $x;?>" <?php if($thn == $x){?> selected <?php }?>>
                                                    <?= $x;?></option>
                                                <?php }?>
                                            </select>
                                        </td>
                                        <td><button type="submit" class="btn btn-primary btn-md">
                                                <i class="fa fa-search"></i></button></td>
                                        <td>
                                            <a href="<?= $baseURL.'index.php'?>" class="btn btn-success btn-md">
                                                <i class="fa fa-sync"></i></a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="clearfix"></div>
                <canvas id="line-chart" height="180" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary border-0">
                <h5 class="card-title">Grafik Kategori Barang</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <form method="post" action="<?= $baseURL.'index.php'?>">
                            <div class="table-responsive">
                                <table>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="kategori" value="yes">
                                            <select name="bln" class="form-control mb-2">
                                                <option selected="selected">Bulan</option>
                                                <?php
                                                    $bulan=array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                                                    $jlh_bln=count($bulan);
                                                    $bln1 = array('01','02','03','04','05','06','07','08','09','10','11','12');
                                                    $no=1;
                                                    for($c=0; $c<$jlh_bln; $c+=1){
                                                        if(!empty($_POST['kategori'])){
                                                            if($_POST['bln'] == $bln1[$c]){
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
                                        </td>
                                        <td>
                                            <select name="thn" class="form-control mb-2">
                                                <option value="">- Pilih Tahun Grafik -</option>
                                                <?php
                                                    $thn_skr = date('Y');
                                                    for ($x = $thn_skr; $x >= 2021; $x--){
                                                ?>
                                                <option value="<?= $x;?>" <?php if($thn == $x){?> selected <?php }?>>
                                                    <?= $x;?></option>
                                                <?php }?>
                                            </select>
                                        </td>
                                        <td><button type="submit" class="btn btn-primary btn-md mb-2">
                                                <i class="fa fa-search"></i></button></td>
                                        <td>
                                            <a href="<?= $baseURL.'index.php'?>" class="btn btn-success btn-md mb-2">
                                                <i class="fa fa-sync"></i></a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="clearfix"></div>
                <canvas id="line-chart-2" height="180" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
var linechart = document.getElementById('line-chart');
var chart = new Chart(linechart, {
    type: 'bar',
    data: {
        labels: [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'
        ], // Merubah data tanggal menjadi format JSON
        datasets: [{
                label: "Stok Terjual",
                data: [
                    <?php 
                            // php mencari produk
                            for($n=1; $n<=12; $n++){
                                if($n > 9) {
                                    $period = $thn.'-'.$n;
                                }else{
                                    $period = $thn.'-'.'0'.$n;
                                }
                                $sql = "SELECT SUM(jumlah) as jml FROM penjualan 
                                        WHERE penjualan.periode = ? ORDER BY id DESC";
                                $row = $connectdb->prepare($sql);
                                $row->execute(array($period));
                                $gr = $row->fetch(PDO::FETCH_OBJ);
                        ?>
                    <?= $gr->jml;?>,
                    <?php } ?>
                ],
                borderColor: '#3c73a8',
                backgroundColor: '#3c73a8',
                borderWidth: 4,
            },
            {
                label: "Stok Pembelian",
                data: [
                    <?php 
                            // php mencari produk
                            for($n=1; $n<=12; $n++){
                                if($n > 9) {
                                    $period = $thn.'-'.$n;
                                }else{
                                    $period = $thn.'-'.'0'.$n;
                                }
                                $sql = "SELECT SUM(jumlah) as jml FROM pembelian 
                                        WHERE pembelian.periode = ? ORDER BY id DESC";
                                $row = $connectdb->prepare($sql);
                                $row->execute(array($period));
                                $gr = $row->fetch(PDO::FETCH_OBJ);
                        ?>
                    <?= $gr->jml;?>,
                    <?php } ?>
                ],
                borderColor: '#32a852',
                backgroundColor: '#32a852',
                borderWidth: 4,
            },
        ],
    },
    options: {
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    },
});
</script>
<script>
var linechart2 = document.getElementById('line-chart-2');
var chart = new Chart(linechart2, {
    type: 'bar',
    data: {
        labels: [
            <?php 
                $sqls = "SELECT * FROM barang_kategori";
                $rows = $connectdb->prepare($sqls);
                $rows->execute();
                $kategori = $rows->fetchAll(PDO::FETCH_OBJ);
                foreach($kategori as $r){
                    echo '"'.$r->nama_kategori.'",';
                }
            ?>
        ], // Merubah data tanggal menjadi format JSON
        datasets: [{
                label: "Stok Terjual",
                data: [
                    <?php 

                if(!empty($_POST['kategori'])){
                    $periode_detail = $thn.'-'.$_POST['bln'];
                }else{
                    $periode_detail = date('Y-m');
                }
                foreach($kategori as $r){
                    $sql1= "SELECT SUM(penjualan_detail.qty) as jml, barang.id_kategori FROM penjualan_detail 
                            LEFT JOIN barang ON penjualan_detail.id_barang= barang.id  
                            WHERE penjualan_detail.periode = ? AND  barang.id_kategori = ?";
                    $row1 = $connectdb->prepare($sql1);
                    $row1->execute(array($periode_detail, $r->id));
                    $pdj = $row1->fetch(PDO::FETCH_OBJ);
                ?>
                    <?= $pdj->jml;?>,
                    <?php } ?>
                ],
                borderColor: '#3c73a8',
                backgroundColor: '#3c73a8',
                borderWidth: 4,
            },
            {
                label: "Stok Pembelian",
                data: [
                    <?php 

                if(!empty($_POST['kategori'])){
                    $periode_detail = $thn.'-'.$_POST['bln'];
                }else{
                    $periode_detail = date('Y-m');
                }
                foreach($kategori as $r){
                    $sql1= "SELECT SUM(pembelian_detail.qty) as jml, barang.id_kategori FROM pembelian_detail 
                            LEFT JOIN barang ON pembelian_detail.id_barang= barang.id  
                            WHERE pembelian_detail.periode = ? AND  barang.id_kategori = ?";
                    $row1 = $connectdb->prepare($sql1);
                    $row1->execute(array($periode_detail, $r->id));
                    $pdj = $row1->fetch(PDO::FETCH_OBJ);
                ?>
                    <?= $pdj->jml;?>,
                    <?php } ?>
                ],
                borderColor: '#32a852',
                backgroundColor: '#32a852',
                borderWidth: 4,
            },
        ],
    },
    options: {
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    },
});
</script>
<?php }?>