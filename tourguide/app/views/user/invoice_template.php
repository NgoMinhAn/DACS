<?php
// Invoice Template for Modal
$paymentStatus = $booking->transaction_payment_status ?? $booking->payment_status ?? 'pending';
$paymentMethod = $booking->transaction_payment_method ?? $booking->payment_method ?? 'N/A';
$paymentDate = $booking->transaction_pay_date ?? $booking->payment_date ?? null;
?>
<div class="invoice-container">
    <!-- Invoice Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold text-primary mb-2">
                <i class="fas fa-file-invoice-dollar me-2"></i>
                <?php echo __('invoice_meta.title'); ?>
            </h3>
            <p class="text-muted mb-1">
                <strong><?php echo __('invoice_meta.invoice_number'); ?>:</strong> #<?php echo str_pad($booking->id, 6, '0', STR_PAD_LEFT); ?>
            </p>
            <p class="text-muted mb-1">
                <strong><?php echo __('invoice_meta.created_at'); ?>:</strong> <?php echo date('d/m/Y H:i:s', strtotime($booking->created_at)); ?>
            </p>
        </div>
        <div class="col-md-6 text-end">
            <div class="badge bg-<?php echo ($paymentStatus == 'success' || $booking->payment_status == 'paid') ? 'success' : 'warning'; ?> text-white fs-6 px-4 py-2 rounded-pill">
                <?php echo ($paymentStatus == 'success' || $booking->payment_status == 'paid') ? __('status.sent') : __('status.cancelled'); ?>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <!-- Customer & Guide Info -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h5 class="fw-bold mb-3"><?php echo __('invoice_meta.customer_info'); ?></h5>
            <div class="p-3 bg-light rounded">
                <p class="mb-2"><strong>Tên:</strong> <?php echo htmlspecialchars($user->name ?? 'N/A'); ?></p>
                <p class="mb-2"><strong>Email:</strong> <?php echo htmlspecialchars($user->email ?? 'N/A'); ?></p>
                <?php if(isset($user->phone)): ?>
                    <p class="mb-0"><strong>Điện thoại:</strong> <?php echo htmlspecialchars($user->phone); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <h5 class="fw-bold mb-3"><?php echo __('invoice.guide_info'); ?></h5>
            <div class="p-3 bg-light rounded">
                <p class="mb-2"><strong>Tên:</strong> <?php echo htmlspecialchars($booking->guide_name ?? 'N/A'); ?></p>
                <?php if(isset($booking->guide_email)): ?>
                    <p class="mb-2"><strong>Email:</strong> <?php echo htmlspecialchars($booking->guide_email); ?></p>
                <?php endif; ?>
                <?php if(isset($booking->guide_phone)): ?>
                    <p class="mb-0"><strong>Điện thoại:</strong> <?php echo htmlspecialchars($booking->guide_phone); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Booking Details -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="fw-bold mb-3"><?php echo __('invoice_meta.booking_details'); ?></h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Thông Tin</th>
                            <th>Chi Tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Ngày đặt tour</strong></td>
                            <td><?php echo date('d/m/Y', strtotime($booking->booking_date)); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Thời gian</strong></td>
                            <td>
                                <?php echo date('H:i', strtotime($booking->start_time)); ?> - 
                                <?php echo date('H:i', strtotime($booking->end_time)); ?> 
                                (<?php echo (int)$booking->total_hours; ?> giờ)
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php echo __('invoice.meeting_place'); ?></strong></td>
                            <td><?php echo htmlspecialchars($booking->meeting_location ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Số người</strong></td>
                            <td><?php echo (int)$booking->number_of_people; ?> người</td>
                        </tr>
                        <?php if($booking->special_requests): ?>
                        <tr>
                            <td><strong>Yêu cầu đặc biệt</strong></td>
                            <td><?php echo nl2br(htmlspecialchars($booking->special_requests)); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td><strong><?php echo __('status_label') ?? 'Status'; ?></strong></td>
                            <td>
                                <span class="badge bg-info">
                                    <?php 
                                        $statusKey = $booking->status ?? '';
                                        echo __('booking_status.' . $statusKey);
                                    ?>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="fw-bold mb-3"><?php echo __('invoice_meta.payment_info'); ?></h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Thông Tin</th>
                            <th>Chi Tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Tổng tiền</strong></td>
                            <td class="fw-bold text-success fs-5">
                                <?php echo number_format($booking->total_price, 0, ',', '.'); ?> đ
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Phương thức thanh toán</strong></td>
                            <td><?php echo htmlspecialchars($paymentMethod); ?></td>
                        </tr>
                        <?php if($paymentDate): ?>
                        <tr>
                            <td><strong>Ngày thanh toán</strong></td>
                            <td><?php echo date('d/m/Y H:i:s', strtotime($paymentDate)); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($booking->vnp_BankCode): ?>
                        <tr>
                            <td><strong>Ngân hàng</strong></td>
                            <td><?php echo htmlspecialchars($booking->vnp_BankCode); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($booking->vnp_TransactionNo): ?>
                        <tr>
                            <td><strong>Mã giao dịch</strong></td>
                            <td><?php echo htmlspecialchars($booking->vnp_TransactionNo); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($booking->vnp_BankTranNo): ?>
                        <tr>
                            <td><strong>Mã giao dịch ngân hàng</strong></td>
                            <td><?php echo htmlspecialchars($booking->vnp_BankTranNo); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($booking->vnp_CardType): ?>
                        <tr>
                            <td><strong>Loại thẻ</strong></td>
                            <td><?php echo htmlspecialchars($booking->vnp_CardType); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($booking->vnp_TxnRef): ?>
                        <tr>
                            <td><strong>Mã đơn hàng (VNPay)</strong></td>
                            <td><?php echo htmlspecialchars($booking->vnp_TxnRef); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <p class="text-muted mb-0">
                <small><?php echo __('invoice_meta.thank_you'); ?></small>
            </p>
        </div>
    </div>
</div>

<style>
.invoice-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}
</style>


