
<?php
    if(!empty(getGet("aksi") == "tambah")) {
        $data[] =  htmlspecialchars($_POST["nama_operasional"]);
        $data[] =  htmlspecialchars($_POST["status_operasional"]);
        $data[] =  htmlspecialchars($_POST["harga_operasional"]);
        $data[] =  htmlspecialchars($_POST["ket_operasional"]);
        $data[] =  htmlspecialchars($_POST["tgl_input"]);
        $data[] =  date('Y-m-d H:i:s');
        $data[] =  $_SESSION['codekop_session']['id'];

        $sql = "INSERT INTO operasional (nama_operasional,status_operasional,harga_operasional,ket_operasional,tgl_input,created_at,id_users ) VALUES (?,?,?,?,?,?,?)";
        $row = $connectdb->prepare($sql);
        $row->execute($data);
        set_flashdata("Berhasil","tambah telah sukses !","success");
        redirect("index.php");

    }

    if(!empty(getGet("aksi") == "update")) {
        $id =  (int)$_POST["id"]; // should be integer (id)
        $data[] =  htmlspecialchars($_POST["nama_operasional"]);
        $data[] =  htmlspecialchars($_POST["status_operasional"]);
        $data[] =  htmlspecialchars($_POST["harga_operasional"]);
        $data[] =  htmlspecialchars($_POST["ket_operasional"]);
        $data[] =  htmlspecialchars($_POST["tgl_input"]);

        $data[] = $id;
         $sql = "UPDATE operasional SET nama_operasional = ?, status_operasional = ?, harga_operasional = ?, ket_operasional = ?, tgl_input = ? WHERE id = ? ";

        $row = $connectdb->prepare($sql);
        $row->execute($data);

        set_flashdata("Berhasil","edit telah sukses !","success");
        redirect("edit.php?id=".$id);

    }

    if(!empty(getGet("aksi") == "delete")) {
        
        $id =  (int)$_GET["id"]; // should be integer (id)
        $sql = "SELECT * FROM operasional WHERE id = ?";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id));
        $cek = $row->rowCount();
        if($cek > 0)
        {
            $sql_delete = "DELETE FROM operasional WHERE id = ?";
            $row_delete = $connectdb->prepare($sql_delete);
            $row_delete->execute(array($id));
            set_flashdata("Berhasil","delete telah sukses !","success");
            redirect("index.php");
        }else{
            set_flashdata("Gagal","delete telah gagal !","danger");
            redirect("index.php");
        }
    
    }
    