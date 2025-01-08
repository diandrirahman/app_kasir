<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_transaksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_transaksi');
        $this->load->model('M_barang');
    }

    public function index()
    {
        $data = [
            'title_form' => 'Transaksi',
        ];
        $this->load->view('includes/header');
        $this->load->view('v_transaksi/index', $data);
        $this->load->view('includes/footer');
    }

    public function get_product($code)
    {
        $product = $this->M_barang->get_by_id($code);
        echo json_encode($product);
    }

    public function save_and_print()
    {
        // Generate transaction ID (format: TRX-YmdHis)
        $id_transaksi = 'TRX-' . date('YmdHis');

        // Get POST data
        $cart = json_decode($this->input->post('cart'), true);
        $total = $this->input->post('total');
        $bayar = $this->input->post('payment');
        $kembali = $this->input->post('change');

        // Prepare transaction data
        $transaction_data = [
            'id_transaksi' => $id_transaksi,
            'tanggal' => date('Y-m-d H:i:s'),
            'total' => $total,
            'bayar' => $bayar,
            'kembali' => $kembali,
            'kasir' => $this->session->userdata('nama')
        ];

        // Prepare items data
        $items_data = [];
        foreach ($cart as $item) {
            $items_data[] = [
                'id_transaksi' => $id_transaksi,
                'kode_barang' => $item['code'],
                'nama_barang' => $item['name'],
                'harga' => $item['price'],
                'jumlah' => $item['quantity'],
                'subtotal' => $item['subtotal'],
            ];
        }

        // Save to database
        $saved = $this->M_transaksi->save_transaction($transaction_data, $items_data);

        if ($saved) {
            echo json_encode([
                'status' => 'success',
                'id_transaksi' => $id_transaksi,
                'message' => 'Transaksi berhasil disimpan'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menyimpan transaksi'
            ]);
        }
    }

    public function generate_receipt_pdf($id_transaksi)
    {
        // Load TCPDF library
        require_once FCPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';

        // Get transaction data
        $data = $this->M_transaksi->get_transaction($id_transaksi);

        // Prepare data for view
        $view_data = [
            'id_transaksi' => $id_transaksi,
            'tanggal' => $data['transaction']->tanggal,
            'kasir' => $data['transaction']->kasir,
            'details' => $data['details'],
            'total' => $data['transaction']->total,
            'bayar' => $data['transaction']->bayar,
            'kembali' => $data['transaction']->kembali
        ];

        // Create a new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(80, 200), true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('Aplikasi Kasir 1.0');
        $pdf->SetTitle('Struk Pembelian');

        // Remove header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(10, 10, 10);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 8);

        // Load HTML content from the view
        $html = $this->load->view('v_transaksi/receipt_view', $view_data, true);  // Render view as string

        // Print content
        $pdf->writeHTML($html, true, false, true, false, '');

        // Close and output PDF
        $pdf->Output('struk_' . $id_transaksi . '.pdf', 'I');  // Output PDF to browser
    }
}
