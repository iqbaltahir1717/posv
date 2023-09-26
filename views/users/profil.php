<?php
    $id =  $_SESSION['codekop_session']['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $row = $connectdb->prepare($sql);
    $row->execute(array($id));
    $edit = $row->fetch(PDO::FETCH_OBJ);
    if (empty($edit)) {
        redirect($baseURL.'index.php');
    }
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
                <h4 class="mb-0">Edit Users</h4>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=update&profil=yes" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="">Full Name</label>
                        <input type="text" class="form-control" required value="<?= $edit->name;?>" name="name"
                            id="name" placeholder="" />
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="text" class="form-control" value="<?= $edit->email;?>" name="email" id="email"
                            placeholder="" />
                    </div>

                    <div class="form-group">
                        <label for="">Telepon</label>
                        <input type="text" class="form-control" value="<?= $edit->telepon;?>" name="telepon"
                            id="telepon" placeholder="" />
                    </div>

                    <div class="form-group">
                        <label for="">Alamat</label>
                        <textarea class="form-control" name="alamat" id="alamat"
                            placeholder=""><?= $edit->alamat;?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Foto</label>
                        <input type="file" class="form-control" accept="image/*" name="avatar" id="avatar"
                            placeholder="" />
                    </div>

                    <div class="form-group">
                        <label for="">Username</label>
                        <input type="text" class="form-control" readonly required value="<?= $edit->user;?>" name="user"
                            id="user" placeholder="" />
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" class="form-control" required value="" name="pass" id="pass"
                            placeholder="" />
                    </div>
                    <input type="hidden" name="id" value="<?= $id;?>">
                    <input type="hidden" value="<?= $edit->avatar;?>" name="foto" placeholder="" />
                    <button type="submit" class="btn btn-primary btn-md">Save</button>
                    <a href="index.php" class="btn btn-danger btn-md">Back</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fa fa-image mr-1"></i> Foto</h4>
            </div>
            <div class="card-body">
                <img src="<?= $baseURL.'assets/uploads/users/'.$edit->avatar;?>" class="img-fluid w-100">
            </div>
        </div>
    </div>
</div>