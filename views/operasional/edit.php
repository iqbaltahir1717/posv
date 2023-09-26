<?php
    $id =  (int)$_GET["id"];
    $sql = "SELECT * FROM operasional WHERE id = ?";
    $row = $connectdb->prepare($sql);
    $row->execute(array($id));
    $edit = $row->fetch(PDO::FETCH_OBJ);
?>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Edit Operasional</h5>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=update" method="post">
                <div class="form-group">
                    <label for="nama_operasional">Nama operasional</label>
                    <input type="text" class="form-control" value="<?= $edit->nama_operasional;?>" name="nama_operasional" id="nama_operasional" placeholder=""/>
                </div>

                <div class="form-group">
                    <label for="status_operasional">Status</label>
                    <select class="form-control" name="status_operasional" required id="status_operasional" placeholder="">
                        <option value="" disabled selected>- pilih -</option>
                        <option <?= $edit->status_operasional == 'Pemasukan' ? 'selected' : '' ;?>>Pemasukan</option>
                        <option <?= $edit->status_operasional == 'Pengeluaran' ? 'selected' : '' ;?>>Pengeluaran</option>
                    </select>   
                </div>

               <div class="form-group">
                    <label for="harga_operasional">Harga</label>
                    <input type="number" class="form-control" value="<?= $edit->harga_operasional;?>" name="harga_operasional" id="harga_operasional" placeholder=""/>
                </div>
                
                <div class="form-group">
                    <label for="tgl_input">Tgl input</label>
                    <input type="date" class="form-control" value="<?= $edit->tgl_input;?>" name="tgl_input" id="tgl_input" placeholder=""/>
                </div>
                
                <div class="form-group">
                    <label for="ket_operasional">Keterangan</label>
                    <textarea class="form-control" name="ket_operasional" id="ket_operasional" placeholder=""><?= $edit->ket_operasional;?></textarea>
                </div>
                <input type="hidden" name="id" value="<?= $edit->id;?>">
                <button type="submit" class="btn btn-primary btn-md">Save</button>
                <a href="index.php" class="btn btn-danger btn-md">Back</a>
            </form>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>