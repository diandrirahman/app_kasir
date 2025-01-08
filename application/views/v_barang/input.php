<div class="modal fade" id="frmInput" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" id="inputFrm">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="title_input_modal"><i class="fa fa-edit"></i> Title</h5>
                    <!-- <button class="btn-close btn-white" type="button" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="txt_input_kode_bar">Kode Barang</label>
                            <input class="form-control" id="txt_input_kode_bar" name="txt_input_kode_bar" type="text" placeholder="Kode Barang">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="txt_input_nama_bar">Nama Barang</label>
                            <input class="form-control" id="txt_input_nama_bar" name="txt_input_nama_bar" type="text" placeholder="Nama Barang">
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="txt_input_harga">Harga</label>
                            <input class="form-control" id="txt_input_harga" name="txt_input_harga" type="number" placeholder="Harga">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal" id="btn_close_input_modal"><i class="fa fa-times-circle"></i> Close</button>
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#btn_close_input_modal').click(function() {
        $('#inputFrm')[0].reset();
        $('#frmInput').modal('hide');
    });

    $('#inputFrm').submit(function(e) {
        e.preventDefault(); // Mencegah reload halaman
        var urls = "<?php echo site_url('C_barang/input_proses') ?>";
        var data = new FormData($('#inputFrm')[0]);

        $.ajax({
            url: urls,
            type: 'POST',
            data: data,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                var out = jQuery.parseJSON(data);
                if (out.is_error == true) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning!',
                        text: out['error_message'],
                        showConfirmButton: false,
                        timer: 2500
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: out['succes_message'],
                        showConfirmButton: false,
                        timer: 2100
                    });

                    // Reload datatable
                    table.ajax.reload();

                    // Reset form dan tutup modal
                    document.getElementById("inputFrm").reset();
                    $('#frmInput').modal('hide');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    showConfirmButton: false,
                    timer: 2100
                });
            }
        });
    });
</script>