<?php 
    if (!empty(in_array($_SESSION['codekop_session']['akses'], [1,6]))) {
    } else {
        redirect($baseURL);
    }
    $id =  (int)$_GET["id"];
    $sql = "SELECT * FROM barang_kategori WHERE id = ?";
    $row = $connectdb->prepare($sql);
    $row->execute(array($id));
    $edit = $row->fetch(PDO::FETCH_OBJ);
    if (empty($edit)) {
        redirect($baseURL.'index.php');
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
                <h4 class="mb-0">Edit Kategori</h4>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=update" method="post">
                    <div class="form-group">
                        <label for="">Nama kategori</label>
                        <input type="text" class="form-control" value="<?= $edit->nama_kategori;?>" name="nama_kategori"
                            id="nama_kategori" placeholder="" />
                    </div>

                    <div class="form-group">
                        <label for="">Tgl Input</label>
                        <input type="date" class="form-control" value="<?= $edit->tgl_input;?>" name="tgl_input"
                            id="tgl_input" placeholder="" />
                    </div>

                    <input type="hidden" name="id" value="<?= $id;?>">
                    <button type="submit" class="btn btn-primary btn-md">Save</button>
                    <a href="index.php" class="btn btn-danger btn-md">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>