<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= e($order['order_number']) ?> - Pande Computers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #1e293b; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #e2e8f0; background: #fff; }
        @media print {
            .no-print { display: none !important; }
            .invoice-box { border: none; padding: 0; }
        }
    </style>
</head>
<body class="bg-light py-4">
    <div class="text-center mb-3 no-print">
        <button onclick="window.print()" class="btn btn-primary fw-bold me-2">Print Invoice</button>
        <button onclick="window.close()" class="btn btn-outline-secondary">Close Window</button>
    </div>

    <div class="invoice-box shadow-sm rounded-3">
        <!-- Header -->
        <div class="row align-items-center border-bottom pb-4 mb-4">
            <div class="col-6">
                <h3 class="fw-black text-dark mb-0 tracking-wide">PANDE COMPUTERS</h3>
                <div class="text-primary small fw-bold tracking-wider">YOUR TRUSTED TECH PARTNER</div>
                <div class="small text-muted mt-1">
                    Ndanda & Aggrey Street, Kariakoo<br>
                    Dar es Salaam, Tanzania<br>
                    Tel / WhatsApp: +255 787 311 342<br>
                    sales@pandecomputers.co.tz
                </div>
            </div>
            <div class="col-6 text-end">
                <h2 class="fw-bold text-uppercase text-secondary mb-1">INVOICE</h2>
                <div class="fw-black fs-5 text-primary font-monospace"><?= e($order['order_number']) ?></div>
                <div class="small text-muted">Date: <?= date('M d, Y', strtotime($order['created_at'])) ?></div>
                <div class="small text-muted">Status: <strong><?= strtoupper(e($order['order_status'])) ?></strong></div>
            </div>
        </div>

        <!-- Bill To -->
        <div class="row mb-4">
            <div class="col-6">
                <h6 class="text-uppercase text-muted small fw-bold mb-1">Billed To:</h6>
                <div class="fw-bold text-dark"><?= e($order['customer_name']) ?></div>
                <div class="small text-muted">Phone: <?= e($order['customer_phone']) ?></div>
                <?php if (!empty($order['customer_email'])): ?>
                    <div class="small text-muted">Email: <?= e($order['customer_email']) ?></div>
                <?php endif; ?>
            </div>
            <div class="col-6 text-end">
                <h6 class="text-uppercase text-muted small fw-bold mb-1">Fulfillment Details:</h6>
                <div class="small">Method: <strong><?= ucfirst(e($order['delivery_method'])) ?></strong></div>
                <div class="small text-muted"><?= e($order['delivery_address'] ?: 'Store Collection') ?>, <?= e($order['delivery_city']) ?></div>
                <div class="small">Payment: <strong class="badge bg-<?= $order['payment_status'] === 'verified' ? 'success' : 'warning text-dark' ?>"><?= strtoupper(e($order['payment_status'])) ?></strong></div>
            </div>
        </div>

        <!-- Table -->
        <table class="table table-bordered align-middle small mb-4">
            <thead class="table-light">
                <tr>
                    <th>Item Description</th>
                    <th>SKU</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Line Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="fw-bold"><?= e($item['product_name']) ?></td>
                        <td class="font-monospace"><?= e($item['sku']) ?></td>
                        <td class="text-center"><?= (int)$item['quantity'] ?></td>
                        <td class="text-end"><?= formatTZS($item['unit_price']) ?></td>
                        <td class="text-end fw-bold"><?= formatTZS($item['line_total']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Subtotal:</th>
                    <th class="text-end"><?= formatTZS($order['subtotal']) ?></th>
                </tr>
                <tr>
                    <th colspan="4" class="text-end">Delivery Charge:</th>
                    <th class="text-end"><?= formatTZS($order['delivery_fee']) ?></th>
                </tr>
                <tr class="table-light">
                    <th colspan="4" class="text-end fs-6">Grand Total:</th>
                    <th class="text-end fs-6 fw-black text-primary"><?= formatTZS($order['total_amount']) ?></th>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="border-top pt-3 small text-muted text-center">
            Thank you for choosing Pande Computers. All genuine electronics are backed by store warranty.<br>
            For support or warranty claims, contact +255 787 311 342.
        </div>
    </div>
</body>
</html>
