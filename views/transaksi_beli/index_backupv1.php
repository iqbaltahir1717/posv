<!-- Page content -->
<div class="row">
    <div class="col-sm-12">
        <h4><i class="fa fa-shopping-cart mr-2"></i> Order Barang</h4>
        <br>
        <?php if (!empty(flashdata())) {
            echo flashdata();
        }?>
        <div class="row">
            <div class="col-sm-12">
                <div class="card mb-3">
                    <!-- Card header -->
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fa fa-search mr-1"></i>
                            Cari Barang
                        </h5>
                    </div>
                    <div class="card-body pb-0">
                        <div class="form-group">
                            <input type="text" autofocus name="cari_barang" id="cari_barang" class="form-control"
                                placeholder="Masukan Kode / Nama Barang [ENTER]">
                        </div>
                        <div id="hasil_cari"></div>
                        <div id="tunggu"></div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="float-right">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary btn-md mt-1 mb-2" data-toggle="modal"
                                data-target="#modelId">
                                <i class="fa fa-search mr-1"></i> Daftar Barang
                            </button>
                            <button type="button" class="btn btn-danger btn-md mt-1 mb-2" data-toggle="modal"
                                data-target="#modelIdReset">
                                <i class="fa fa-trash mr-1"></i> Reset Keranjang
                            </button>
                        </div>
                        <h5 class="mt-2"><i class="fa fa-shopping-cart mr-1"></i> Keranjang</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive pt-3">
                            <table class="table table-striped table-sm" id="example5">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Barang</th>
                                        <th>Gambar</th>
                                        <th>Barang</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $no =1;
                                        $sql = "SELECT barang.id_barang as idb, barang.gambar, keranjang_beli.* 
                                                FROM keranjang_beli  
                                                LEFT JOIN barang 
                                                ON keranjang_beli.id_barang=barang.id 
                                                WHERE id_member = ?
                                                ORDER BY keranjang_beli.id ASC";
                                        $row = $connectdb->prepare($sql);
                                        $row->execute(array($users->id));
                                        $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                                        $grantotal = 0;
                                        foreach ($hasil as $r) {
                                    ?>
                                    <tr>
                                        <td><?= $no; ?></td>
                                        <td><?=$r->idb; ?></td>
                                        <td>
                                            <a href="<?= url_images($baseURL, 'barang', $r->gambar); ?>"
                                                data-toggle="lightbox" data-title="<?=$r->nama_barang; ?>"
                                                data-gallery="gallery">
                                                <img src="<?= url_images($baseURL, 'barang/', $r->gambar); ?>"
                                                    alt="<?=$r->nama_barang; ?>" class="img-fluid" width="80" />
                                            </a>
                                        </td>
                                        <td><?=$r->nama_barang; ?></td>
                                        <form method="post" action="proses.php?aksi=editkeranjang">
                                            <td>
                                                <input type="number" class="form-control" name="beli"
                                                    style="width:150px !important;" value="<?=$r->beli; ?>">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="jual"
                                                    style="width:150px !important;" value="<?=$r->jual; ?>">
                                            </td>
                                            <td>
                                                <input type="number" style="width:80px !important;" min="1"
                                                    class="form-control" name="qty" value="<?=$r->jumlah; ?>">
                                            </td>
                                            <td>Rp<?= number_format(($r->beli * $r->jumlah) ?? 0);?>,-</td>
                                            <td>
                                                <input type="hidden" value="<?=$r->id; ?>" class="form-control"
                                                    name="id" id="id">
                                                <input type="hidden" value="<?=$r->id_barang; ?>" class="form-control"
                                                    name="id_barang" id="id_barang">
                                                <div class="btn-group">
                                                    <button type="submit"
                                                        class="btn btn-success btn-sm"
                                                        title="Edit Keranjang">
                                                        <i class="fa fa-edit"></i>Edit Qty
                                                    </button>
                                                    <a href="<?= "proses.php?aksi=delete&id=".$r->id;?>"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="javascript:return confirm(`Data keranjang ingin dihapus ?`);"
                                                        title="Delete">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </form>
                                    </tr>
                                    <?php $no++; $grantotal += ($r->beli * $r->jumlah); }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <form method="post" action="proses.php?aksi=tambah">
                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6 text-right">
                                    <div class="row">
                                        <?php
                                            $sql = "SELECT * FROM pembelian ORDER BY id DESC LIMIT 1";
                                            $row = $connectdb->prepare($sql);
                                            $row->execute();
                                            $edit = $row->fetch(PDO::FETCH_OBJ);
                                            if (isset($edit->id)) {
                                            $cekid = $edit->id + 1;
                                            } else {
                                            $cekid = 1;
                                            }
                                            $kode = 'OR00'.$cekid;
                                        ?>
                                        <div class="col-sm-6 mb-3">
                                            <h5 class="text-right pt-1">No Transaksi</h5>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <input type="text" autocomplete="off" class="form-control" required
                                                name="notrx" value="<?= $kode;?>">
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <h5 class="text-right pt-1">Grand Total</h5>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <h5>Rp<?=number_format($grantotal);?>,-</h5>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <h5 class="text-right pt-1">Supplier</h5>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="form-group">
                                                <select class="form-control" required name="supplier" id="supplier">
                                                    <option value="">- pilih -</option>
                                                    <?php
                                                        $no =1;
                                                        $sql = "SELECT * FROM supplier ORDER BY id DESC";
                                                        $row = $connectdb->prepare($sql);
                                                        $row->execute();
                                                        $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                                                        foreach ($hasil as $r) {
                                                    ?>
                                                    <option><?= $r->nama_supplier; ?></option>
                                                    <?php  }?>
                                                </select>
                                            </div>
                                            <input type="hidden" autocomplete="off" class="form-control" name="total"
                                                value="<?= $grantotal;?>">
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <!-- <p class="text-right">Aksi</p>   -->
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <button type="submit" name="" id=""
                                                class="btn btn-primary btn-lg btn-block">
                                                <i class="fa fa-save mr-1"></i> Order Barang
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
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
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">Semua</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                            aria-controls="profile" aria-selected="false">Limit</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="table-responsive mt-4">
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
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="table-responsive mt-4">
                            <table class="table table-striped table-sm table-bordered w-100"
                                id="table-artikel-query-limit">
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal  Reset -->
<div class="modal fade" id="modelIdReset" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-shopping-cart mr-1"></i> Reset Keranjang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda ingin mereset keranjang ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="proses.php?aksi=reset" class="btn btn-danger">Reset</a>
            </div>
        </div>
    </div>
