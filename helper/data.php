<?php

session_start();
include '../setting.php';
include '../helper.php';
if(!empty($_SESSION['codekop_session'])) {
    $uid =  (int)$_SESSION['codekop_session']['id'];
    $sql_users = "SELECT * FROM users WHERE id = ?";
    $row_users = $connectdb->prepare($sql_users);
    $row_users->execute(array($uid));
    $users = $row_users->fetch(PDO::FETCH_OBJ);
} else {
    redirect($baseURL.'login.php');
}


//all-categories
if(!empty(getGet('aksi') == 'barang')) {
    $query  = "SELECT barang_kategori.nama_kategori, barang.* 
                FROM barang 
                LEFT JOIN barang_kategori ON barang.id_kategori=barang_kategori.id";
    $search = array('nama_kategori','nama_barang','id_barang','merk','satuan_barang','harga_beli','harga_jual','stok');
    $where = null;
    if(!empty(getGet('stok', true))) {
        $isWhere = " stok <= 5 ";
    } else {
        $isWhere = null;
    }
    echo get_tables_query($connectdb, $query, $search, $where, $isWhere);
}

if(!empty(getGet('aksi') == 'barang_1')) {
    $query  = "SELECT barang_kategori.nama_kategori, barang.* 
                FROM barang 
                LEFT JOIN barang_kategori ON barang.id_kategori=barang_kategori.id";
    $search = array('nama_kategori','nama_barang','id_barang','merk','satuan_barang','harga_beli','harga_jual','stok');
    $where = array('barang.id_kategori'=>1);
    if(!empty(getGet('stok', true))) {
        $isWhere = " stok <= 5 ";
    } else {
        $isWhere = null;
    }
    echo get_tables_query($connectdb, $query, $search, $where, $isWhere);
}

if(!empty(getGet('aksi') == 'barang_2')) {
    $query  = "SELECT barang_kategori.nama_kategori, barang.* 
                FROM barang 
                LEFT JOIN barang_kategori ON barang.id_kategori=barang_kategori.id";
    $search = array('nama_kategori','nama_barang','id_barang','merk','satuan_barang','harga_beli','harga_jual','stok');
    $where = array('barang.id_kategori'=>2);
    if(!empty(getGet('stok', true))) {
        $isWhere = " stok <= 5 ";
    } else {
        $isWhere = null;
    }
    echo get_tables_query($connectdb, $query, $search, $where, $isWhere);
}

if(!empty(getGet('aksi') == 'barang_3')) {
    $query  = "SELECT barang_kategori.nama_kategori, barang.* 
                FROM barang 
                LEFT JOIN barang_kategori ON barang.id_kategori=barang_kategori.id";
    $search = array('nama_kategori','nama_barang','id_barang','merk','satuan_barang','harga_beli','harga_jual','stok');
    $where = array('barang.id_kategori'=>3);
    if(!empty(getGet('stok', true))) {
        $isWhere = " stok <= 5 ";
    } else {
        $isWhere = null;
    }
    echo get_tables_query($connectdb, $query, $search, $where, $isWhere);
}

if(!empty(getGet('aksi') == 'barang_4')) {
    $query  = "SELECT barang_kategori.nama_kategori, barang.* 
                FROM barang 
                LEFT JOIN barang_kategori ON barang.id_kategori=barang_kategori.id";
    $search = array('nama_kategori','nama_barang','id_barang','merk','satuan_barang','harga_beli','harga_jual','stok');
    $where = array('barang.id_kategori'=>4);
    if(!empty(getGet('stok', true))) {
        $isWhere = " stok <= 5 ";
    } else {
        $isWhere = null;
    }
    echo get_tables_query($connectdb, $query, $search, $where, $isWhere);
}

if(!empty(getGet('aksi') == 'barang_5')) {
    $query  = "SELECT barang_kategori.nama_kategori, barang.* 
                FROM barang 
                LEFT JOIN barang_kategori ON barang.id_kategori=barang_kategori.id";
    $search = array('nama_kategori','nama_barang','id_barang','merk','satuan_barang','harga_beli','harga_jual','stok');
    $where = array('barang.id_kategori'=>5);
    if(!empty(getGet('stok', true))) {
        $isWhere = " stok <= 5 ";
    } else {
        $isWhere = null;
    }
    echo get_tables_query($connectdb, $query, $search, $where, $isWhere);
}

