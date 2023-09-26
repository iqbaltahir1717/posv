<?php
    $id =  (int)$_GET["id"];
    $sql = "SELECT * FROM supplier WHERE id = ?";
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
                <h4 class="title">Edit Supplier</h4>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=update" method="post">
                    <div class="form-group">
                        <label for="">Nama </label>
                        <input type="text" class="form-control" value="<?= $edit->nama_supplier;?>" name="nama_supplier"
                            id="nama_supplier" placeholder="" />
                    </div>

                    <div class="form-group">
                        <label for="">Alamat</label>
                        <input type="text" class="form-control" value="<?= $edit->alamat_supplier;?>"
                            name="alamat_supplier" id="alamat_supplier" placeholder="" />
                    </div>

                    <div class="form-group">
                        <label for="">Telepon</label>
                        <input type="text" class="form-control" value="<?= $edit->telepon_supplier;?>"
                            name="telepon_supplier" id="telepon_supplier" placeholder="" />
                    </div>

                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="text" class="form-control" value="<?= $edit->email_supplier;?>"
                            name="email_supplier" id="email_supplier" placeholder="" />
                    </div>

                    <div class="form-group">
                        <label for="">Created at</label>
                        <input type="datetime-local" class="form-control" value="<?= $edit->created_at;?>"
                            name="created_at" id="created_at" placeholder="" />
                    </div>

                    <input type="hidden" name="id" value="<?= $id;?>">
                    <button type="submit" class="btn btn-primary btn-md">Save</button>
                    <a href="index.php" class="btn btn-danger btn-md">Back</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>