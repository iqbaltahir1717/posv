<div class="row">
    <div class="col-md-12">
        <?php if (!empty(flashdata())) {
            echo flashdata();
        }?>
        <a href="tambah.php" class="btn btn-primary" role="button">Add Pelanggan</a>
        <br><br>
        <div class="card">
            <div class="card-header">
                <h4 class="title">Daftar Pelanggan</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="example1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Created at</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no =1;
                                $sql = "SELECT * FROM pelanggan ORDER BY id DESC";
                                $row = $connectdb->prepare($sql);
                                $row->execute();
                                $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                                foreach ($hasil as $r) {
                            ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><?=$r->kode_pelanggan; ?></td>
                                <td><?=$r->nama_pelanggan; ?></td>
                                <td><?=$r->telepon_pelanggan; ?></td>
                                <td><?=$r->email_pelanggan; ?></td>
                                <td><?=$r->created_at; ?></td>
                                <td>
                                    <a href="<?= "edit.php?id=".$r->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <?php if (!empty(in_array($_SESSION['codekop_session']['akses'], [1]))) {?>
                                    <a href="<?= "proses.php?aksi=delete&id=".$r->id;?>" class="btn btn-danger btn-sm"
                                        onclick="javascript:return confirm(`Data ingin dihapus ?`);" title="Delete">
                                        <i class="fa fa-times"></i>
                                    </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php $no++; }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>