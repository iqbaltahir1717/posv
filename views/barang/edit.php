<?php 
    if (!empty(in_array($_SESSION['codekop_session']['akses'], [1,6]))) {
    } else {
        redirect($baseURL);
    }
    $id =  (int)$_GET["id"];
    $sql = "SELECT * FROM barang WHERE id = ?";
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
                <h4 class="mb-0">Edit Barang</h4>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=update" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="">Id barang</label>
                        <input type="text" class="form-control" value="<?= $edit->id_barang;?>" name="id_barang"
                            id="id_barang" placeholder="" />
                    </div>

                    <div class="form-group">
                        <label for="">Id kategori</label>
                        <select class="form-control" required name="id_kategori" id="id_kategori">
                            <?php
                                $sql = "SELECT * FROM barang_kategori ORDER BY id DESC";
                                $row = $connectdb->prepare($sql);
                                $row->execute();
                                $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                                foreach ($hasil as $r) {
                            ?>
                            <option <?php if ($edit->id_kategori == $r->id) {
                                        echo 'selected';
                                    } ?> value="<?= $r->id; ?>"><?= $r->nama_kategori; ?></option>
                            <?php }?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Nama barang</label>
                        <input type="text" value="<?= $edit->nama_barang;?>" class="form-control" name="nama_barang"
                            id="nama_barang" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">Merk</label>
                        <input type="text" class="form-control" value="<?= $edit->merk;?>" name="merk" id="merk"
                            placeholder="" />
                    </div>

                    <div class="form-group">
                        <label for="">Harga beli</label>
                        <input type="text" id="rupiah1" class="form-control" value="<?= $edit->harga_beli;?>"
                            name="harga_beli" placeholder="" />
                    </div>

                    <div class="form-group">
                        <label for="">Harga jual</label>
                        <input type="text" id="rupiah2" class="form-control" value="<?= $edit->harga_jual;?>"
                            name="harga_jual" id="harga_jual" placeholder="" />
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
                            <option <?php if ($edit->satuan_barang == $r->satuan) {
                                        echo 'selected';
                                    } ?> value="<?= $r->satuan; ?>"><?= $r->satuan; ?></option>
                            <?php }?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Stok</label>
                        <input type="number" value="<?= $edit->stok;?>" class="form-control" name="stok" id="stok"
                            placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="">Gambar <small class="text-danger">* opsional</small></label>
                        <input type="file" class="form-control" accept="image/*" name="gambar" id="gambar"
                            placeholder="">
                        <br>
                        <a href="<?= url_images($baseURL, 'barang', $edit->gambar);?>" data-toggle="lightbox"
                            data-title="<?=$edit->nama_barang;?>" data-gallery="gallery">
                            <img src="<?= url_images($baseURL, 'barang', $edit->gambar);?>"
                                alt="<?=$edit->nama_barang;?>" class="img-fluid" width="80" />
                        </a>
                    </div>
                    <div class="form-group">
                        <label for="">Tgl input</label>
                        <input type="date" class="form-control" value="<?= $edit->tgl_input;?>" name="tgl_input"
                            id="tgl_input" placeholder="" />
                    </div>
                    <input type="hidden" value="<?= $edit->gambar;?>" name="foto" placeholder="" />
                    <input type="hidden" name="idb" value="<?= $edit->id_barang;?>">
                    <input type="hidden" name="id" value="<?= $id;?>">
                    <button type="submit" class="btn btn-primary btn-md">Save</button>
                    <a href="index.php" class="btn btn-danger btn-md">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>