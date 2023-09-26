<?php 
    if (!empty(in_array($_SESSION['codekop_session']['akses'], [1,6]))) {
    } else {
        redirect($baseURL);
    }
?>
<!-- Page content -->
<div class="row">
    <div class="col-sm-7 mx-auto">
        <?php if (!empty(flashdata())) {
            echo flashdata();
        }?>
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h4 class="mb-0">Tambah Kategori</h4>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=tambah" method="post">
                    <div class="form-group">
                        <label for="">Nama kategori</label>
                        <input type="text" class="form-control" required name="nama_kategori" id="nama_kategori"
                            placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="">Tgl input</label>
                        <input type="date" value="<?= date('Y-m-d');?>" class="form-control" required name="tgl_input"
                            id="tgl_input" placeholder="">
                    </div>
                    <button type="submit" class="btn btn-primary btn-md">Save</button>
                    <a href="index.php" class="btn btn-danger btn-md">Back</a>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
</div>