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

if(!empty(getGet("aksi") == "edit_keranjang")) {
    // cek fungsi di helper.php
    $id =  (int)$_POST["id_barang"];
    $sql = "SELECT * FROM barang WHERE id = ?";
    $row = $connectdb->prepare($sql);
    $row->execute(array($id));
    $edit = $row->fetch(PDO::FETCH_OBJ);
    $data[] = (int)$_POST["beli"] ?? 0;
    // $data[] = (int)$_POST["jual"] ?? 0;
    $data[] = (int)$_POST["qty"] ?? 0;
    $data[] = (int)$_POST['id'];
    $sqlk = "UPDATE keranjang_beli SET beli = ?, jumlah = ? WHERE id = ? ";
    $rowk = $connectdb->prepare($sqlk);
    $rowk->execute($data);

    echo json_encode(['cek' => 'success', 'msg' => "update barang keranjang !"]);
}

if(!empty(getGet("aksi") == "edit_jual")) {
    // cek fungsi di helper.php
    $setQuery = '';
    if(getPost('tipe') == 'retail') {
        $setQuery = 'jual = ?';
    }
    $data[] = (int)$_POST["jual"] ?? 0;
    $data[] = (int)$_POST['id'];
    $sqlk = "UPDATE keranjang_beli SET ".$setQuery." WHERE id = ? ";
    $rowk = $connectdb->prepare($sqlk);
    $rowk->execute($data);

    echo json_encode(['cek' => 'success', 'msg' => "update barang keranjang !"]);
}

if (!empty(getGet("aksi") == "tambah")) {
    $total = (int)$_POST['total'];
    if ($total == 0) {
        echo json_encode(['cek' => 'error', 'msg' => "Daftar belanja anda belum ada !"]);
        exit;
    }
    $notrx = $_POST['notrx'];
    $sqlNota   = 'SELECT no_trx FROM pembelian WHERE no_trx = ?';
    $rowNota   = $connectdb->prepare($sqlNota);
    $rowNota->execute([$notrx]);
    $hasilNota = $rowNota->rowCount();
    if ($hasilNota > 0) {
        $notrx = $notrx.'-'.date('Hi').'-'.$_SESSION['codekop_session']['id'];
    }

    $sql = "SELECT barang.id_barang as idb, keranjang_beli.* 
            FROM keranjang_beli  
            LEFT JOIN barang 
            ON keranjang_beli.id_barang=barang.id 
            WHERE id_member = ?
            ORDER BY keranjang_beli.id ASC";
    $row = $connectdb->prepare($sql);
    $row->execute(array($users->id));
    $hasil = $row->fetchAll(PDO::FETCH_OBJ);
    $grantotal = 0;
    $beli = 0;
    $qty = 0;
    $stok = 0;
    foreach ($hasil as $r) {
        $sqlb = "SELECT * FROM barang WHERE id = ?";
        $rowb = $connectdb->prepare($sqlb);
        $rowb->execute(array($r->id_barang));
        $editb = $rowb->fetch(PDO::FETCH_OBJ);

        $stok = $editb->stok + $r->jumlah;

        $datapd = array();
        $datapd[] = $notrx;
        $datapd[] = $r->id_barang;
        $datapd[] = $editb->id_barang;
        $datapd[] = $r->nama_barang;
        $datapd[] = $r->beli;
        $datapd[] = $r->jumlah;
        $datapd[] = $r->beli * $r->jumlah;
        $datapd[] = date('Y-m-d');
        $datapd[] = date('Y-m');
        $datapd[] = $_SESSION['codekop_session']['id'];
        $datapd[] = date('Y-m-d H:i:s');
        $sqlpd = "INSERT INTO pembelian_detail(no_trx, id_barang, idb, nama_barang, 
                    beli, qty, total,
                    tgl_input, periode, id_member, created_at) VALUES 
                    (?,?,?,?,?,?,?,?,?,?,?)";

        $rowpd = $connectdb->prepare($sqlpd);
        $rowpd->execute($datapd);

        $sqlbu = "UPDATE barang SET stok = ?, harga_beli = ?, harga_jual = ? WHERE id = ?";
        $rowbu = $connectdb->prepare($sqlbu);
        $rowbu->execute(array($stok, $r->beli, $r->jual, $r->id_barang));

        $beli += $r->beli * $r->jumlah;
        $qty  += $r->jumlah;
    }

    $datap[] = $_POST["supplier"];
    $datap[] = $notrx;
    $datap[] = $_SESSION['codekop_session']['id'];
    $datap[] = $qty;
    $datap[] = $beli;
    $datap[] = date('Y-m-d');
    $datap[] = date('Y-m-d H:i:s');
    $datap[] = date('Y-m');

    $sqlp = "INSERT INTO pembelian ( nm_supplier, no_trx, 
                id_member, jumlah, 
                beli, tanggal_input, 
                created_at, periode) VALUES 
                (?,?,?,?,?,?,?,?)";
    $rowp = $connectdb->prepare($sqlp);
    $rowp->execute($datap);

    $sql_delete = "DELETE FROM keranjang_beli WHERE id_member = ?";
    $row_delete = $connectdb->prepare($sql_delete);
    $row_delete->execute(array($_SESSION['codekop_session']['id']));

    echo json_encode(['cek' => 'success', 'msg' => "Transaksi Anda Telah Berhasil  !", 'id' => $notrx]);
}
