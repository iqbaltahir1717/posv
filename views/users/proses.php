<?php 
    if (!empty(getGet("aksi") == "tambah")) {
        if (!empty($_SESSION['codekop_session']['akses'] != 1)) {
            redirect($baseURL);
        }
        $pass = password_hash(htmlspecialchars(getPost("pass")), PASSWORD_DEFAULT);

        $sqlk = "SELECT * FROM users WHERE user = ?";
        $rowk = $connectdb->prepare($sqlk);
        $rowk->execute(array(htmlspecialchars(getPost("user"))));
        $count = $rowk->rowCount();
        if ($count > 0) {
            set_flashdata("Gagal", "username telah dipakai !", "danger");
            redirect("tambah.php");
        } else {
            $data[] =  htmlspecialchars(getPost("name"));
            $data[] =  htmlspecialchars(getPost("user"));
            $data[] =  $pass;
            $data[] = 'avatar.jpg';
            $data[] =  getPost("akses");
            $data[] =  getPost("active");
            $data[] =  date('Y-m-d H:i:s');
    
            $sql = "INSERT INTO users (name,user,pass, avatar, akses,active,created_at ) VALUES (?,?,?,?,?,?,?)";
            $row = $connectdb->prepare($sql);
            $row->execute($data);
            
            set_flashdata("Berhasil", "tambah telah sukses !", "success");
            redirect("index.php");
        }
    }

    if (!empty(getGet("aksi") == "update")) {
        $id =  (int)getPost("id");
        // set_time_limit(0);
        $allowedImageType = array("image/gif", "image/JPG", "image/jpeg", "image/pjpeg", "image/png", "image/x-png", 'image/webp');
        if ($_FILES['avatar']["size"] > 0) {
            
            $filepath = $_FILES['avatar']['tmp_name'];
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
                if (isset($_GET['profil'])) {
                    redirect("profil.php");
                } else {
                    redirect("edit.php?id=".$id);
                }
                exit;
            }else if ($_FILES['avatar']["error"] > 0) {
                set_flashdata("Gagal", "You can only upload JPG, PNG and GIF file", "danger");
                if (isset($_GET['profil'])) {
                    redirect("profil.php");
                } else {
                    redirect("edit.php?id=".$id);
                }
                exit;
            } elseif (!in_array($_FILES['avatar']["type"], $allowedImageType)) {
                set_flashdata("Gagal", "You can only upload JPG, PNG and GIF file", "danger");
                if (isset($_GET['profil'])) {
                    redirect("profil.php");
                } else {
                    redirect("edit.php?id=".$id);
                }
                exit;
            } elseif (round($_FILES['avatar']["size"] / 1024) > 4096) {
                set_flashdata("Gagal", "Besar Gambar Tidak Boleh Lebih Dari 4 MB", "danger");
                if (isset($_GET['profil'])) {
                    redirect("profil.php");
                } else {
                    redirect("edit.php?id=".$id);
                }
                exit;
            } else {
                $target_path = './assets/uploads/users/';
                $temp = explode(".", $_FILES["avatar"]["name"]);
                $newfilename = round(microtime(true)) . '.' . end($temp);
                $target_path = $target_path . basename($newfilename);
                
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path)) {
                    //post foto lama
                    $foto = getPost('foto');
                    //remove foto di direktori
                    if ($foto == 'avatar.jpg') {
                    } else {
                        if (file_exists('./assets/uploads/users/'.$foto.'')) {
                            unlink('./assets/uploads/users/'.$foto.'');
                        }
                    }

                    $pass = password_hash(htmlspecialchars(getPost("pass")), PASSWORD_DEFAULT);

                    $data[] =  htmlspecialchars(getPost("name"));
                    $data[] =  htmlspecialchars(getPost("user"));
                    $data[] =  $pass;
                    if (isset($_GET['profil'])) {
                    } else {
                        $data[] =  htmlspecialchars(getPost("akses"));
                        $data[] =  htmlspecialchars(getPost("active"));
                    }
                    $data[] =  date('Y-m-d H:i:s');
                    $data[] =  $newfilename;
                    $data[] =  htmlspecialchars(getPost("email"));
                    $data[] =  htmlspecialchars(getPost("telepon"));
                    $data[] =  htmlspecialchars(getPost("alamat"));
                    $data[] =  $id;

                    if (isset($_GET['profil'])) {
                        $sql = "UPDATE users SET name = ?, user = ?, pass = ?, created_at = ?, avatar =?, email =?, telepon =?, alamat =?   WHERE id = ? ";
                    } else {
                        if (!empty($_SESSION['codekop_session']['akses'] != 1)) {
                            redirect($baseURL);
                        }
                        $sql = "UPDATE users SET name = ?, user = ?, pass = ?, akses = ?, active = ?, created_at = ?, avatar =?, email =?, telepon =?, alamat =?   WHERE id = ? ";
                    }
                    
                    $row = $connectdb->prepare($sql);
                    $row->execute($data);
                } else {
                    set_flashdata("gagal", "edit telah gagal upload !", "danger");
                    if (isset($_GET['profil'])) {
                        redirect("profil.php");
                    } else {
                        redirect("edit.php?id=".$id);
                    }
                    exit;
                }
            }
        } else {
            $pass = password_hash(htmlspecialchars(getPost("pass")), PASSWORD_DEFAULT);

            $data[] =  htmlspecialchars(getPost("name"));
            $data[] =  htmlspecialchars(getPost("user"));
            $data[] =  $pass;
            if (isset($_GET['profil'])) {
            } else {
                $data[] =  htmlspecialchars(getPost("akses"));
                $data[] =  htmlspecialchars(getPost("active"));
            }
            $data[] =  date('Y-m-d H:i:s');
            $data[] =  htmlspecialchars(getPost("email"));
            $data[] =  htmlspecialchars(getPost("telepon"));
            $data[] =  htmlspecialchars(getPost("alamat"));

            $data[] = $id;
            if (isset($_GET['profil'])) {
                $sql = "UPDATE users SET name = ?, user = ?, pass = ?,  created_at = ?, email =?, telepon =?, alamat =?  WHERE id = ? ";
            } else {
                if (!empty($_SESSION['codekop_session']['akses'] != 1)) {
                    redirect($baseURL);
                }
                $sql = "UPDATE users SET name = ?, user = ?, pass = ?, akses = ?, active = ?, created_at = ?, email =?, telepon =?, alamat =?  WHERE id = ? ";
            }
            $row = $connectdb->prepare($sql);
            $row->execute($data);
        }

        set_flashdata("Berhasil", "edit telah sukses !", "success");
        if (isset($_GET['profil'])) {
            redirect("profil.php");
        } else {
            redirect("edit.php?id=".$id);
        }
    }

    if (!empty(getGet("aksi") == "delete")) {
        if (!empty($_SESSION['codekop_session']['akses'] != 1)) {
            redirect($baseURL);
        }
        $id =  (int)getGet("id"); // should be integer (id)
        $sql = "SELECT * FROM users WHERE id = ?";
        $row = $connectdb->prepare($sql);
        $row->execute(array($id));
        $cek = $row->rowCount();
        if ($cek > 0) {
            $sql_delete = "DELETE FROM users WHERE id = ?";
            $row_delete = $connectdb->prepare($sql_delete);
            $row_delete->execute(array($id));
            set_flashdata("Berhasil", "delete telah sukses !", "success");
            redirect("index.php");
        } else {
            set_flashdata("Gagal", "delete telah gagal !", "success");
            redirect("index.php");
        }
    }