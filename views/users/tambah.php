<?php if (!empty($_SESSION['codekop_session']['akses'] != 1)) {
    redirect($baseURL);
}?>
<!-- Page content -->
<div class="row">
    <div class="col-sm-7 mx-auto">
        <?php if (!empty(flashdata())) {
            echo flashdata();
        }?>
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h4 class="mb-0">Tambah Users</h4>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=tambah" method="post">

                    <div class="form-group">
                        <label for="">Full Name</label>
                        <input type="text" class="form-control" required name="name" id="name" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">Username</label>
                        <input type="text" class="form-control" required name="user" id="user" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" class="form-control" required name="pass" id="pass" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">Hak Akses</label>
                        <?php
                            $sql1   = "SELECT * FROM hak_akses";
                            $row1   = $connectdb->prepare($sql1);
                            $row1->execute();
                            $hsl    = $row1->fetchAll(PDO::FETCH_OBJ);
                            $n = 0;
                            foreach ($hsl as $r) {
                        ?>
                        <br>
                        <input type="radio" name="akses" value="<?= $r->id; ?>"> <?= $r->hak_akses; ?>
                        <?php $n++;}?>
                    </div>

                    <div class="form-group">
                        <label for="">Active</label>

                        <select class="form-control" name="active">
                            <option value="1">
                                Aktif
                            </option>
                            <option value="0">
                                Tdk Aktif
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-md">Save</button>
                    <a href="index.php" class="btn btn-danger btn-md">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>