if(!empty(getGet('aksi') == 'barang_6')) {
    $query  = "SELECT barang_kategori.nama_kategori, barang.* 
                FROM barang 
                LEFT JOIN barang_kategori ON barang.id_kategori=barang_kategori.id";
    $search = array('nama_kategori','nama_barang','id_barang','merk','satuan_barang','harga_beli','harga_jual','stok');
    $where = array('barang.id_kategori'=>6);
    if(!empty(getGet('stok', true))) {
        $isWhere = " stok <= 5 ";
    } else {
        $isWhere = null;
    }
    echo get_tables_query($connectdb, $query, $search, $where, $isWhere);
}

if(!empty(getGet('aksi') == 'barang_7')) {
    $query  = "SELECT barang_kategori.nama_kategori, barang.* 
                FROM barang 
                LEFT JOIN barang_kategori ON barang.id_kategori=barang_kategori.id";
    $search = array('nama_kategori','nama_barang','id_barang','merk','satuan_barang','harga_beli','harga_jual','stok');
    $where = array('barang.id_kategori'=>7);
    if(!empty(getGet('stok', true))) {
        $isWhere = " stok <= 5 ";
    } else {
        $isWhere = null;
    }
    echo get_tables_query($connectdb, $query, $search, $where, $isWhere);
}


if(!empty(getGet('aksi') == 'nota-jual')) {
    $query = "SELECT users.name, pelanggan.nama_pelanggan, penjualan.* 
                FROM penjualan 
                LEFT JOIN users 
                ON penjualan.id_member = users.id 
                LEFT JOIN pelanggan 
                ON penjualan.id_pelanggan=pelanggan.id";
    $search = array('no_trx','name','nama_pelanggan');
    if(!empty(getGet('id_pelanggan', true))) {
        $where  = array('id_pelanggan' => getGet('id_pelanggan'));
    } else {
        $where  = null;
    }
    if(!empty(getGet('thn', true))) {
        $periode = getGet('thn', true).'-'.getGet('bln', true);
        $isWhere = " penjualan.periode = '".$periode."' ";
    } elseif(!empty(getGet('hari', true))) {
        $tgla = getGet('tgla', true);
        $tglb = getGet('tglb', true);
        $isWhere = " penjualan.tanggal_input BETWEEN '$tgla' AND '$tglb' ";
    } else {
        $isWhere = " penjualan.periode = '".date('Y-m')."' ";
    }
    if($_SESSION['codekop_session']['akses']== 5) {
        $isWhere .= " AND penjualan.id_member = ".$_SESSION['codekop_session']['id'];
    }


    echo get_tables_query($connectdb, $query, $search, $where, $isWhere);
}

// if(!empty(getGet('aksi') == 'nota-jual-produk')){
//     $query = "SELECT * FROM penjualan_detail";
//     $search = array('no_trx','nama_barang');
//     $where  = null;
//     if(!empty(getGet('thn'])){
//         $periode = getGet('thn',true).'-'.getGet('bln',true);
//         $isWhere = " penjualan_detail.periode = '".$periode."' ";
//     }elseif(!empty(getGet('hari',true))){
//         $tgla = getGet('tgla',true);
//         $tglb = getGet('tglb',true);
//         $isWhere = " penjualan_detail.tgl_input BETWEEN '$tgla' AND '$tglb' ";
//     }else{
//         $isWhere = " penjualan_detail.periode = '".date('Y-m')."' ";
//     }
//     echo get_tables_query($connectdb,$query,$search,$where,$isWhere);
// }

if(!empty(getGet('aksi') == 'nota-jual-produk')) {
    $query = "SELECT barang_kategori.nama_kategori, 
                barang.id_kategori, 
                penjualan_detail.* 
                FROM penjualan_detail LEFT JOIN barang ON penjualan_detail.id_barang=barang.id 
                LEFT JOIN barang_kategori ON barang.id_kategori=barang_kategori.id";
    $search = array('no_trx','penjualan_detail.nama_barang');

    if(!empty(getGet('kategori', true))) {
        if(getGet('kategori', true) !== 'All') {
            $where  = array('barang.id_kategori' => getGet('kategori', true));
        } else {
            $where  = null;
        }
    } else {
        $where  = null;
    }

    if(!empty(getGet('thn', true))) {
        $periode = getGet('thn', true).'-'.getGet('bln', true);
        $isWhere = " penjualan_detail.periode = '".$periode."' ";
    } elseif(!empty(getGet('hari', true))) {
        $tgla = getGet('tgla');
        $tglb = getGet('tglb');
        $isWhere = " penjualan_detail.tgl_input BETWEEN '$tgla' AND '$tglb' ";
    } else {
        $isWhere = " penjualan_detail.periode = '".date('Y-m')."' ";
    }
    echo get_tables_query($connectdb, $query, $search, $where, $isWhere);
}

