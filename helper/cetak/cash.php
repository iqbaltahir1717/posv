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
		exit;
	}

	// PENJUALAN
	if(!empty(getGet('cari', true))) {
		$periode = getGet('thn', true).'-'.getGet('bln', true);
		$sql = "SELECT SUM(jumlah) as qty, SUM(beli) as beli, SUM(total) as jual 
					FROM penjualan 
					WHERE penjualan.periode = ? ORDER BY id DESC";
		$row = $connectdb->prepare($sql);
		$row->execute(array($periode));
		$hasil = $row->fetch(PDO::FETCH_OBJ);
	} else {
		$sql = "SELECT SUM(jumlah) as qty, SUM(beli) as beli, SUM(total) as jual 
				FROM penjualan 
				WHERE penjualan.periode = ? ORDER BY id DESC";
		$row = $connectdb->prepare($sql);
		$row->execute(array(date('Y-m')));
		$hasil = $row->fetch(PDO::FETCH_OBJ);
	}
	$qty = $hasil->qty;
	$jual = $hasil->jual;

	// PEMBELIAN
	if(!empty(getGet('cari', true))) {
		$periode = getGet('thn', true).'-'.getGet('bln', true);
		$sql = "SELECT SUM(jumlah) as qty, SUM(beli) as beli
				FROM pembelian 
				WHERE pembelian.periode = ? ORDER BY id DESC";
		$row = $connectdb->prepare($sql);
		$row->execute(array($periode));
		$hasil = $row->fetch(PDO::FETCH_OBJ);
	} else {
		$sql = "SELECT SUM(jumlah) as qty, SUM(beli) as beli
				FROM pembelian 
				WHERE pembelian.periode = ? ORDER BY id DESC";
		$row = $connectdb->prepare($sql);
		$row->execute(array(date('Y-m')));
		$hasil = $row->fetch(PDO::FETCH_OBJ);
	}
	$qty_beli = $hasil->qty;
	$beli = $hasil->beli;

	$no =1;
	if(!empty(getGet('cari', true))) {
		$periode = getGet('thn', true).'-'.getGet('bln', true);
		$sql = "SELECT * FROM operasional WHERE tgl_input LIKE '%$periode%' ORDER BY id DESC";
	} else {
		$periode = date('Y-m');
		$sql = "SELECT * FROM operasional WHERE tgl_input LIKE '%$periode%' ORDER BY id DESC";
	}
	$row = $connectdb->prepare($sql);
	$row->execute();
	$hasil = $row->fetchAll(PDO::FETCH_OBJ);
	$total_masuk = 0;
	$total_keluar = 0;
	foreach($hasil as $r) {
		if($r->status_operasional == 'Pemasukan') {
			$total_masuk += $r->harga_operasional;
		} else {
			$total_keluar += $r->harga_operasional;
		}
	}

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
<!DOCTYPE html>
<html>

<head>
    <style>
    #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #customers td,
    #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #customers tr:hover {
        background-color: #ddd;
    }

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
    }
    </style>
</head>

<body onload="window.print()">
    <h3>
        <center>
			Cashflow 
			<?php if(!empty(getGet('cari', true))) { ?>
            <?= $bulan_tes[getGet('bln', true)];?> <?= getGet('thn', true);?>
            <?php } else {?>
            <?= $bulan_tes[date('m')];?> <?= date('Y');?>
            <?php }?>
		</center>
    </h3>
    <table id="customers">
        <tr>
            <th colspan="2">OPERASIONAL</th>
        </tr>
        <tr>
            <td>Total Pemasukan</td>
            <td>Rp<?= number_format($total_masuk ?? 0);?>,-</td>
        </tr>
        <tr>
            <td>Total Pengeluaran</td>
            <td>(Rp<?= number_format($total_keluar ?? 0);?>,-)</td>
        </tr>
        <tr>
            <th colspan="2">PENJUALAN</th>
        </tr>
        <tr>
            <td>Total Penjualan</td>
            <td>Rp<?= number_format($jual ?? 0);?>,-</td>
        </tr>
        <tr>
            <th colspan="2">PEMBELIAN</th>
        </tr>
        <tr>
            <td>Total Pembelian</td>
            <td>(Rp<?= number_format($beli ?? 0);?>,-)</td>
        </tr>
        <?php $total = (($total_masuk + $jual) - ($beli + $total_keluar)); ?>
        <tr>
            <th>LABA BERSIH</th>
            <th>Rp<?= number_format($total ?? 0);?>,-</th>
        </tr>
        <tr>
            <th colspan="2">STOK</th>
        </tr>
        <tr>
        <tr>
            <td>Total Qty Penjualan</td>
            <td><?= number_format($qty ?? 0);?></td>
        </tr>
        <tr>
            <td>Total Qty Pembelian</td>
            <td><?= number_format($qty_beli ?? 0);?></td>
        </tr>
    </table>

</body>

</html>