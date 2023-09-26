<?php
    $id =  (int)$_GET["id"];
    $sql = "SELECT * FROM pelanggan WHERE id = ?";
    $row = $connectdb->prepare($sql);
    $row->execute(array($id));
    $edit = $row->fetch(PDO::FETCH_OBJ);
?>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <?php if (!empty(flashdata())) {
            echo flashdata();
        }?>
        <div class="card">
            <div class="card-header">
                <h4 class="title">Edit Pelanggan</h4>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=edit" method="post">
                    <div class="form-group">
                        <label for="">Kode</label>
                        <input type="text" class="form-control" value="<?= $edit->kode_pelanggan;?>" readonly
                            name="kode_pelanggan" id="kode_pelanggan" placeholder="" />
                    </div>
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" class="form-control" value="<?= $edit->nama_pelanggan;?>"
                            name="nama_pelanggan" id="nama_pelanggan" placeholder="" />
                    </div>
                    <div class="form-group">
                        <label for="">Telepon</label>
                        <input type="number" class="form-control" value="<?= $edit->telepon_pelanggan;?>"
                            name="telepon_pelanggan" id="telepon_pelanggan" placeholder="" />
                    </div>
                    <div class="form-group">
                        <label for="">E-mail</label>
                        <input type="email" class="form-control" value="<?= $edit->email_pelanggan;?>"
                            name="email_pelanggan" id="email_pelanggan" placeholder="" />
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <textarea class="form-control" name="alamat_pelanggan" id="alamat_pelanggan"
                            placeholder=""><?= $edit->alamat_pelanggan;?></textarea>
                    </div>
                    <input type="hidden" name="id" value="<?= $edit->id;?>">
                    <button type="submit" class="btn btn-primary btn-md">Save</button>
                    <a href="index.php" class="btn btn-danger btn-md">Back</a>
                </form>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>