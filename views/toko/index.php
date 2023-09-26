<?php 
    if (!empty($_SESSION['codekop_session']['akses'] != 1)) {
        redirect($baseURL);
    }
    $sql = "SELECT * FROM toko WHERE id = 1";
    $row = $connectdb->prepare($sql);
    $row->execute();
    $edit = $row->fetch(PDO::FETCH_OBJ);
?>
<!-- Page content -->
<div class="row">
    <div class="col-sm-8">
        <?php if (!empty(flashdata())) {
            echo flashdata();
        }?>
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h4 class="mb-0">Edit Pengaturan</h4>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=update" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="">Nama toko</label>
                        <input type="text" required class="form-control" value="<?= $edit->nama_toko;?>"
                            name="nama_toko" id="nama_toko" placeholder="" />
                    </div>

                    <div class="form-group">
                        <label for="">Alamat toko</label>
                        <textarea class="form-control" required name="alamat_toko" id="alamat_toko"
                            placeholder=""><?= $edit->alamat_toko;?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="">Telepon</label>
                        <input type="text" required class="form-control" value="<?= $edit->tlp;?>" name="tlp" id="tlp"
                            placeholder="" />
                    </div>
                    <div class="form-group">
                        <label for="">Nama pemilik</label>
                        <input type="text" required class="form-control" value="<?= $edit->nama_pemilik;?>"
                            name="nama_pemilik" id="nama_pemilik" placeholder="" />
                    </div>
                    <div class="form-group">
                        <label for="">Logo</label>
                        <input type="file" required class="form-control" accept="image/*" name="logo" id="logo"
                            placeholder="" />
                    </div>
                    <input type="hidden" required value="<?= $edit->logo;?>" name="foto" placeholder="" />
                    <button type="submit" class="btn btn-primary btn-md">Save</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fa fa-image mr-1"></i> Logo</h4>
            </div>
            <div class="card-body">
                <img src="<?= $baseURL.'assets/uploads/toko/'.$edit->logo;?>" class="img-fluid">
            </div>
        </div>
    </div>
</div>