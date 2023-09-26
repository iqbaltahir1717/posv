<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="title">Add Supplier</h4>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=tambah" method="post">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" class="form-control" required name="nama_supplier" id="nama_supplier"
                            placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">Alamat</label>
                        <input type="text" class="form-control" required name="alamat_supplier" id="alamat_supplier"
                            placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">Telepon</label>
                        <input type="text" class="form-control" required name="telepon_supplier" id="telepon_supplier"
                            placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="text" class="form-control" required name="email_supplier" id="email_supplier"
                            placeholder="">
                    </div>
                    <button type="submit" class="btn btn-primary btn-md">Save</button>
                    <a href="index.php" class="btn btn-danger btn-md">Back</a>
                </form>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>