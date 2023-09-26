<?php
    $id =  getGet("id",true);
    $sql = "SELECT users.name, pelanggan.nama_pelanggan, penjualan.* 
            FROM penjualan 
            LEFT JOIN users 
            ON penjualan.id_member = users.id 
            LEFT JOIN pelanggan 
            ON penjualan.id_pelanggan=pelanggan.id WHERE no_trx = ?";
    $row = $connectdb->prepare($sql);
    $row->execute(array($id));
    $edit = $row->fetch(PDO::FETCH_OBJ);
    if(!$edit) {
        redirect('index.php');
    }
?>
<?php if(!empty(flashdata())){ echo flashdata(); }?>
<div class="row">
    <div class="col-md-4">
        <a class="btn btn-danger btn-md" href="<?= 'index.php';?>" role="button">
            <i class="fas fa-angle-left mr-1"></i> Kembali
        </a>
        <a href="<?= "../helper/cetak/cetak.php?no=".$id?>" target="_blank" class="btn btn-primary btn-md"><i
                class="fas fa-print mr-1"></i>
            Print
        </a>
        <div class="card mt-3">
            <div class="card-header">
                Detail Transaksi
            </div>
            <div class="card-body">
                <form method="post" action="proses.php?aksi=editlap">
                    <div class="row">
                        <div class="col-sm-4">No trx</div>
                        <div class="col-sm-8"><?= $edit->no_trx;?></div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-4">ID Kasir</div>
                        <div class="col-sm-8"><?= $edit->name;?></div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-4">ID pelanggan</div>
                        <div class="col-sm-8"><?= $edit->nama_pelanggan ?? '-';?></div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-4">Jumlah</div>
                        <div class="col-sm-8"><?= $edit->jumlah;?></div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-4">Total Penjualan</div>
                        <div class="col-sm-8">Rp<?= number_format($edit->total);?>,-</div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-4">Dibayar</div>
                        <div class="col-sm-8"><input type="number" class="form-control" value="<?= $edit->bayar;?>"
                                name="dibayar"></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-4">Kembali</div>
                        <div class="col-sm-8">Rp<?= number_format($edit->bayar - $edit->total);?>,-</div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-4">Tanggal input</div>
                        <div class="col-sm-8"><?= $edit->tanggal_input;?></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-4">Created at</div>
                        <div class="col-sm-8"><?= $edit->created_at;?></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-4">Aksi</div>
                        <div class="col-sm-8">
                            <input type="hidden" class="form-control" value="<?= $edit->total;?>"
                                name="total">
                            <input type="hidden" class="form-control" value="<?= $edit->no_trx;?>"
                                name="notrx">
                            <button type="submit" class="btn btn-primary btn-md">Simpan</button>    
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <button type="button" class="btn btn-primary btn-md mt-1 mb-2" data-toggle="modal" data-target="#modelId">
            <i class="fa fa-search mr-1"></i> Daftar Barang
        </button>
        <div class="card mt-3">
            <div class="card-header">
                Daftar Transaksi
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm w-100">
                        <tr>
                            <td><b>No</b></td>
                            <td><b>Nama</b></td>
                            <td><b>Harga</b></td>
                            <td><b>Qty</b></td>
                            <td><b>Diskon</b></td>
                            <td><b>Total</b></td>
                            <td><b>Aksi</b></td>
                        </tr>
                        <?php 
                            $no =1;
                            $diskon = 0;
                            $sql = "SELECT * FROM penjualan_detail 
                                WHERE no_trx = ?
                                ORDER BY id ASC";
                            $row = $connectdb->prepare($sql);
                            $row->execute(array($id));
                            $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                            foreach($hasil as $r){
                        ?>
                        <tr>
                            <form method="post" action="proses.php?aksi=editstok">
                                <td><?=$no;?></td>
                                <td><?=$r->nama_barang;?></td>
                                <td><?= number_format($r->jual);?> x </td>
                                <td><input type="number" style="width:100px" value="<?=$r->qty;?>" class="form-control" name="qty"></td>
                                <td><input type="number" style="width:150px" value="<?=$r->diskon;?>" class="form-control" name="diskon">
                                </td>
                                <td>Rp<?=number_format($r->total);?>,-</td>
                                <td>
                                    <input type="hidden" name="id" value="<?= $r->id;?>">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                        <a href="proses.php?aksi=deletestok&id=<?= $r->id;?>" class="btn btn-danger btn-sm"
                                            onclick="javascript: return confirm('Apakah barang yang telah terjual akan dihapus ?')">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </form>
                        </tr>
                        <?php $no++; $diskon += $r->diskon;}?>
                        <tr>
                            <th colspan="3">Total</th>
                            <th><?= $edit->jumlah;?></th>
                            <th>Rp<?= number_format($diskon);?></th>
                            <th>Rp<?= number_format($edit->total);?></th>
                            <th>#</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Barang -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-search mr-1"></i> Daftar Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered w-100" id="table-artikel-query">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Barang</th>
                                <th>Gambar</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Merk</th>
                                <th>Harga</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
