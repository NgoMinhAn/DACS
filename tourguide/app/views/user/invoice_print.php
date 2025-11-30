<?php
// Define payment variables
$paymentStatus = $booking->transaction_payment_status ?? $booking->payment_status ?? 'pending';
$paymentMethod = $booking->transaction_payment_method ?? $booking->payment_method ?? 'N/A';
$paymentDate = $booking->transaction_pay_date ?? $booking->payment_date ?? null;
?>
<!DOCTYPE html>
<?php if (session_status() === PHP_SESSION_NONE) { @session_start(); } ?>
<html lang="<?php echo function_exists('getLocale') ? getLocale() : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            @page { margin: 1cm; }
        }
        body {
            font-family: 'Arial', sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        .invoice-header {
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .invoice-title {
            color: #667eea;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .invoice-info {
            color: #666;
            font-size: 14px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #667eea;
            color: white;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            color: white;
        }
        .status-paid {
            background-color: #28a745;
        }
        .status-pending {
            background-color: #ffc107;
            color: #333;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .mb-0 { margin-bottom: 0; }
        .mt-4 { margin-top: 30px; }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="invoice-header">
        <div class="invoice-title"><?php echo __('invoice_meta.title'); ?></div>
        <div class="invoice-info">
            <strong><?php echo __('invoice_meta.invoice_number'); ?>:</strong> #<?php echo str_pad($booking->id ?? 0, 6, '0', STR_PAD_LEFT); ?><br>
            <strong><?php echo __('invoice_meta.created_at'); ?>:</strong> <?php echo !empty($booking->created_at) ? date('d/m/Y H:i:s', strtotime($booking->created_at)) : date('d/m/Y H:i:s'); ?>
        </div>
        <div class="text-right" style="margin-top: -40px;">
            <?php 
                $statusClass = ($paymentStatus == 'success' || ($booking->payment_status ?? '') == 'paid') ? 'status-paid' : 'status-pending';
                $statusText = ($paymentStatus == 'success' || ($booking->payment_status ?? '') == 'paid') ? __('status.sent') : __('status.cancelled');
            ?>
            <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
        </div>
    </div>

    <!-- Customer & Guide Info -->
    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <div style="flex: 1;">
            <div class="section-title"><?php echo __('invoice_meta.customer_info'); ?></div>
            <div class="info-box">
                <p class="mb-0"><strong>Tên:</strong> <?php echo htmlspecialchars($user->name ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="mb-0"><strong>Email:</strong> <?php echo htmlspecialchars($user->email ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
                <?php if(!empty($user->phone)): ?>
                    <p class="mb-0"><strong>Điện thoại:</strong> <?php echo htmlspecialchars($user->phone, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div style="flex: 1;">
            <div class="section-title"><?php echo __('invoice.guide_info'); ?></div>
            <div class="info-box">
                <p class="mb-0"><strong>Tên:</strong> <?php echo htmlspecialchars($booking->guide_name ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
                <?php if(!empty($booking->guide_email)): ?>
                    <p class="mb-0"><strong>Email:</strong> <?php echo htmlspecialchars($booking->guide_email, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
                <?php if(!empty($booking->guide_phone)): ?>
                    <p class="mb-0"><strong>Điện thoại:</strong> <?php echo htmlspecialchars($booking->guide_phone, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Booking Details -->
    <div class="section-title"><?php echo __('invoice_meta.booking_details'); ?></div>
    <table>
        <tr>
            <th style="width: 30%;"><?php echo __('table.info') ?? 'Information'; ?></th>
            <th><?php echo __('table.details') ?? 'Details'; ?></th>
        </tr>
        <tr>
            <td><strong>Ngày đặt tour</strong></td>
            <td><?php echo !empty($booking->booking_date) ? date('d/m/Y', strtotime($booking->booking_date)) : 'N/A'; ?></td>
        </tr>
        <tr>
            <td><strong>Thời gian</strong></td>
            <td>
                <?php 
                    $startTime = !empty($booking->start_time) ? date('H:i', strtotime($booking->start_time)) : 'N/A';
                    $endTime = !empty($booking->end_time) ? date('H:i', strtotime($booking->end_time)) : 'N/A';
                    $hours = (int)($booking->total_hours ?? 0);
                    echo $startTime . ' - ' . $endTime . ' (' . $hours . ' giờ)';
                ?>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo __('invoice.meeting_place'); ?></strong></td>
            <td><?php echo htmlspecialchars($booking->meeting_location ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td><strong>Số người</strong></td>
            <td><?php echo (int)($booking->number_of_people ?? 1); ?> người</td>
        </tr>
        <?php if(!empty($booking->special_requests)): ?>
        <tr>
            <td><strong>Yêu cầu đặc biệt</strong></td>
            <td><?php echo nl2br(htmlspecialchars($booking->special_requests, ENT_QUOTES, 'UTF-8')); ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td><strong><?php echo __('status_label') ?? 'Status'; ?></strong></td>
            <td>
                <?php 
                    echo __('booking_status.' . ($booking->status ?? ''));
                ?>
            </td>
        </tr>
    </table>

    <!-- Payment Information -->
    <div class="section-title"><?php echo __('invoice_meta.payment_info'); ?></div>
    <table>
        <tr>
            <th style="width: 30%;">Thông Tin</th>
            <th>Chi Tiết</th>
        </tr>
        <tr>
            <td><strong>Tổng tiền</strong></td>
            <td class="total-amount">
                <?php 
                    $exchangeRate = 24500; // USD to VND exchange rate
                    echo number_format(($booking->total_price ?? 0) * $exchangeRate, 0, ',', '.'); 
                ?> đ
            </td>
        </tr>
        <tr>
            <td><strong>Phương thức thanh toán</strong></td>
            <td><?php echo htmlspecialchars($paymentMethod ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <?php if($paymentDate && !empty($paymentDate)): ?>
        <tr>
            <td><strong>Ngày thanh toán</strong></td>
            <td><?php echo date('d/m/Y H:i:s', strtotime($paymentDate)); ?></td>
        </tr>
        <?php endif; ?>
        <?php if(!empty($booking->vnp_BankCode)): ?>
        <tr>
            <td><strong>Ngân hàng</strong></td>
            <td><?php echo htmlspecialchars($booking->vnp_BankCode, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <?php endif; ?>
        <?php if(!empty($booking->vnp_TransactionNo)): ?>
        <tr>
            <td><strong>Mã giao dịch</strong></td>
            <td><?php echo htmlspecialchars($booking->vnp_TransactionNo, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <?php endif; ?>
        <?php if(!empty($booking->vnp_BankTranNo)): ?>
        <tr>
            <td><strong>Mã giao dịch ngân hàng</strong></td>
            <td><?php echo htmlspecialchars($booking->vnp_BankTranNo, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <?php endif; ?>
        <?php if(!empty($booking->vnp_CardType)): ?>
        <tr>
            <td><strong>Loại thẻ</strong></td>
            <td><?php echo htmlspecialchars($booking->vnp_CardType, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <?php endif; ?>
        <?php if(!empty($booking->vnp_TxnRef)): ?>
        <tr>
            <td><strong>Mã đơn hàng (VNPay)</strong></td>
            <td><?php echo htmlspecialchars($booking->vnp_TxnRef, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <?php endif; ?>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p class="mb-0"><?php echo __('invoice_meta.thank_you'); ?></p>
        <p class="mb-0" style="margin-top: 10px;">
            <small><?php echo __('invoice_meta.generated_by') ?? 'This invoice was automatically generated by the Tour Guide system'; ?></small>
        </p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 16px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <?php echo __('buttons.print') ?? 'Print Invoice'; ?>
        </button>
        <button onclick="window.close()" style="padding: 10px 30px; font-size: 16px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            <?php echo __('buttons.close') ?? 'Close'; ?>
        </button>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
