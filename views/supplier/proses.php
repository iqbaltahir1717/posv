<?php
    if (!empty(getGet("aksi") == "tambah")) {
        $data[] =  getPost("nama_supplier", true);
        $data[] =  getPost("alamat_supplier", true);
        $data[] =  getPost("telepon_supplier", true);
        $data[] =  getPost("email_supplier", true);
        $data[] =  date('Y-m-d H:i:s');

        $sql = "INSERT INTO supplier (nama_supplier,alamat_supplier,telepon_supplier,email_supplier,created_at ) VALUES ( ?,?,?,?,?)";
        $row = $connectdb->prepare($sql);
        $row->execute($data);
        
        set_flashdata("Berhasil", "tambah telah sukses !", "success");
        redirect("index.php");
    }

    if (!empty(getGet("aksi") == "update")) {
        $id =  (int)$_POST["id"];
        $data[] =  getPost("nama_supplier", true);
        $data[] =  getPost("alamat_supplier", true);
        $data[] =  getPost("telepon_supplier", true);
        $data[] =  getPost("email_supplier", true);
        $data[] =  date('Y-m-d H:i:s');

        $data[] = $id;
        $sql = "UPDATE supplier SET nama_supplier = ?, alamat_supplier = ?, telepon_supplier = ?, email_supplier = ?, created_at = ?  WHERE id = ? ";

        $row = $connectdb->prepare($sql);
        $row->execute($data);

        set_flashdata("Berhasil", "edit telah sukses !", "success");
        redirect("edit.php?id=".$id);
    }

    if (!empty(getGet("aksi") == "delete")) {
        $id =  (int)$_GET["id"]; // should be integer (id)
        $sql = "SELECT * FROM supplier WHERE id = ?";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id));
        $cek = $row->rowCount();
        if ($cek > 0) {
            $sql_delete = "DELETE FROM supplier WHERE id = ?";
            $row_delete = $connectdb->prepare($sql_delete);
            $row_delete->execute(array($id));
            set_flashdata("Berhasil", "delete telah sukses !", "success");
            redirect("index.php");
        } else {
            set_flashdata("Gagal", "delete telah gagal !", "danger");
            redirect("index.php");
        }
    }