var tabel = null;
$(document).ready(function() {
    tabel = $('#table-artikel-query').DataTable({
        "processing": true,
        "responsive": true,
        "serverSide": true,
        "ordering": true, // Set true agar bisa di sorting
        "order": [
            [0, 'DESC']
        ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
        "ajax": {
            <?php if(isset($_GET['stok'])){?> "url": "<?= $baseURL.'/helper/data.php?aksi=barang&stok=yes';?>", // URL file untuk proses select datanya
            <?php }else{?> "url": "<?= $baseURL.'/helper/data.php?aksi=barang';?>", // URL file untuk proses select datanya
            <?php }?> "type": "POST"
        },
        "deferRender": true,
        "aLengthMenu": [
            [10, 25, 50],
            [10, 25, 50]
        ], // Combobox Limit
        "columns": [{
                "data": 'id',
                "sortable": false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "data": "id_barang"
            },
            {
                "data": "gambar",
                "render": function(data, type, row, meta) {
                    if (row.gambar !== null) {
                        return `<center>
                                        <a href="<?= $baseURL;?>assets/uploads/barang/${row.gambar}" data-toggle="lightbox" 
                                            data-title="${row.nama_barang}" data-gallery="gallery">
                                            <img src="<?= $baseURL;?>assets/uploads/barang/${row.gambar}" 
                                                alt="${row.nama_barang}" class="img-fluid" width="80"/>
                                        </a>
                                    </center>`;
                    } else {
                        return `<center>
                                        <a href="<?= $baseURL;?>assets/dist/img/no-image.jpg" data-toggle="lightbox" 
                                            data-title="${row.nama_barang}" data-gallery="gallery">
                                            <img src="<?= $baseURL;?>assets/dist/img/no-image.jpg" 
                                                alt="${row.nama_barang}" class="img-fluid" width="80"/>
                                        </a>
                                    </center>`;
                    }
                }
            },
            {
                "data": "nama_barang"
            },
            {
                "data": "nama_kategori"
            },
            {
                "data": "merk"
            },
            {
                data: 'harga_jual',
                render: $.fn.dataTable.render.number(',', '.', 0, 'Rp')
            },
            {
                "data": "satuan_barang"
            },
            {
                "data": "stok"
            },
            {
                "data": "id",
                "render": function(data, type, row, meta) {
                    return `<form method="post" action="proses.php?aksi=addbarang">   
                                    <input type="number" value="1"
                                        class="form-control" 
                                        name="qty" id="qty" placeholder="">
                                    <input type="hidden" value="${row.id}"
                                        class="form-control" 
                                        name="id" id="id" placeholder="">
                                    <input type="hidden" value="<?= $edit->no_trx;?>"
                                        class="form-control" 
                                        name="no_trx" id="no_trx" placeholder="">
                                    <button type="submit" 
                                        class="btn btn-success btn-sm mt-2 mb-2 btn-block" title="Tambahkan">
                                        <i class="fa fa-plus mr-1"></i>
                                    </button> 
                                </form>`;
                }
            },
        ],
    });
});
</script>