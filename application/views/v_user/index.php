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
                    <div class="col-sm-9">
                        <button id="btn_input" class="btn btn-primary" type="button"
                            title="Tambah Data"><i class="fa fa-plus-square">
                                Tambah Data </i></button>
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
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Akses</th>
                                <th>Aksi</th>
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
<?php $this->load->view('v_user/input'); ?>
<?php $this->load->view('v_user/edit'); ?>
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
                "url": "<?php echo site_url('C_user/ajax_list'); ?>",
                "type": "POST",
                "data": function(data) {
                    $('#loader').hide();
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
                    "width": "5%"
                }, // Number column
                {
                    "width": "20%"
                }, // Kode Barang column
                {
                    "width": "30%"
                }, // Nama Barang column
                {
                    "width": "15%"
                }, // Harga column
                {
                    "width": "10%"
                } // Aksi column
            ]

        });
        table.columns.adjust().draw();
    });

    $('#btn_input').click(function(e) {
        e.preventDefault();
        $('#frmInput').modal('show');
        $('#title_input_modal').text('Tambah Data');
    });

    function edit_user(id) {
        $.ajax({
            url: '<?php echo site_url('C_user/ajax_edit'); ?>/' + id,
            type: 'get',
            dataType: 'json',
            success: function(data) {
                $('#frmEdit').modal('show');
                $('#title_edit_modal').text('Edit Data User');
                $('#txt_edit_id').val(data.id);
                $('#txt_edit_username').val(data.username);
                $('#txt_edit_username_old').val(data.username);
                $('#txt_edit_nama').val(data.nama);
                level = data.id_level;
                if (level == 1) {
                    $('#edit_akses_admin').prop('checked', true);
                } else {
                    $('#edit_akses_kasir').prop('checked', true);
                }
            }
        });
    }

    function delete_user(id) {
        var data_id = id;
        var urls = '<?php echo site_url("C_user/delete_permanen/"); ?>';
        swal.fire({
            title: "Apakah Kamu Yakin?",
            text: "Apakah Kamu Yakin Hapus Data ini?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonText: "Hapus",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
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