</div>

<script>
// AJAX call for autocomplete 
$(document).ready(function() {
    $("#cari_barang").change(function() {
        $.ajax({
            type: "POST",
            url: "<?= $baseURL.'helper/cari.php?sortir=beli';?>",
            data: 'keyword=' + $(this).val(),
            beforeSend: function() {
                $("#hasil_cari").hide();
                $("#tunggu").html(
                    '<p style="color:green"><blink><i class="fa fa-sync fa-spin mr-1"></i> tunggu sebentar</blink></p>'
                );
            },
            success: function(html) {
                $("#tunggu").html('');
                $("#hasil_cari").show();
                $("#hasil_cari").html(html);
            }
        });
    });
});
</script>
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
            "url": "<?= $baseURL.'/helper/data.php?aksi=barang';?>", // URL file untuk proses select datanya
            "type": "POST"
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
                data: 'harga_beli',
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
                    return `<form method="post" action="proses.php?aksi=keranjang">   
                                    <input type="number" value="1"
                                        class="form-control" 
                                        name="qty" id="qty" placeholder="">
                                    <input type="hidden" value="${row.id}"
                                        class="form-control" 
                                        name="id" id="id" placeholder="">
                                    <button type="submit" 
                                        class="btn btn-success btn-sm mt-2 mb-2 btn-block" title="Tambahkan ke keranjang">
                                        <i class="fa fa-shopping-cart mr-1"></i>
                                    </button> 
                                </form>`;
                }
            },
        ],
    });
});
</script>

<script>
var tabel1 = null;
$(document).ready(function() {
    tabel1 = $('#table-artikel-query-limit').DataTable({
        "processing": true,
        "responsive": true,
        "serverSide": true,
        "ordering": true, // Set true agar bisa di sorting
        "order": [
            [0, 'DESC']
        ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
        "ajax": {
            "url": "<?= $baseURL.'/helper/data.php?aksi=barang&stok=yes';?>", // URL file untuk proses select datanya
            "type": "POST"
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
                data: 'harga_beli',
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
                    return `<form method="post" action="proses.php?aksi=keranjang">   
                                    <input type="number" value="1"
                                        class="form-control" 
                                        name="qty" id="qty" placeholder="">
                                    <input type="hidden" value="${row.id}"
                                        class="form-control" 
                                        name="id" id="id" placeholder="">
                                    <button type="submit" 
                                        class="btn btn-success btn-sm mt-2 mb-2 btn-block" title="Tambahkan ke keranjang">
                                        <i class="fa fa-shopping-cart mr-1"></i>
                                    </button> 
                                </form>`;
                }
            },
        ],
    });
});
</script>