<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Transaksi
            <small>Aplikasi Kasir 1.0</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Masukan kode barang</label>
                                    <?php
                                    $this->db->select('*');
                                    $this->db->from('db_appkasir.tb_barang');
                                    $this->db->order_by('id', 'desc');
                                    $barang = $this->db->get()->result_array();
                                    ?>
                                    <select class="form-control" id="product_code">
                                        <option value="">Pilih Barang</option>
                                        <?php foreach ($barang as $b) : ?>
                                            <option value="<?= $b['kode_barang']; ?>"><?= $b['kode_barang']; ?> - <?= $b['nama_barang']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Masukan jumlah barang</label>
                                    <input type="number" class="form-control" id="quantity" min="1">
                                </div>
                                <button class="btn btn-primary btn-block" id="add_to_cart">
                                    Tambah Ke Keranjang
                                </button>
                            </div>

                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Jumlah</th>
                                                <th>Harga</th>
                                                <th>Subtotal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cart_items">
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Total:</label>
                                            <input type="text" class="form-control" id="total" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Jumlah Bayar:</label>
                                            <input type="number" class="form-control" id="payment_amount">
                                        </div>
                                        <div class="form-group">
                                            <label>Kembali:</label>
                                            <input type="text" class="form-control" id="change_amount" readonly>
                                        </div>
                                        <button class="btn btn-success btn-block" id="complete_transaction">
                                            Selesai & Cetak Struk
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        let cart = [];

        $('#add_to_cart').click(function() {
            const code = $('#product_code').val();
            const quantity = parseInt($('#quantity').val());

            if (!code || !quantity) {
                alert('Mohon isi kode barang dan jumlah');
                return;
            }

            $.ajax({
                url: '<?php echo base_url("C_transaksi/get_product"); ?>/' + code,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    addItemToCart(response, quantity);
                    updateCartDisplay();
                    clearInputs();
                }
            });
        });

        $('#payment_amount').on('input', function() {
            const total = parseFloat($('#total').val()) || 0;
            const payment = parseFloat($(this).val()) || 0;
            $('#change_amount').val((payment - total).toFixed(2));
        });

        // Update the complete_transaction click handler
        $('#complete_transaction').click(function() {
            const total = parseFloat($('#total').val()) || 0;
            const payment = parseFloat($('#payment_amount').val()) || 0;

            if (payment < total) {
                alert('Jumlah pembayaran kurang');
                return;
            }

            // Ajax call to save transaction and generate PDF
            $.ajax({
                url: '<?php echo base_url("C_transaksi/save_and_print"); ?>',
                method: 'POST',
                data: {
                    cart: JSON.stringify(cart),
                    total: total,
                    payment: payment,
                    change: payment - total
                },
                success: function(response) {
                    const resp = JSON.parse(response);
                    if (resp.status === 'success') {
                        // Open PDF in new tab
                        window.open('<?php echo base_url("c_transaksi/generate_receipt_pdf/"); ?>' + resp.id_transaksi, '_blank');
                        resetTransaction();
                    } else {
                        alert('Gagal menyimpan transaksi: ' + resp.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        });

        function addItemToCart(product, quantity) {
            const existingItem = cart.find(item => item.code === product.code);

            if (existingItem) {
                existingItem.quantity += quantity;
                existingItem.subtotal = existingItem.quantity * existingItem.price;
            } else {
                cart.push({
                    code: product.kode_barang,
                    name: product.nama_barang,
                    quantity: quantity,
                    price: product.harga,
                    subtotal: quantity * product.harga
                });
            }
        }

        function updateCartDisplay() {
            let total = 0;
            $('#cart_items').empty();

            cart.forEach((item, index) => {
                $('#cart_items').append(`
                <tr>
                    <td>${item.code}</td>
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>${item.price}</td>
                    <td>${item.subtotal}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="removeItem(${index})">
                            Hapus
                        </button>
                    </td>
                </tr>
            `);
                total += item.subtotal;
            });

            $('#total').val(total.toFixed(2));
            $('#change_amount').val('');
            $('#payment_amount').val('');
        }

        function clearInputs() {
            $('#product_code').val('');
            $('#quantity').val('');
        }

        function resetTransaction() {
            cart = [];
            updateCartDisplay();
            clearInputs();
        }

        window.removeItem = function(index) {
            cart.splice(index, 1);
            updateCartDisplay();
        };
    });
</script>