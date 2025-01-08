<div class="content-wrapper">
    <!-- Page Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo $title_form; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('Welcome'); ?>"><i class="fa fa-home"></i> Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('Welcome'); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active"><?php echo $title_form; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo $title_form; ?> Form</h3>
            </div>
            <form class="form theme-form" id="frm_index">
                <div class="mb-2 row">
                    <!-- <div class="col-sm-9">
                        <button id="btn_input" class="btn btn-primary" type="button"
                            title="Tambah Data"><i class="fa fa-plus-square">
                                Tambah Data </i></button>
                    </div> -->
                    <div class="mb-2 row">
                        <div class="col-sm-3">
                            <label for="from_date">From Date:</label>
                            <input type="date" id="from_date" class="form-control" placeholder="YYYY-MM-DD">
                        </div>
                        <div class="col-sm-3">
                            <label for="to_date">To Date:</label>
                            <input type="date" id="to_date" class="form-control" placeholder="YYYY-MM-DD">
                        </div>
                        <div class="col-sm-3">
                            <label for="to_date"></label>
                            <button id="filter_btn" class="btn btn-primary mt-4 form-control" type="button">Filter</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card-body">
                <!-- DataTable -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="datatable_list">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                                <th>Kasir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTable akan memuat data di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    var table;
    $(document).ready(function(e) {
        table = $('#datatable_list').DataTable({
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "pagingType": "full_numbers",
            "oLanguage": {
                "sProcessing": '<center><img alt src="<?php echo base_url('assets/images/loading/loading-4.gif'); ?>" style="opacity: 1.0;filter: alpha(opacity=100);"></center>'
            },
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "searching": true,
            "autoWidth": true,
            // "scrollY": 455,
            "scrollX": true,
            "order": [], //Initial no order.
            "ajax": {
                "url": "<?php echo site_url('C_report/ajax_list'); ?>",
                "type": "POST",
                "data": function(data) {
                    $('#loader').hide();
                    data.from_date = $('#from_date').val();
                    data.to_date = $('#to_date').val();
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [{
                    "targets": [-1], //last column
                    "orderable": true, //set not orderable
                },
                {
                    "targets": [-2], //2 last column (photo)
                    "orderable": true, //set not orderable
                },
            ],
            "columns": [{
                    "width": "5%",
                }, // Number column
                {
                    "width": "15%",
                }, // Tanggal column
                {
                    "width": "10%",
                }, // Kode Barang column
                {
                    "width": "25%",
                }, // Nama Barang column
                {
                    "width": "10%",
                }, // Jumlah column
                {
                    "width": "15%",
                }, // Harga column
                {
                    "width": "15%",
                }, // Subtotal column
                {
                    "width": "10%",
                } // Kasir column
            ]

        });
        table.columns.adjust().draw();
    });

    $('#btn_input').click(function(e) {
        e.preventDefault();
        $('#frmInput').modal('show');
        $('#title_input_modal').text('Tambah Data');
    });

    $('#filter_btn').click(function() { //button filter event click
        table.ajax.reload(); //just reload table
        scrollWin();
    });

    function edit_barang(kode_barang) {
        $.ajax({
            url: '<?php echo site_url('C_barang/ajax_edit'); ?>/' + kode_barang,
            type: 'get',
            dataType: 'json',
            success: function(data) {
                $('#frmEdit').modal('show');
                $('#title_edit_modal').text('Edit Data Barang');
                $('#txt_edit_id_bar').val(data.id);
                $('#txt_edit_kode_bar_old').val(data.kode_barang);
                $('#txt_edit_kode_bar').val(data.kode_barang);
                $('#txt_edit_nama_bar').val(data.nama_barang);
                $('#txt_edit_harga').val(data.harga);
            }
        });
    }

    function delete_barang(id) {
        var data_id = id;
        var urls = '<?php echo site_url("C_barang/delete_permanen/"); ?>';
        swal.fire({
            title: "Apakah Kamu Yakin?",
            text: "Apakah Kamu Yakin Hapus Data ini?",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Batal",
                    value: null,
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true
                },
                confirm: {
                    text: "Hapus",
                    value: true,
                    visible: true,
                    className: "btn btn-success",
                    closeModal: false
                }
            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'POST',
                    url: urls + data_id,
                    dataType: "JSON",
                    success: function(data) {
                        if (data.is_error === true) {
                            swal.fire({
                                title: "Oopps",
                                text: data.error_message,
                                icon: "error",
                                timer: 2000,
                                buttons: false,
                            });
                        } else {
                            swal.fire({
                                title: "Success",
                                text: "Data Berhasil Dihapus secara permanen.",
                                icon: "success",
                                timer: 1500,
                                buttons: false,
                            });
                        }
                        // Reload DataTable
                        table.ajax.reload(null, false); // Tetap di halaman yang sama
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal.fire({
                            title: "Gagal",
                            text: "Terjadi kesalahan saat menghapus data.",
                            icon: "error",
                            timer: 2000,
                            buttons: false,
                        });
                        console.error("AJAX Error: ", textStatus, errorThrown);
                    }
                });
            } else {
                swal.fire({
                    title: "Dibatalkan",
                    text: "Data Kamu masih Aman!",
                    icon: "info",
                    timer: 1500,
                    buttons: false,
                });
            }
        });
    }
</script>