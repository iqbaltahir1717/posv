<?php
    if (!empty(getGet("aksi") == "tambah")) {
        $data[] =  htmlspecialchars($_POST["kode_pelanggan"]);
        $data[] =  htmlspecialchars($_POST["nama_pelanggan"]);
        $data[] =  htmlspecialchars($_POST["alamat_pelanggan"]);
        $data[] =  htmlspecialchars($_POST["telepon_pelanggan"]);
        $data[] =  htmlspecialchars($_POST["email_pelanggan"]);
        $data[] =  date('Y-m-d H:i:s');

        $sql = "INSERT INTO pelanggan (kode_pelanggan,nama_pelanggan,alamat_pelanggan,telepon_pelanggan,email_pelanggan,created_at) VALUES (?,?,?,?,?,?)";
        $row = $connectdb->prepare($sql);
        $row->execute($data);
        
        set_flashdata("Berhasil", "tambah telah sukses !", "success");
        redirect("index.php");
    }

    if (!empty(getGet("aksi") == "edit")) {
        $id =  (int)$_POST["id"]; // should be integer (id)
        $data[] =  htmlspecialchars($_POST["kode_pelanggan"]);
        $data[] =  htmlspecialchars($_POST["nama_pelanggan"]);
        $data[] =  htmlspecialchars($_POST["alamat_pelanggan"]);
        $data[] =  htmlspecialchars($_POST["telepon_pelanggan"]);
        $data[] =  htmlspecialchars($_POST["email_pelanggan"]);
        $data[] =  date('Y-m-d H:i:s');
        $data[] = $id;
        $sql = "UPDATE pelanggan SET kode_pelanggan = ?, nama_pelanggan = ?, alamat_pelanggan = ?, telepon_pelanggan = ?, email_pelanggan = ?, created_at = ? WHERE id = ? ";

        $row = $connectdb->prepare($sql);
        $row->execute($data);

        set_flashdata("Berhasil", "edit telah sukses !", "success");
        redirect("edit.php?id=".$id);
    }

    if (!empty(getGet("aksi") == "delete")) {
        $id =  (int)$_GET["id"]; // should be integer (id)
        $sql = "SELECT * FROM pelanggan WHERE id = ?";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id));
        $cek = $row->rowCount();
        if ($cek > 0) {
            $sql_delete = "DELETE FROM pelanggan WHERE id = ?";
            $row_delete = $connectdb->prepare($sql_delete);
            $row_delete->execute(array($id));
            set_flashdata("Berhasil", "delete telah sukses !", "success");
            redirect("index.php");
        } else {
            set_flashdata("Gagal", "delete telah gagal !", "danger");
            redirect("index.php");
        }
    }