<?php 
    if (!empty(in_array($_SESSION['codekop_session']['akses'], [1,6]))) {
    } else {
        redirect($baseURL);
    }
?>
<!-- Page content -->
<div class="row">
    <div class="col-sm-12">
        <?php if (!empty(flashdata())) {
            echo flashdata();
        }?>
        <a href="tambah.php" class="btn btn-primary btn-md" role="button">
            <i class="fa fa-plus mr-1"></i>Add Satuan</a>
        <br><br>
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <h4 class="mb-0">Data Satuan</h4>
            </div>
            <div class="card-body">
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table table-striped" id="example1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Satuan</th>
                                <?php if (!empty(in_array($_SESSION['codekop_session']['akses'], [1,6]))) {?>
                                <th>Aksi</th>
                                <?php }?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no =1;
                                $sql = "SELECT * FROM barang_satuan ORDER BY id DESC";
                                $row = $connectdb->prepare($sql);
                                $row->execute();
                                $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                                foreach ($hasil as $r) {
                            ?>
                            <tr>
                                <td><?= $no; ?></td>

                                <td><?=$r->satuan; ?></td>
                                <?php if (!empty(in_array($_SESSION['codekop_session']['akses'], [1,6]))) {?>
                                <td>
                                    <a href="<?= "edit.php?id=".$r->id;?>" class="btn btn-success btn-sm" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <?php if (!empty(in_array($_SESSION['codekop_session']['akses'], [1]))) {?>
                                    <?php if ($r->id > 1) {?>
                                    <a href="<?= "proses.php?aksi=delete&id=".$r->id;?>" class="btn btn-danger btn-sm"
                                        onclick="javascript:return confirm(`Data ingin dihapus ?`);" title="Delete">
                                        <i class="fa fa-times"></i>
                                    </a>
                                    <?php }
                                    }?>
                                </td>
                                <?php } ?>
                            </tr>
                            <?php $no++;}?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>