if(!empty(getGet('aksi') == 'nota-beli')) {
    $query = "SELECT users.name, pembelian.* 
                FROM pembelian 
                LEFT JOIN users 
                ON pembelian.id_member = users.id";
    $search = array('no_trx','name');
    $where  = null;
    if(!empty(getGet('thn', true))) {
        $periode = getGet('thn', true).'-'.getGet('bln', true);
        $isWhere = " pembelian.periode = '".$periode."' ";
    } elseif(!empty(getGet('hari', true))) {
        $tgla = getGet('tgla');
        $tglb = getGet('tglb');
        $isWhere = " pembelian.tanggal_input BETWEEN '$tgla' AND '$tglb' ";
    } else {
        $isWhere = " pembelian.periode = '".date('Y-m')."' ";
    }
    echo get_tables_query($connectdb, $query, $search, $where, $isWhere);
}

if(!empty(getGet('aksi') == 'nota-beli-produk')) {
    $query = "SELECT * FROM pembelian_detail";
    $search = array('no_trx','nama_barang');
    $where  = null;
    if(!empty(getGet('thn', true))) {
        $periode = getGet('thn', true).'-'.getGet('bln', true);
        $isWhere = " pembelian_detail.periode = '".$periode."' ";
    } elseif(!empty(getGet('hari', true))) {
        $tgla = getGet('tgla');
        $tglb = getGet('tglb');
        $isWhere = " pembelian_detail.tgl_input BETWEEN '$tgla' AND '$tglb' ";
    } else {
        $isWhere = " pembelian_detail.periode = '".date('Y-m')."' ";
    }
    echo get_tables_query($connectdb, $query, $search, $where, $isWhere);
}

if(!empty(getGet('aksi') == 'stok')) {
    $query = "SELECT barang_kategori.nama_kategori, barang.* 
                FROM barang 
                LEFT JOIN barang_kategori ON barang.id_kategori=barang_kategori.id";
    $search = array('nama_kategori','nama_barang','id_barang');
    $where = null;
    $isWhere = null;
    echo get_tables_query($connectdb, $query, $search, $where, $isWhere);
}

if(!empty(getGet('aksi') == 'stok_masuk')) {
    $query = "SELECT SUM(qty) AS qty FROM pembelian_detail";
    if(!empty(getGet('thn', true))) {
        $periode = getGet('thn', true).'-'.getGet('bln', true);
        $isWhere = " pembelian_detail.periode = '".$periode."' ";
    } else {
        $isWhere = " pembelian_detail.periode = '".date('Y-m')."' ";
    }

    $sql = $query.' WHERE '.$isWhere.' AND idb = ?';
    $row = $connectdb->prepare($sql);
    $row->execute(array(getPost('id_barang')));
    $qty = $row->fetch();
    $qt = $qty['qty'] ?? 0;
    echo json_encode(['qty' => $qt]);
}

if(!empty(getGet('aksi') == 'stok_keluar')) {
    $query = "SELECT SUM(qty) AS qty FROM penjualan_detail";
    if(!empty(getGet('thn', true))) {
        $periode = getGet('thn', true).'-'.getGet('bln', true);
        $isWhere = " penjualan_detail.periode = '".$periode."' ";
    } else {
        $isWhere = " penjualan_detail.periode = '".date('Y-m')."' ";
    }

    $sql = $query.' WHERE '.$isWhere.' AND idb = ?';
    $row = $connectdb->prepare($sql);
    $row->execute(array(getPost('id_barang')));
    $qty = $row->fetch();
    $qt = $qty['qty'] ?? 0;
    echo json_encode(['qty' => $qt]);
}
