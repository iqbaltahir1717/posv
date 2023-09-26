<?php if (!empty($_SESSION['codekop_session']['akses'] != 1)) {
    redirect($baseURL);
}?>
<!-- Page content -->
<div class="row">
    <div class="col-sm-12">
        <?php if (!empty(flashdata())) {
            echo flashdata();
        }?>
        <a href="tambah.php" class="btn btn-primary btn-md" role="button">
            <i class="fa fa-plus mr-1"></i> Add Users</a>
        <br><br>
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h4 class="mb-0">Data Users</h4>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table class="table table-striped" id="example1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Akses</th>
                                <th>Active</th>
                                <th>Created at</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no =1;
                                $sql = "SELECT hak_akses.hak_akses, users.* FROM users LEFT JOIN hak_akses 
                                        ON users.akses = hak_akses.id ORDER BY users.id DESC";
                                $row = $connectdb->prepare($sql);
                                $row->execute();
                                $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                                foreach ($hasil as $r) {
                            ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><?=$r->name; ?></td>
                                <td><?=$r->user; ?></td>
                                <td><?=$r->hak_akses; ?></td>
                                <td><?php if ($r->active == '1') {
                                    echo 'Aktif';
                                } else {
                                    echo 'Tdk Aktif';
                                } ?></td>
                                <td><?=$r->created_at; ?></td>
                                <td>
                                    <a href="<?= "edit.php?id=".$r->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="<?= "proses.php?aksi=delete&id=".$r->id; ?>" class="btn btn-danger btn-sm"
                                        onclick="javascript:return confirm(`Data ingin dihapus ?`);" title="Delete">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php $no++;
}?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>