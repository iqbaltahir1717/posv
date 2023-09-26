<?php
    $sql = "SELECT * FROM pelanggan ORDER BY id DESC LIMIT 1";
    $row = $connectdb->prepare($sql);
    $row->execute();
    $edit = $row->fetch(PDO::FETCH_OBJ);
    if (isset($edit->id)) {
        $cekid = $edit->id + 1;
    } else {
        $cekid = 1;
    }
    $kode = 'PL00'.$cekid;
?>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="title">Add Pelanggan</h4>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=tambah" method="post">
                    <div class="form-group">
                        <label for="">Kode</label>
                        <input type="text" class="form-control" required value="<?= $kode;?>" readonly
                            name="kode_pelanggan" id="kode_pelanggan" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" class="form-control" required name="nama_pelanggan" id="nama_pelanggan"
                            placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="">Telepon</label>
                        <input type="number" class="form-control" required name="telepon_pelanggan"
                            id="telepon_pelanggan" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">E-mail</label>
                        <input type="email" class="form-control" required name="email_pelanggan" id="email_pelanggan"
                            placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <textarea class="form-control" required name="alamat_pelanggan" id="alamat_pelanggan"
                            placeholder=""></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-md">Save</button>
                    <a href="index.php" class="btn btn-danger btn-md">Back</a>
                </form>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>