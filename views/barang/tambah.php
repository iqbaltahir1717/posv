<!-- Page content -->
<?php 
    if (!empty(in_array($_SESSION['codekop_session']['akses'], [1,6]))) {
    } else {
        redirect($baseURL);
    }
    $sql = "SELECT * FROM barang ORDER BY id DESC LIMIT 1";
    $row = $connectdb->prepare($sql);
    $row->execute();
    $edit = $row->fetch(PDO::FETCH_OBJ);
    if (isset($edit->id)) {
        $cekid = $edit->id + 1;
    } else {
        $cekid = 1;
    }
    $kode = 'BR00'.$cekid;
?>
<div class="row">
    <div class="col-sm-7 mx-auto">
        <?php if (!empty(flashdata())) {
            echo flashdata();
        }?>
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h4 class="mb-0">Tambah Barang</h4>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=tambah" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="">ID Barang</label>
                        <input type="text" class="form-control" value="<?= $kode;?>" required name="id_barang"
                            id="id_barang" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">Kategori </label>
                        <select class="form-control" required name="id_kategori" id="id_kategori">
                            <?php
                                $sql = "SELECT * FROM barang_kategori ORDER BY id DESC";
                                $row = $connectdb->prepare($sql);
                                $row->execute();
                                $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                                foreach ($hasil as $r) {
                            ?>
                            <option value="<?= $r->id; ?>"><?= $r->nama_kategori; ?></option>
                            <?php }?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Nama barang</label>
                        <input type="text" class="form-control" required name="nama_barang" id="nama_barang"
                            placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">Merk</label>
                        <input type="text" class="form-control" required name="merk" id="merk" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">Harga beli</label>
                        <input type="text" class="form-control" required name="harga_beli" id="rupiah1" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">Harga jual</label>
                        <input type="text" class="form-control" required name="harga_jual" id="rupiah2" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">Satuan barang</label>
                        <select class="form-control" required name="satuan_barang" id="satuan_barang">
                            <?php
                                $sql = "SELECT * FROM barang_satuan ORDER BY id DESC";
                                $row = $connectdb->prepare($sql);
                                $row->execute();
                                $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                                foreach ($hasil as $r) {
                            ?>
                            <option value="<?= $r->satuan; ?>"><?= $r->satuan; ?></option>
                            <?php }?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Stok</label>
                        <input type="number" class="form-control" required name="stok" id="stok" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="">Gambar <small class="text-danger">* opsional</small></label>
                        <input type="file" class="form-control" accept="image/*" name="gambar" id="gambar"
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
        </div>
    </div>
</div>