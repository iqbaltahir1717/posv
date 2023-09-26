<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Add Operasional</h5>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=tambah" method="post">
                <div class="form-group">
                    <label for="nama_operasional">Nama Operasional</label>
                    <input type="text" class="form-control" name="nama_operasional" required id="nama_operasional" placeholder="">
                </div>

                <div class="form-group">
                    <label for="status_operasional">Status</label>
                    <select class="form-control" name="status_operasional" required id="status_operasional" placeholder="">
                        <option value="" disabled selected>- pilih -</option>
                        <option>Pemasukan</option>
                        <option>Pengeluaran</option>
                    </select>   
                </div>

                <div class="form-group">
                    <label for="harga_operasional">Harga</label>
                    <input type="number" class="form-control" required name="harga_operasional" id="harga_operasional" placeholder="">
                </div>
                <div class="form-group">
                    <label for="tgl_input">Tgl Input</label>
                    <input type="date" class="form-control" required name="tgl_input" id="tgl_input" placeholder="">
                </div>

                <div class="form-group">
                    <label for="ket_operasional">Keterangan</label>
                    <textarea class="form-control" name="ket_operasional" id="ket_operasional" placeholder=""></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-md">Save</button>
                <a href="index.php" class="btn btn-danger btn-md">Back</a>
            </form>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>