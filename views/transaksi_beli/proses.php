<?php
    if (!empty(getGet("aksi") == "keranjang")) {

        // cek fungsi di helper.php
        $id =  (int)$_POST["id"];
        $sql = "SELECT * FROM barang WHERE id = ?";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id));
        $edit = $row->fetch(PDO::FETCH_OBJ);
        
        $cekbarang =  "SELECT * FROM keranjang_beli WHERE id_barang = ? AND id_member = ?";
        $rowq = $connectdb->prepare($cekbarang);
        $rowq->execute(array($id, $_SESSION["codekop_session"]["id"]));
        $jml = $rowq->rowCount();
        if ($jml > 0) {
            $sqlw = "UPDATE keranjang_beli SET jumlah= jumlah + ? WHERE id_barang = ? AND id_member = ?";
            $roww = $connectdb->prepare($sqlw);
            $roww->execute(array((int)$_POST["qty"] ?? 0,$id, $_SESSION["codekop_session"]["id"]));
        } else {
            $data[] =  $id;
            $data[] =  $_SESSION['codekop_session']['id'];
            $data[] =  $edit->nama_barang;
            $data[] =  (int)$_POST["qty"] ?? 0;
            $data[] =  $edit->harga_beli;
            $data[] =  $edit->harga_jual;
            $data[] =  date('Y-m-d');

            $sqlk = "INSERT INTO keranjang_beli (id_barang,id_member,nama_barang,jumlah,beli,jual,tanggal_input ) VALUES (?,?,?,?,?,?,?)";
            $rowk = $connectdb->prepare($sqlk);
            $rowk->execute($data);
        }

        set_flashdata("Berhasil", "tambah barang ke keranjang !", "success");
        redirect("index.php");
    }

    if (!empty(getGet("aksi") == "tambah")) {
        $total = (int)$_POST['total'];
        if ($total == 0) {
            set_flashdata("Gagal", "Daftar belanja anda belum ada !", "danger");
            redirect("index.php");
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

        set_flashdata("Berhasil", " Transaksi Anda Telah Berhasil !", "success");
        redirect("sukses.php?notrx=".$notrx.'&kembali='.$beli);
    }

    if (!empty(getGet("aksi") == "carikeranjang")) {

        // cek fungsi di helper.php
        $id =  (int)$_GET["id"];
        $sql = "SELECT * FROM barang WHERE id = ?";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id));
        $edit = $row->fetch(PDO::FETCH_OBJ);
        
        $cekbarang =  "SELECT * FROM keranjang_beli WHERE id_barang = ? AND id_member = ?";
        $rowq = $connectdb->prepare($cekbarang);
        $rowq->execute(array($id, $_SESSION["codekop_session"]["id"]));
        $jml = $rowq->rowCount();
        if ($jml > 0) {
            $sqlw = "UPDATE keranjang_beli SET jumlah= jumlah + ? WHERE id_barang = ? AND id_member = ?";
            $roww = $connectdb->prepare($sqlw);
            $roww->execute(array((int)$_GET["qty"],$id, $_SESSION["codekop_session"]["id"]));
        } else {
            $data[] =  $id;
            $data[] =  $_SESSION['codekop_session']['id'];
            $data[] =  $edit->nama_barang;
            $data[] =  (int)$_GET["qty"];
            $data[] =  $edit->harga_beli;
            $data[] =  $edit->harga_jual;
            $data[] =  date('Y-m-d');

            $sqlk = "INSERT INTO keranjang_beli (id_barang,id_member,nama_barang,jumlah,beli,jual,tanggal_input ) VALUES ( ?,?,?,?,?,?,?)";
            $rowk = $connectdb->prepare($sqlk);
            $rowk->execute($data);
        }

        set_flashdata("Berhasil", "tambah barang ke keranjang beli !", "success");
        redirect("index.php");
    }

    if (!empty(getGet("aksi") == "editkeranjang")) {
        $id =  (int)$_POST["id_barang"];
        $sql = "SELECT * FROM barang WHERE id = ?";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id));
        $edit = $row->fetch(PDO::FETCH_OBJ);
        $data[] = (int)$_POST["beli"] ?? 0;
        $data[] = (int)$_POST["jual"] ?? 0;
        $data[] = (int)$_POST["qty"] ?? 0;
        $data[] = (int)$_POST['id'];
        $sqlk = "UPDATE keranjang_beli SET beli = ?, jual = ?, jumlah = ? WHERE id = ? ";
        $rowk = $connectdb->prepare($sqlk);
        $rowk->execute($data);

        set_flashdata("Berhasil", "update barang keranjang beli !", "success");
        redirect("index.php");
    }

    if (!empty(getGet("aksi") == "delete")) {
        $id = (int)$_GET['id'];
        $sql_delete = "DELETE FROM keranjang_beli WHERE id = ?";
        $row_delete = $connectdb->prepare($sql_delete);
        $row_delete->execute(array($id));
        set_flashdata("Berhasil", "delete barang dari list keranjang beli !", "success");
        redirect("index.php");
    }

    if (!empty(getGet("aksi") == "reset")) {
        $sql_delete = "DELETE FROM keranjang_beli WHERE id_member = ?";
        $row_delete = $connectdb->prepare($sql_delete);
        $row_delete->execute(array($_SESSION['codekop_session']['id']));
        set_flashdata("Berhasil", "reset keranjang beli telah sukses !", "success");
        redirect("index.php");
    }