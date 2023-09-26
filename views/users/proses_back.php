
<?php
    if (!empty(getGet("aksi") == "tambah")) {
        set_flashdata("Gagal", "delete telah gagal untuk akun demo !", "success");
        redirect("index.php");
    }

    if (!empty(getGet("aksi") == "update")) {
        set_flashdata("Gagal", "delete telah gagal untuk akun demo !", "success");
        redirect("index.php");
    }

    if (!empty(getGet("aksi") == "delete")) {
        set_flashdata("Gagal", "delete telah gagal untuk akun demo !", "success");
        redirect("index.php");
    }
