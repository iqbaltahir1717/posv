<?php
    if(!empty(getGet("aksi") == "keranjang")) {

        // cek fungsi di helper.php
        $id =  (int)$_POST["id"];
        $sql = "SELECT * FROM barang WHERE id = ?";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id));
        $edit = $row->fetch(PDO::FETCH_OBJ);
        if($edit->stok >= (int)$_POST["qty"])
        {
            $cekbarang =  "SELECT * FROM keranjang WHERE id_barang = ? AND id_member = ?";
            $rowq = $connectdb->prepare($cekbarang);
            $rowq->execute(array($id, $_SESSION["codekop_session"]["id"]));
            $jml = $rowq->rowCount();
            if ($jml > 0) {
                $sqlw = "UPDATE keranjang SET jumlah= jumlah + ? WHERE id_barang = ? AND id_member = ?";
                $roww = $connectdb->prepare($sqlw);
                $roww->execute(array($_POST["qty"] ?? 0, $id, $_SESSION["codekop_session"]["id"]));
            }else{
                $data[] =  $id;
                $data[] =  $_SESSION['codekop_session']['id'];
                $data[] =  $edit->nama_barang;
                $data[] =  0;
                $data[] =  (int)$_POST["qty"] ?? 0;
                $data[] =  $edit->harga_beli;
                $data[] =  $edit->harga_jual;
                $data[] =  date('Y-m-d');
        
                $sqlk = "INSERT INTO keranjang (id_barang,id_member,nama_barang,diskon,jumlah,beli,jual,tanggal_input ) VALUES ( ?,?,?,?,?,?,?,?)";
                $rowk = $connectdb->prepare($sqlk);
                $rowk->execute($data);
    
            }
            set_flashdata("Berhasil","tambah barang ke keranjang !","success");
            redirect("index.php");
        }else{

            set_flashdata("Gagal","qty barang kurang dari stok barang !","danger");
            redirect("index.php");
        }
    }

    if(!empty(getGet("aksi") == "tambah")) {
        $bayar = preg_replace("/[^0-9]/", "",$_POST['bayar']);
        $total = (int)$_POST['total'];
        if($total == 0){
            set_flashdata("Gagal","Daftar belanja anda belum ada !","danger");
            redirect("index.php");
            exit;
        }
        
        $notrx = $_POST['notrx'];
        $sqlNota   = 'SELECT no_trx FROM penjualan WHERE no_trx = ?';
        $rowNota   = $connectdb->prepare($sqlNota);
        $rowNota->execute([$notrx]);
        $hasilNota = $rowNota->rowCount();
        if ($hasilNota > 0) {
            $notrx = $notrx.'-'.date('Hi').'-'.$_SESSION['codekop_session']['id'];
        }
        if($bayar >= $total){
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
            foreach($hasil as $r){

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
            $datap[] = $grantotal;
            $datap[] = $bayar;
            $datap[] = date('Y-m-d');
            $datap[] = date('Y-m-d H:i:s');
            $datap[] = date('Y-m');
            $datap[] = getPost('id_pelanggan', TRUE);

            $sqlp = "INSERT INTO penjualan ( no_trx, 
                        id_member, jumlah, 
                        beli, total, bayar, tanggal_input, 
                        created_at, periode, id_pelanggan) VALUES 
                        (?,?,?,?,?,?,?,?,?, ?)";
            $rowp = $connectdb->prepare($sqlp);
            $rowp->execute($datap);

            $sql_delete = "DELETE FROM keranjang WHERE id_member = ?";
            $row_delete = $connectdb->prepare($sql_delete);
            $row_delete->execute(array($_SESSION['codekop_session']['id']));
            $kembali = $bayar - $grantotal;
            set_flashdata("Berhasil"," Transaksi Anda Telah Berhasil !","success");
            redirect("sukses.php?notrx=".$notrx.'&kembali='.$kembali);
        }
        else{
            set_flashdata("Gagal","Jumlah Pembayaran Anda Kurang !","danger");
            redirect("index.php");
        }
    }

    if(!empty(getGet("aksi") == "carikeranjang")) {

        // cek fungsi di helper.php
        $id =  (int)$_GET["id"];
        $sql = "SELECT * FROM barang WHERE id = ?";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id));
        $edit = $row->fetch(PDO::FETCH_OBJ);
        if($edit->stok >= (int)$_GET["qty"])
        {
            $cekbarang =  "SELECT * FROM keranjang WHERE id_barang = ? AND id_member = ?";
            $rowq = $connectdb->prepare($cekbarang);
            $rowq->execute(array($id, $_SESSION["codekop_session"]["id"]));
            $jml = $rowq->rowCount();
            if ($jml > 0) {
                $sqlw = "UPDATE keranjang SET jumlah= jumlah + ? WHERE id_barang = ? AND id_member = ?";
                $roww = $connectdb->prepare($sqlw);
                $roww->execute(array((int)$_GET["qty"],$id, $_SESSION["codekop_session"]["id"]));
            }else{
                $data[] =  $id;
                $data[] =  $_SESSION['codekop_session']['id'];
                $data[] =  $edit->nama_barang;
                $data[] =  0;
                $data[] =  (int)$_GET["qty"];
                $data[] =  $edit->harga_beli;
                $data[] =  $edit->harga_jual;
                $data[] =  date('Y-m-d');
                
                $sqlk = "INSERT INTO keranjang (id_barang,id_member,nama_barang,diskon,jumlah,beli,jual,tanggal_input ) VALUES ( ?,?,?,?,?,?,?,?)";
                $rowk = $connectdb->prepare($sqlk);
                $rowk->execute($data);
            }

            set_flashdata("Berhasil", "tambah barang ke keranjang !", "success");
            redirect("index.php");
        }else{

            set_flashdata("Gagal","qty barang kurang dari stok barang !","danger");
            redirect("index.php");
        }
    }

    if(!empty(getGet("aksi") == "editkeranjang")) {
        $id =  (int)$_POST["id_barang"];
        $sql = "SELECT * FROM barang WHERE id = ?";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id));
        $edit = $row->fetch(PDO::FETCH_OBJ);

        if($edit->stok >= (int)$_POST["qty"])
        {

            $data[] = (int)$_POST["diskon"] ?? 0;
            $data[] = (int)$_POST["qty"] ?? 0;
            $data[] = (int)$_POST['id'];
            $sqlk = "UPDATE keranjang SET diskon = ?, jumlah = ? WHERE id = ? ";

            $rowk = $connectdb->prepare($sqlk);
            $rowk->execute($data);

            set_flashdata("Berhasil","update barang keranjang !","success");
            redirect("index.php");
        }else{

            set_flashdata("Gagal","qty barang kurang dari stok barang !","danger");
            redirect("index.php");
        }
    }

    if(!empty(getGet("aksi") == "delete")) {
        $id = (int)$_GET['id'];
        $sql_delete = "DELETE FROM keranjang WHERE id = ?";
        $row_delete = $connectdb->prepare($sql_delete);
        $row_delete->execute(array($id));
        set_flashdata("Berhasil","delete barang dari list keranjang !","success");
        redirect("index.php");
    }

    if(!empty(getGet("aksi") == "reset")) {
        
        $sql_delete = "DELETE FROM keranjang WHERE id_member = ?";
        $row_delete = $connectdb->prepare($sql_delete);
        $row_delete->execute(array($_SESSION['codekop_session']['id']));
        set_flashdata("Berhasil","reset keranjang telah sukses !","success");
        redirect("index.php");
    }

    if(!empty(getGet("aksi") == "pelanggan")) {
        $data[] =  htmlspecialchars($_POST["kode_pelanggan"]);
        $data[] =  htmlspecialchars($_POST["nama_pelanggan"]);
        $data[] =  htmlspecialchars($_POST["alamat_pelanggan"]);
        $data[] =  htmlspecialchars($_POST["telepon_pelanggan"]);
        $data[] =  htmlspecialchars($_POST["email_pelanggan"]);
        $data[] =  date('Y-m-d H:i:s');

        $sql = "INSERT INTO pelanggan (kode_pelanggan,nama_pelanggan,alamat_pelanggan,telepon_pelanggan,email_pelanggan,created_at) VALUES (?,?,?,?,?,?)";
        $row = $connectdb->prepare($sql);
        $row->execute($data);
        
        set_flashdata("Berhasil","tambah pelanggan telah sukses !","success");
        redirect("index.php");

    }