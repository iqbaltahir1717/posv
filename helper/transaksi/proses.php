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

if(!empty(getGet("aksi") == "edit_tipe")) {
    $harga = 0;
    $harga = (int)getPost('retail');
    $data[] = getPost('tipe', true);
    $data[] = $harga;
    $data[] = (int)$_POST['id'];
    $sqlk = "UPDATE keranjang SET jual = ? WHERE id = ? ";

    $rowk = $connectdb->prepare($sqlk);
    $rowk->execute($data);
    echo json_encode(['cek' => 'success', 'msg' => "update barang keranjang !"]);
}

if(!empty(getGet("aksi") == "edit_keranjang")) {
    $id =  (int)$_POST["id_barang"];
    $sql = "SELECT * FROM barang WHERE id = ?";
    $row = $connectdb->prepare($sql);
    $row->execute(array($id));
    $edit = $row->fetch(PDO::FETCH_OBJ);

    if($edit->stok >= (int)$_POST["qty"]) {
        $data[] = (int)$_POST["diskon"] ?? 0;
        $data[] = (int)$_POST["qty"] ?? 0;
        $data[] = (int)$_POST['id'];
        $sqlk = "UPDATE keranjang SET diskon = ?, jumlah = ? WHERE id = ? ";

        $rowk = $connectdb->prepare($sqlk);
        $rowk->execute($data);
        echo json_encode(['cek' => 'success', 'msg' => "update barang keranjang !"]);
    } else {
        echo json_encode(['cek' => 'success', 'msg' => "qty barang kurang dari stok barang !"]);
    }
}

if(!empty(getGet("aksi") == "tambah")) {
    $bayar = preg_replace("/[^0-9]/", "", $_POST['bayar']);
    $grtotal = preg_replace("/[^0-9]/", "", $_POST['grandtotal']);
    if ($grtotal > $bayar) {
        echo json_encode(['cek' => 'error', 'msg' => "Daftar belanja anda belum ada !"]);
        exit;
    }
    if(empty($bayar)) {
        $bayar = 0;
    }

    $notrx     = getPost('notrx', true);
    $sqlNota   = 'SELECT no_trx FROM penjualan WHERE no_trx = ?';
    $rowNota   = $connectdb->prepare($sqlNota);
    $rowNota->execute([$notrx]);
    $hasilNota = $rowNota->rowCount();
    if ($hasilNota > 0) {
        $notrx = $notrx.'-'.date('Hi').'-'.$_SESSION['codekop_session']['id'];
    }

    $sql = "SELECT barang.id_barang as idb, keranjang.* 
            FROM keranjang  
            LEFT JOIN barang 
            ON keranjang.id_barang=barang.id 
            WHERE id_member = ?
            ORDER BY keranjang.id ASC";
    $row = $connectdb->prepare($sql);
    $row->execute(array($users->id));
    $hasil = $row->fetchAll(PDO::FETCH_OBJ);
    $grantotal = 0;
    $beli = 0;
    $qty = 0;
    $stok = 0;
    foreach($hasil as $r) {

        $sqlb = "SELECT * FROM barang WHERE id = ?";
        $rowb = $connectdb->prepare($sqlb);
        $rowb->execute(array($r->id_barang));
        $editb = $rowb->fetch(PDO::FETCH_OBJ);

        $datapd = array();
        $datapd[] = $notrx; //1
        $datapd[] = $r->id_barang; //2
        $datapd[] = $editb->id_barang; //3
        $datapd[] = $r->nama_barang; //4
        $datapd[] = $r->beli; //5
        $datapd[] = $r->jual; //6
        $datapd[] = $r->jumlah; //7
        $datapd[] = $r->diskon; //8
        $datapd[] = ($r->jual * $r->jumlah) - $r->diskon; //9
        $datapd[] = date('Y-m-d'); //10
        $datapd[] = date('Y-m'); //11
        $datapd[] = $_SESSION['codekop_session']['id']; //12
        $datapd[] = date('Y-m-d H:i:s'); //13
        $sqlpd = "INSERT INTO penjualan_detail(no_trx, 
                    id_barang, 
                    idb, 
                    nama_barang, 
                    beli, 
                    jual, 
                    qty, 
                    diskon, 
                    total,
                    tgl_input, 
                    periode, 
                    id_member,
                    created_at) VALUES 
                    (?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $rowpd = $connectdb->prepare($sqlpd);
        $rowpd->execute($datapd);

        $stok = $editb->stok - $r->jumlah;

        $sqlbu = "UPDATE barang SET stok = ? WHERE id = ?";
        $rowbu = $connectdb->prepare($sqlbu);
        $rowbu->execute(array($stok, $r->id_barang));

        $grantotal += ($r->jual * $r->jumlah) - $r->diskon;
        $beli += $r->beli * $r->jumlah;
        $qty  += $r->jumlah;
    }
    $datap[] = $notrx;
    $datap[] = $_SESSION['codekop_session']['id'];
    $datap[] = $qty;
    $datap[] = $beli;
    $datap[] = $grtotal;
    $datap[] = $bayar;
    $datap[] = date('Y-m-d');
    $datap[] = date('Y-m-d H:i:s');
    $datap[] = date('Y-m');
    $datap[] = getPost('id_pelanggan', true);

    $sqlp = "INSERT INTO penjualan ( no_trx, 
                id_member, jumlah, 
                beli, total, bayar, tanggal_input, 
                created_at, periode, id_pelanggan) VALUES 
                (?,?,?,?,?,?,?,?,?,?)";
    $rowp = $connectdb->prepare($sqlp);
    $rowp->execute($datap);

    $sql_delete = "DELETE FROM keranjang WHERE id_member = ?";
    $row_delete = $connectdb->prepare($sql_delete);
    $row_delete->execute(array($_SESSION['codekop_session']['id']));
    // $kembali = $bayar - $grantotal;

    echo json_encode(['cek' => 'success', 'msg' => "Sukses tambah transaksi !", 'id' => $notrx]);
}
