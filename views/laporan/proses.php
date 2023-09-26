<?php
    if(!empty(getGet("aksi") == "del")) {
        
        $id =  getGet("no", true); // should be integer (id)
        $sql = "SELECT * FROM penjualan WHERE no_trx = ?";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id));
        $cek = $row->rowCount();
        if($cek > 0)
        {
            $select = "SELECT * FROM penjualan_detail WHERE no_trx = ?";
            $rows = $connectdb->prepare($select);
            $rows->execute(array($id));
            $hasil = $rows->fetchAll();
            foreach( $hasil as $r ){
                $sqlUpdate = "UPDATE barang SET stok = stok + ? WHERE id = ?";
                $rowUpdate = $connectdb->prepare($sqlUpdate);
                $rowUpdate->execute(array($r['qty'],$r['id_barang']));
            }

            $sql_delete = "DELETE FROM penjualan WHERE no_trx = ?";
            $row_delete = $connectdb->prepare($sql_delete);
            $row_delete->execute(array($id));

            $sql_delete1= "DELETE FROM penjualan_detail WHERE no_trx = ?";
            $row_delete1 = $connectdb->prepare($sql_delete1);
            $row_delete1->execute(array($id));

            set_flashdata("Berhasil","delete data telah sukses !","success");
            redirect("index.php");
        }else{
            set_flashdata("Gagal","delete data telah gagal !","danger");
            redirect("index.php");
        }
    }

    if (!empty(getGet("aksi") == "editlap")) {
        if(getPost('dibayar') >= getPost('total')) {
            $sqlp = "UPDATE penjualan SET bayar = ? WHERE no_trx = ?";
            $rowp = $connectdb->prepare($sqlp);
            $rowp->execute(array(getPost('dibayar'), getPost('notrx')));
            set_flashdata("Berhasil","Pembayaran telah sukses !","success");
            redirect("edit.php?id=".getPost('notrx'));
        } else {
            set_flashdata("Gagal","Pembayaran Kurang !","danger");
            redirect("edit.php?id=".getPost('notrx'));
        }
    }

    if (!empty(getGet("aksi") == "addbarang")) {
        $id_barang = strip_tags(getPost('id'));
        $notrx = strip_tags(getPost('no_trx'));
        $qty = strip_tags(getPost('qty'));

        $sqlb = "SELECT * FROM barang WHERE id = ?";
        $rowb = $connectdb->prepare($sqlb);
        $rowb->execute(array($id_barang));
        $editb = $rowb->fetch(PDO::FETCH_OBJ);

        
        $sql = "SELECT * FROM penjualan_detail 
                WHERE id_barang = ? AND no_trx = ?";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id_barang, $notrx));
        $hsl = $row->fetch(PDO::FETCH_OBJ);

        $datapd[] = $notrx; //1
        $datapd[] = $id_barang; //2
        $datapd[] = $editb->id_barang; //3
        $datapd[] = $editb->nama_barang; //4
        $datapd[] = $editb->harga_beli; //5
        $datapd[] = $editb->harga_jual; //6
        if( $hsl ) {
            $datapd[] = $qty + $hsl->qty; //7
            $datapd[] = $hsl->diskon; //8
            $datapd[] = ($editb->harga_jual * ($qty + $hsl->qty)) - $hsl->diskon; //9
        } else {
            $datapd[] = $qty; //7
            $datapd[] = 0; //8
            $datapd[] = $editb->harga_jual * $qty; //9
        }
        $datapd[] = date('Y-m-d'); //10
        $datapd[] = date('Y-m'); //11 
        $datapd[] = $_SESSION['codekop_session']['id']; //12
        $datapd[] = date('Y-m-d H:i:s'); //13
        if ($hsl) {
            $datapd[] = $hsl->id; //14
            $sqlpd = "UPDATE penjualan_detail SET no_trx = ?, 
                            id_barang =?, 
                            idb =?, 
                            nama_barang =?, 
                            beli =?, 
                            jual =?, 
                            qty =?, 
                            diskon =?, 
                            total =?,
                            tgl_input =?, 
                            periode =?, 
                            id_member =?,
                            created_at =? WHERE id = ?";
        } else {
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
        }
        $rowpd = $connectdb->prepare($sqlpd);
        $rowpd->execute($datapd);

        if ($hsl) {
            $stok = ($editb->stok + $hsl->qty) - ($hsl->qty + $qty);
        } else {
            $stok = $editb->stok - $qty;
        }

        $sqlbu = "UPDATE barang SET stok = ? WHERE id = ?";
        $rowbu = $connectdb->prepare($sqlbu);
        $rowbu->execute(array($stok, $id_barang));

        $sqlrj = "SELECT * FROM penjualan_detail WHERE no_trx = ?";
        $rowrj = $connectdb->prepare($sqlrj);
        $rowrj->execute(array($notrx));
        $hslrj = $rowrj->fetchAll(PDO::FETCH_OBJ);
        $jqty = 0;
        $jbeli = 0;
        $jtotal = 0;
        $jbayar = 0;
        foreach($hslrj as $r) {
            $jtotal += $r->total;
            $jbeli += $r->beli * $r->qty;
            $jqty  += $r->qty;
        };
        $sqlp = "UPDATE penjualan SET jumlah = ?, beli = ?, total = ?, bayar = ? WHERE no_trx = ?";
        $rowp = $connectdb->prepare($sqlp);
        $rowp->execute(array($jqty , $jbeli, $jtotal, $jtotal, $notrx));

        set_flashdata("Berhasil","tambah barang telah sukses !","success");
        redirect("edit.php?id=".$notrx);
    }
    
    if (!empty(getGet("aksi") == "editstok")) {
        $id = strip_tags(getPost('id'));
        $diskon = strip_tags(getPost('diskon'));
        $sql = "SELECT * FROM penjualan_detail 
                WHERE id = ?
                ORDER BY id ASC";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id));
        $hsl = $row->fetch(PDO::FETCH_OBJ);
        $jml = $hsl->qty;
        $qty = getPost('qty');
        $jml_final = $qty - $jml;
        
        $sqlbu = "UPDATE barang SET stok = stok - ? WHERE id = ?";
        $rowbu = $connectdb->prepare($sqlbu);
        $rowbu->execute(array($jml_final, $hsl->id_barang));

        $ttl_jual = 0;
        $ttl_jual = ($hsl->jual * $qty) - $diskon;
        $sqlpj = "UPDATE penjualan_detail SET qty = ?, total = ?, diskon = ? WHERE id = ?";
        $rowpj = $connectdb->prepare($sqlpj);
        $rowpj->execute(array($qty, $ttl_jual, $diskon, $id));

        $sqlrj = "SELECT * FROM penjualan_detail WHERE no_trx = ?";
        $rowrj = $connectdb->prepare($sqlrj);
        $rowrj->execute(array($hsl->no_trx));
        $hslrj = $rowrj->fetchAll(PDO::FETCH_OBJ);
        $jqty = 0;
        $jbeli = 0;
        $jtotal = 0;
        $jbayar = 0;
        foreach($hslrj as $r) {
            $jtotal += $r->total;
            $jbeli += $r->beli * $r->qty;
            $jqty  += $r->qty;
        }

        $sqlp = "UPDATE penjualan SET jumlah = ?, beli = ?, total = ?, bayar = ? WHERE no_trx = ?";
        $rowp = $connectdb->prepare($sqlp);
        $rowp->execute(array($jqty , $jbeli, $jtotal, $jtotal, $hsl->no_trx));

        set_flashdata("Berhasil","ubah data telah sukses !","success");
        redirect("edit.php?id=".$hsl->no_trx);
    }

    if (!empty(getGet("aksi") == "deletestok")) {
        $id = strip_tags(getGet('id'));
        $sql = "SELECT * FROM penjualan_detail 
                WHERE id = ?
                ORDER BY id ASC";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id));
        $hsl = $row->fetch(PDO::FETCH_OBJ);
        $jml = $hsl->qty;
        
        $sqlbu = "UPDATE barang SET stok = stok + ? WHERE id = ?";
        $rowbu = $connectdb->prepare($sqlbu);
        $rowbu->execute(array($jml, $hsl->id_barang));

        $sqlpj = "DELETE FROM penjualan_detail WHERE id = ?";
        $rowpj = $connectdb->prepare($sqlpj);
        $rowpj->execute(array($id));

        $sqlrj = "SELECT * FROM penjualan_detail WHERE no_trx = ?";
        $rowrj = $connectdb->prepare($sqlrj);
        $rowrj->execute(array($hsl->no_trx));
        $hslrj = $rowrj->fetchAll(PDO::FETCH_OBJ);
        $jqty = 0;
        $jbeli = 0;
        $jtotal = 0;
        $jbayar = 0;
        foreach($hslrj as $r) {
            $jtotal += $r->total;
            $jbeli += $r->beli * $r->qty;
            $jqty  += $r->qty;
        }

        $sqlp = "UPDATE penjualan SET jumlah = ?, beli = ?, total = ?, bayar = ? WHERE no_trx = ?";
        $rowp = $connectdb->prepare($sqlp);
        $rowp->execute(array($jqty , $jbeli, $jtotal, $jtotal, $hsl->no_trx));

        set_flashdata("Berhasil","delete barang telah sukses !","success");
        redirect("edit.php?id=".$hsl->no_trx);
    }
   