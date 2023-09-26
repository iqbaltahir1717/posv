<?php
    function redirect($url)
    {
        echo '<script>window.location="'.$url.'";</script>';
    }

    function cek_id($connectdb, $table, $col, $val)
    {
        $sql = "SELECT * FROM $table WHERE $col = ? ORDER BY id DESC LIMIT 1";
        $row = $connectdb->prepare($sql);
        $row->execute(array($val));
        $cek = $row->rowCount();
        if ($cek > 0) {
            // return time();
            // Get the current timestamp
            $timestamp = time(); // You can also use a specific timestamp if needed
            // Generate a random number between 1000 and 9999
            $randomNumber = rand(10, 999);
            // Combine the timestamp and the random number to create the ID
            $randomID = $timestamp . $randomNumber;
            // Output the random ID
            return $randomID;
        } else {
            return $val;
        }
    }

    function url_images($baseURL, $dir = null, $file = null)
    {
        if ($dir == null) {
            if ($file == '') {
                return $baseURL.'assets/dist/img/no-image.jpg';
            } else {
                if (file_exists(__DIR__."/assets/uploads/".$file)) {
                    return $baseURL.'assets/uploads/'.$file;
                } else {
                    return $baseURL.'assets/dist/img/no-image.jpg';
                }
            }
        } else {
            if ($dir == '' || $file == '') {
                return $baseURL.'assets/dist/img/no-image.jpg';
            } else {
                if (file_exists(__DIR__."/assets/uploads/".$dir."/".$file)) {
                    return $baseURL.'assets/uploads/'.$dir."/".$file;
                } else {
                    return $baseURL.'assets/dist/img/no-image.jpg';
                }
            }
        }
    }

    function getSegments($baseURL, $segment)
    {
        $explode_url = explode('/', $_SERVER['REQUEST_URI']);
        $rowurl = count(explode('/', $baseURL));
        $row_url = count(parse_url($baseURL));
        $urlc = $rowurl-$row_url;
        $rurl = $urlc-1;
        $cont = $segment + $rurl;
        $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        if (isset($uriSegments[$cont])) {
            return $uriSegments[$cont];
        } else {
            return '';
        }
    }

    function set_flashdata($pesan, $aksi, $tipe)
    {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi'  => $aksi,
            'tipe'  => $tipe
        ];
    }

    function flashdata()
    {
        if (isset($_SESSION['flash'])) {
            echo '<div class="alert alert-' . $_SESSION['flash']['tipe'] . '">
                    <strong>' . $_SESSION['flash']['pesan'] . '</strong> ' . $_SESSION['flash']['aksi'] . '
                </div>';
            unset($_SESSION['flash']);
        }
    }
    
    function alert_swal()
    {
        if (isset($_SESSION['flash'])) {
            echo '<script>
                Swal.fire({
                    title: "'.$_SESSION['flash']['pesan'].'",
                    html: "'.$_SESSION['flash']['aksi'].'",
                    icon: "'.$_SESSION['flash']['tipe'].'",
                    showConfirmButton: false,
                    timer: 1500
                });
              </script>';
            unset($_SESSION['flash']);
        }
    }

    function getPost($name, $t = null)
    {
        if ($t == true) {
            return filter_var(strip_tags($_POST[''.$name.''] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        } else {
            return strip_tags($_POST[''.$name.''] ?? '');
        }
    }

    function getGet($name, $t = null)
    {
        if ($t == true) {
            return filter_var(strip_tags($_GET[''.$name.''] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        } else {
            return strip_tags($_GET[''.$name.''] ?? '');
        }
    }

    function deleteFolder($str)
    {
        //It it's a file.
        if (is_file($str)) {
            //Attempt to delete it.
            return unlink($str);
        }
        //If it's a directory.
        elseif (is_dir($str)) {
            //Get a list of the files in this directory.
            $scan = glob(rtrim($str, '/').'/*');
            //Loop through the list of files.
            foreach ($scan as $index=>$path) {
                //Call our recursive function.
                deleteFolder($path);
            }
            //Remove the directory itself.
            return @rmdir($str);
        }
    }

    function get_tables_query($conn, $query, $cari, $where, $iswhere)
    {
        // Ambil data yang di ketik user pada textbox pencarian
        $search = htmlspecialchars($_POST['search']['value']);
        // Ambil data limit per page
        $limit = preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['length']}");
        // Ambil data start
        $start =preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['start']}");

        if ($where != null) {
            $setWhere = array();
            foreach ($where as $key => $value) {
                $setWhere[] = $key."='".$value."'";
            }
            $fwhere = implode(' AND ', $setWhere);

            if (!empty($iswhere)) {
                $sql = $conn->prepare($query." WHERE  $iswhere AND ".$fwhere);
                $sql->execute();
            } else {
                $sql = $conn->prepare($query." WHERE ".$fwhere);
                $sql->execute();
            }
            $sql_count = $sql->rowCount();

            $cari = implode(" LIKE '%".$search."%' OR ", $cari)." LIKE '%".$search."%'";
            
            // Untuk mengambil nama field yg menjadi acuan untuk sorting
            $order_field = $_POST['order'][0]['column'];

            // Untuk menentukan order by "ASC" atau "DESC"
            $order_ascdesc = $_POST['order'][0]['dir'];
            $order = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;

            if (!empty($iswhere)) {
                $sql_data = $conn->prepare($query." WHERE $iswhere AND ".$fwhere." AND (".$cari.")".$order." LIMIT ".$limit." OFFSET ".$start);
                $sql_data->execute();
            } else {
                $sql_data = $conn->prepare($query." WHERE ".$fwhere." AND (".$cari.")".$order." LIMIT ".$limit." OFFSET ".$start);
                $sql_data->execute();
            }
            
            if (isset($search)) {
                if (!empty($iswhere)) {
                    $sql_cari =  $conn->prepare($query." WHERE $iswhere AND ".$fwhere." AND (".$cari.")");
                    $sql_cari->execute();
                } else {
                    $sql_cari =  $conn->prepare($query." WHERE ".$fwhere." AND (".$cari.")");
                    $sql_cari->execute();
                }
                $sql_filter_count = $sql_cari->rowCount();
            } else {
                if (!empty($iswhere)) {
                    $sql_filter = $conn->prepare($query." WHERE $iswhere AND ".$fwhere);
                    $sql_filter->execute();
                } else {
                    $sql_filter = $conn->prepare($query." WHERE ".$fwhere);
                    $sql_filter->execute();
                }
                $sql_filter_count = $sql_filter->rowCount();
            }
            $data = $sql_data->fetchAll();
        } else {
            if (!empty($iswhere)) {
                $sql = $conn->prepare($query." WHERE  $iswhere ");
                $sql->execute();
            } else {
                $sql = $conn->prepare($query);
                $sql->execute();
            }
            $sql_count = $sql->rowCount();

            $cari = implode(" LIKE '%".$search."%' OR ", $cari)." LIKE '%".$search."%'";
            
            // Untuk mengambil nama field yg menjadi acuan untuk sorting
            $order_field = $_POST['order'][0]['column'];

            // Untuk menentukan order by "ASC" atau "DESC"
            $order_ascdesc = $_POST['order'][0]['dir'];
            $order = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;

            if (!empty($iswhere)) {
                $sql_data = $conn->prepare($query." WHERE $iswhere AND (".$cari.")".$order." LIMIT ".$limit." OFFSET ".$start);
                $sql_data->execute();
            } else {
                $sql_data = $conn->prepare($query." WHERE (".$cari.")".$order." LIMIT ".$limit." OFFSET ".$start);
                $sql_data->execute();
            }

            if (isset($search)) {
                if (!empty($iswhere)) {
                    $sql_cari =  $conn->prepare($query." WHERE $iswhere AND (".$cari.")");
                    $sql_cari->execute();
                } else {
                    $sql_cari =  $conn->prepare($query." WHERE (".$cari.")");
                    $sql_cari->execute();
                }
                $sql_filter_count = $sql_cari->rowCount();
            } else {
                if (!empty($iswhere)) {
                    $sql_filter = $conn->prepare($query." WHERE $iswhere");
                    $sql_filter->execute();
                } else {
                    $sql_filter = $conn->prepare($query);
                    $sql_filter->execute();
                }
                $sql_filter_count = $sql_filter->rowCount();
            }
            
            $data = $sql_data->fetchAll();
        }
        $callback = array(
            'draw' => $_POST['draw'], // Ini dari datatablenya
            'recordsTotal' => $sql_count,
            'recordsFiltered'=>$sql_filter_count,
            'data'=>$data
        );
        return json_encode($callback); // Convert array $callback ke json
    }
