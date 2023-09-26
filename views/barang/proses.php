<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if (!empty(getGet("aksi") == "import")) {
    $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    if (isset($_FILES['berkas_excel']['name']) && in_array($_FILES['berkas_excel']['type'], $file_mimes)) {
        $arr_file = explode('.', $_FILES['berkas_excel']['name']);
        $extension = end($arr_file);
         
        if ('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
         
        $spreadsheet = $reader->load($_FILES['berkas_excel']['tmp_name']);
             
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        for ($i = 1;$i < count($sheetData);$i++) {
            if ($sheetData[$i]['2'] == null) {
                $kategori = 1;
            } else {
                $kategori = $sheetData[$i]['2'];
            }
            // cek fungsi di helper.php
            $cekid  =  cek_id($connectdb, 'barang', 'id_barang', $sheetData[$i]['1']);
                
            // $data[] =  $cekid;
            // $data[] =  $kategori;
            // $data[] =  $sheetData[$i]['3'];
            // $data[] =  $sheetData[$i]['4'];
            // $data[] =  $sheetData[$i]['5'];
            // $data[] =  $sheetData[$i]['6'];
            // $data[] =  $sheetData[$i]['7'];
            // $data[] =  $sheetData[$i]['8'];
            // $data[] =  date('Y-m-d');
            // $data[] =  null;

            // $sql = "INSERT INTO barang (id_barang,id_kategori,nama_barang,merk,harga_beli,harga_jual,satuan_barang,stok,tgl_input,tgl_update ) VALUES ( ?,?,?,?,?,?,?,?,?,?)";
            // $row = $connectdb->prepare($sql);
            // $row->execute($data);

            $sql = "INSERT INTO barang (id_barang,id_kategori,nama_barang,merk,harga_beli,harga_jual,satuan_barang,stok,tgl_input,tgl_update ) 
                    VALUES (
                        :id_barang,
                        :id_kategori,
                        :nama_barang,
                        :merk,
                        :harga_beli,
                        :harga_jual,
                        :satuan_barang,
                        :stok,
                        :tgl_input,
                        :tgl_update )";

            $row = $connectdb->prepare($sql);
            $row->bindValue(':id_barang', $cekid);
            $row->bindValue(':id_kategori', $kategori);
            $row->bindValue(':nama_barang', $sheetData[$i]['3']);
            $row->bindValue(':merk', $sheetData[$i]['4']);
            $row->bindValue(':harga_beli', $sheetData[$i]['5']);
            $row->bindValue(':harga_jual', $sheetData[$i]['6']);
            $row->bindValue(':satuan_barang', $sheetData[$i]['7']);
            $row->bindValue(':stok', $sheetData[$i]['8']);
            $row->bindValue(':tgl_input', date('Y-m-d'));
            $row->bindValue(':tgl_update', null);
            $row->execute();
        }
    }
        
    set_flashdata("Berhasil", "import telah sukses !", "success");
    redirect("index.php");
}

if (!empty(getGet("aksi") == "tambah")) {
    $harga_beli = preg_replace("/[^0-9]/", "", getPost("harga_beli", true));
    $harga_jual = preg_replace("/[^0-9]/", "", getPost("harga_jual", true));
    // cek fungsi di helper.php
    $cekid  =  cek_id($connectdb, 'barang', 'id_barang', getPost("id_barang", true));
    $data[] =  $cekid;
    $data[] =  getPost("id_kategori", true);
    $data[] =  getPost("nama_barang", true);
    $data[] =  getPost("merk", true);
    $data[] =  $harga_beli;
    $data[] =  $harga_jual;
    $data[] =  getPost("satuan_barang", true);
    $data[] =  getPost("stok", true);
    $data[] =  getPost("tgl_input", true);
    $data[] =  null;

    if ($_FILES['gambar']["size"] > 0) {
        $allowedImageType = array("image/gif", "image/JPG", "image/jpeg", "image/pjpeg", "image/png", "image/x-png", 'image/webp');
        $filepath = $_FILES['gambar']['tmp_name'];
        $fileSize = filesize($filepath);
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $filetype = finfo_file($fileinfo, $filepath);
        $allowedTypes = [
            'image/png'   => 'png',
            'image/jpeg'  => 'jpg',
            'image/gif'   => 'gif',
            'image/jpg'   => 'jpeg',
            'image/webp'  => 'webp'
        ];
        if(!in_array($filetype, array_keys($allowedTypes))) {
            set_flashdata("Gagal", "You can only upload JPG, PNG and GIF file !!", "danger");
            redirect("index.php");
            exit;
        }else if ($_FILES['gambar']["error"] > 0) {
            set_flashdata("Gagal", "You can only upload JPG, PNG and GIF file", "danger");
            redirect("index.php");
            exit;
        } elseif (!in_array($_FILES['gambar']["type"], $allowedImageType)) {
            set_flashdata("Gagal", "You can only upload JPG, PNG and GIF file", "danger");
            redirect("index.php");
            exit;
        } elseif (round($_FILES['gambar']["size"] / 1024) > 4096) {
            set_flashdata("Gagal", "Besar Gambar Tidak Boleh Lebih Dari 4 MB", "danger");
            redirect("index.php");
            exit;
        } else {
            $target_path = './assets/uploads/barang/';
            $temp = explode(".", $_FILES["gambar"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $target_path = $target_path . basename($newfilename);
                
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_path)) {
                $data[] = $newfilename;
                $sql = "INSERT INTO barang (id_barang,id_kategori,nama_barang,merk,harga_beli,harga_jual,satuan_barang,stok,tgl_input,tgl_update, gambar ) VALUES ( ?, ?,?,?,?,?,?,?,?,?,?)";
            } else {
                set_flashdata("Gagal", "edit telah gagal upload !", "danger");
                redirect("index.php");
                exit;
            }
        }
    } else {
        $sql = "INSERT INTO barang (id_barang,id_kategori,nama_barang,merk,harga_beli,harga_jual,satuan_barang,stok,tgl_input,tgl_update ) VALUES ( ?,?,?,?,?,?,?,?,?,?)";
    }

    $row = $connectdb->prepare($sql);
    $row->execute($data);
    set_flashdata("Berhasil", "tambah telah sukses !", "success");
    redirect("index.php");
}

if (!empty(getGet("aksi") == "stok")) {
    $id =  (int)$_POST["id"];
    $sql = "SELECT * FROM barang WHERE id = ?";
    $row = $connectdb->prepare($sql);
    $row->execute(array($id));
    $edit = $row->fetch(PDO::FETCH_OBJ);
    $stok = 0;
    $stok = (int)$edit->stok + (int)getPost("qty");
    $data[] = $stok;
    $data[] = date('Y-m-d');
    $data[] = $id;

    $sqlb = "UPDATE barang SET stok = ?, tgl_update = ?  WHERE id = ? ";
    $rowb = $connectdb->prepare($sqlb);
    $rowb->execute($data);

    set_flashdata("Berhasil", "edit stok barang sukses !", "success");
    redirect("index.php?stok=yes");
}

if (!empty(getGet("aksi") == "update")) {
    $id =  (int)$_POST["id"];

    // cek jika sama
    if (getPost("id_barang", true) == getPost("idb", true)) {
        $cekid  = getPost("id_barang", true);
    } else {
        $cekid  = cek_id($connectdb, 'barang', 'id_barang', getPost("id_barang", true));
    }
        
    $harga_beli = preg_replace("/[^0-9]/", "", getPost("harga_beli", true));
    $harga_jual = preg_replace("/[^0-9]/", "", getPost("harga_jual", true));

    $data[] =  $cekid;
    $data[] =  getPost("id_kategori", true);
    $data[] =  getPost("nama_barang", true);
    $data[] =  getPost("merk", true);
    $data[] =  $harga_beli;
    $data[] =  $harga_jual;
    $data[] =  getPost("satuan_barang", true);
    $data[] =  getPost("stok", true);
    $data[] =  getPost("tgl_input", true);
    $data[] =  date('Y-m-d');

    if ($_FILES['gambar']["size"] > 0) {
        $allowedImageType = array("image/gif", "image/JPG", "image/jpeg", "image/pjpeg", "image/png", "image/x-png", 'image/webp');
        $filepath = $_FILES['gambar']['tmp_name'];
        $fileSize = filesize($filepath);
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $filetype = finfo_file($fileinfo, $filepath);
        $allowedTypes = [
            'image/png'   => 'png',
            'image/jpeg'  => 'jpg',
            'image/gif'   => 'gif',
            'image/jpg'   => 'jpeg',
            'image/webp'  => 'webp'
        ];
        if(!in_array($filetype, array_keys($allowedTypes))) {
            set_flashdata("Gagal", "You can only upload JPG, PNG and GIF file !!", "danger");
            redirect("index.php");
            exit;
        }else if ($_FILES['gambar']["error"] > 0) {
            set_flashdata("Gagal", "You can only upload JPG, PNG and GIF file", "danger");
            redirect("index.php");
            exit;
        } elseif (!in_array($_FILES['gambar']["type"], $allowedImageType)) {
            set_flashdata("Gagal", "You can only upload JPG, PNG and GIF file", "danger");
            redirect("index.php");
            exit;
        } elseif (round($_FILES['gambar']["size"] / 1024) > 4096) {
            set_flashdata("Gagal", "Besar Gambar Tidak Boleh Lebih Dari 4 MB", "danger");
            redirect("index.php");
            exit;
        } else {
            $target_path = './assets/uploads/barang/';
            $temp = explode(".", $_FILES["gambar"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $target_path = $target_path . basename($newfilename);
                
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_path)) {
                //post foto lama
                $foto = getPost("foto", true);
                //remove foto di direktori
                if (file_exists('./assets/uploads/barang/'.$foto.'')) {
                    unlink('./assets/uploads/barang/'.$foto.'');
                }

                $data[] = $newfilename;
                $sql = "UPDATE barang SET id_barang = ?, id_kategori = ?, nama_barang = ?, merk = ?, harga_beli = ?, harga_jual = ?, satuan_barang = ?, stok = ?, tgl_input = ?, tgl_update = ?, gambar = ?  WHERE id = ? ";
            } else {
                set_flashdata("Gagal", "edit telah gagal upload !", "danger");
                redirect("index.php");
                exit;
            }
        }
    } else {
        $sql = "UPDATE barang SET id_barang = ?, id_kategori = ?, nama_barang = ?, merk = ?, harga_beli = ?, harga_jual = ?, satuan_barang = ?, stok = ?, tgl_input = ?, tgl_update = ?  WHERE id = ? ";
    }

    $data[] = $id;
    $row = $connectdb->prepare($sql);
    $row->execute($data);

    set_flashdata("Berhasil", "edit telah sukses !", "success");
    redirect("edit.php?id=".$id);
}

if (!empty(getGet("aksi") == "delete")) {
    $id =  (int)$_GET["id"]; // should be integer (id)
    $sql = "SELECT * FROM barang WHERE id = ?";
    $row = $connectdb->prepare($sql);
    $row->execute(array($id));
    $cek = $row->rowCount();
    if ($cek > 0) {
        $sql_delete = "DELETE FROM barang WHERE id = ?";
        $row_delete = $connectdb->prepare($sql_delete);
        $row_delete->execute(array($id));
        set_flashdata("Berhasil", "delete telah sukses !", "success");
        redirect("index.php");
    } else {
        set_flashdata("Gagal", "delete telah gagal !", "danger");
        redirect("index.php");
    }
}