<!-- Page content -->
<?php 
    if (!empty(in_array($_SESSION['codekop_session']['akses'], [1,6]))) {
    } else {
        redirect($baseURL);
    }
?>
<div class="row">
    <div class="col-sm-7 mx-auto">
        <?php if (!empty(flashdata())) {
            echo flashdata();
        }?>
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h4 class="mb-0">Impor File</h4>
            </div>
            <div class="card-body">
                <form action="proses.php?aksi=import" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputFile">File Upload (Excel)</label>
                        <input type="file" name="berkas_excel"
                            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                            class="form-control" id="exampleInputFile">
                    </div>
                    <button type="submit" class="btn btn-primary btn-md">Save</button>
                    <a href="<?= $baseURL.'assets/file/format_import.xlsx';?>" class="btn btn-success btn-md">
                        <i class="fa fa-download mr-1"></i> Format File Excel
                    </a>
                    <a href="index.php" class="btn btn-danger btn-md">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>