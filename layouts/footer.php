</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy;<?=date('Y');?> <a href="#">Portal Studio</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0
    </div>
</footer>

<!-- Modal -->
<div class="modal fade" id="modelIdLogout" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="fas fa-power-off mr-2 text-danger"></span> Perhatian!!
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda ingin Keluar dari Aplikasi, dan mengakhiri Sesi ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="<?= $baseURL.'logout.php';?>" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
</div>
</div>
<!-- ./wrapper -->
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= $baseURL;?>assets/plugins/moment/moment.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?= $baseURL;?>assets/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= $baseURL;?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= $baseURL;?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= $baseURL;?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= $baseURL;?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= $baseURL;?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= $baseURL;?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= $baseURL;?>assets/plugins/jszip/jszip.min.js"></script>
<script src="<?= $baseURL;?>assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= $baseURL;?>assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= $baseURL;?>assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= $baseURL;?>assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= $baseURL;?>assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Ekko Lightbox -->
<script src="<?= $baseURL;?>assets/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= $baseURL;?>assets/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= $baseURL;?>assets/dist/js/demo.js"></script>
<!-- Page specific script -->

<script type="text/javascript">
$(document).ready(function() {
    $('#rupiah1').on( "keyup", function() {
        $(this).val(formatRupiah( $(this).val(), ''));
    });
    $('#rupiah2').on( "keyup", function() {
        $(this).val(formatRupiah($(this).val(), ''));
    });
});
/* Fungsi formatRupiah */
function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
}
</script>
<script>
function jam() {
    var e = document.getElementById('jam'),
        d = new Date(),
        h, m, s;
    h = d.getHours();
    m = set(d.getMinutes());
    s = set(d.getSeconds());
    e.innerHTML = h + ':' + m + ':' + s;
    setTimeout('jam()', 1000);
}

function set(e) {
    e = e < 10 ? '0' + e : e;
    return e;
}
</script>
<script>
$(function() {
    //Initialize Select2 Elements
    $('.select2').select2()
});
</script>
<script>
$(function() {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true
        });
    });
})
</script>
<script>
$(function() {
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
        "responsive": true
    });
    $('#example3').DataTable({
        "responsive": true
    });
    $('#example4').DataTable({
        "responsive": true
    });
    $('#example5').DataTable({
        "paging": false,
        "ordering": false,
        "info": false
    });
});
</script>
<script type="application/javascript">
//angka 500 dibawah ini artinya pesan akan muncul dalam 0,5 detik setelah document ready
$(document).ready(function() {
    setTimeout(function() {
        $(".alert-danger").slideUp('slow');
    }, 5000);
})
//angka 3000 dibawah ini artinya pesan akan hilang dalam 3 detik setelah muncul
setTimeout(function() {
    $(".alert-danger").slideUp('slow');
}, 5000);
$(document).ready(function() {
    setTimeout(function() {
        $(".alert-success").slideUp('slow');
    }, 5000);
})
setTimeout(function() {
    $(".alert-success").slideUp('slow');
}, 5000);
$(document).ready(function() {
    setTimeout(function() {
        $(".alert-info").slideUp('slow');
    }, 5000);
})
setTimeout(function() {
    $(".alert-info").slideUp('slow');
}, 5000);
$(document).ready(function() {
    setTimeout(function() {
        $(".alert-error").slideUp('slow');
    }, 5000);
})
setTimeout(function() {
    $(".alert-error").slideUp('slow');
}, 5000);
</script>
</body>

</html>