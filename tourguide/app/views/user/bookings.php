<!-- My Bookings & Invoice History Page -->
<div class="container my-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div>
                <h1 class="display-5 fw-bold mb-2">
                    <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>
                    Lịch Sử Hóa Đơn
                </h1>
                <p class="text-muted mb-0">Xem và quản lý tất cả các đơn đặt tour và hóa đơn thanh toán của bạn</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-calendar-check fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Tổng Đơn</h6>
                            <h3 class="mb-0 fw-bold"><?php echo count($allBookings ?? $bookings); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Đã Thanh Toán</h6>
                            <h3 class="mb-0 fw-bold">
                                <?php 
                                    $paidCount = 0;
                                    $statsBookings = $allBookings ?? $bookings;
                                    foreach($statsBookings as $b) {
                                        if($b->payment_status == 'paid' || ($b->transaction_payment_status == 'success')) {
                                            $paidCount++;
                                        }
                                    }
                                    echo $paidCount;
                                ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Đang Chờ</h6>
                            <h3 class="mb-0 fw-bold">
                                <?php 
                                    $pendingCount = 0;
                                    $statsBookings = $allBookings ?? $bookings;
                                    foreach($statsBookings as $b) {
                                        // Đang chờ = chỉ những bookings có status là 'pending' (chờ xác nhận)
                                        // Loại trừ: completed, cancelled, và những cái đã accepted
                                        if ($b->status == 'pending') {
                                            $pendingCount++;
                                        }
                                    }
                                    echo $pendingCount;
                                ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-money-bill-wave fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Tổng Chi</h6>
                            <h3 class="mb-0 fw-bold">
                                <?php 
                                    $totalAmount = 0;
                                    $statsBookings = $allBookings ?? $bookings;
                                    foreach($statsBookings as $b) {
                                        if($b->payment_status == 'paid' || ($b->transaction_payment_status == 'success')) {
                                            $totalAmount += $b->total_price;
                                        }
                                    }
                                    echo number_format($totalAmount, 0, ',', '.') . ' đ';
                                ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($bookings)): ?>
        <!-- Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-file-invoice fa-5x text-muted mb-3"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Chưa Có Đơn Đặt Tour</h3>
                        <p class="text-muted mb-4">Bạn chưa có đơn đặt tour nào. Hãy bắt đầu khám phá và tìm hướng dẫn viên phù hợp với bạn!</p>
                        <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-lg btn-primary rounded-pill px-5">
                            <i class="fas fa-search me-2"></i>Tìm Hướng Dẫn Viên
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Bookings Table -->
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <tr>
                                <th class="ps-4 py-3">Mã Đơn</th>
                                <th class="py-3">Hướng Dẫn Viên</th>
                                <th class="py-3">Ngày & Giờ</th>
                                <th class="py-3">Số Tiền</th>
                                <th class="py-3">Thanh Toán</th>
                                <th class="py-3">Trạng Thái</th>
                                <th class="py-3 text-end pe-4">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $index => $booking): ?>
                                <tr class="border-bottom">
                                    <td class="ps-4">
                                        <div class="fw-bold text-primary">#<?php echo str_pad($booking->id, 6, '0', STR_PAD_LEFT); ?></div>
                                        <small class="text-muted">
                                            <?php echo date('d/m/Y H:i', strtotime($booking->created_at)); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($booking->guide_name); ?></div>
                                            <small class="text-muted">
                                                <i class="fas fa-users me-1"></i><?php echo (int)$booking->number_of_people; ?> người
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">
                                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                            <?php echo date('d/m/Y', strtotime($booking->booking_date)); ?>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?php echo date('H:i', strtotime($booking->start_time)); ?> - <?php echo date('H:i', strtotime($booking->end_time)); ?>
                                        </small>
                                        <?php if($booking->meeting_location): ?>
                                            <div class="mt-1">
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($booking->meeting_location); ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success fs-5">
                                            <?php echo number_format($booking->total_price, 0, ',', '.'); ?> đ
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i><?php echo (int)$booking->total_hours; ?> giờ
                                        </small>
                                    </td>
                                    <td>
                                        <?php 
                                            $paymentStatus = $booking->transaction_payment_status ?? $booking->payment_status ?? 'pending';
                                            $paymentMethod = $booking->transaction_payment_method ?? $booking->payment_method ?? 'N/A';
                                            $paymentDate = $booking->transaction_pay_date ?? $booking->payment_date ?? null;
                                            
                                            if($paymentStatus == 'success' || $booking->payment_status == 'paid'):
                                        ?>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-success rounded-pill me-2">
                                                    <i class="fas fa-check-circle me-1"></i>Đã thanh toán
                                                </span>
                                            </div>
                                            <?php if($paymentMethod): ?>
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-credit-card me-1"></i><?php echo htmlspecialchars($paymentMethod); ?>
                                                </small>
                                            <?php endif; ?>
                                            <?php if($paymentDate): ?>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-calendar me-1"></i><?php echo date('d/m/Y H:i', strtotime($paymentDate)); ?>
                                                </small>
                                            <?php endif; ?>
                                            <?php if($booking->vnp_BankCode): ?>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-university me-1"></i><?php echo htmlspecialchars($booking->vnp_BankCode); ?>
                                                </small>
                                            <?php endif; ?>
                                            <?php if($booking->vnp_TransactionNo): ?>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-receipt me-1"></i>Mã GD: <?php echo htmlspecialchars($booking->vnp_TransactionNo); ?>
                                                </small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark rounded-pill">
                                                <i class="fas fa-clock me-1"></i>Chưa thanh toán
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $statusClass = '';
                                            $statusIcon = '';
                                            $statusText = '';
                                            switch ($booking->status) {
                                                case 'pending':
                                                    $statusClass = 'bg-warning text-dark';
                                                    $statusIcon = 'fa-clock';
                                                    $statusText = 'Chờ xác nhận';
                                                    break;
                                                case 'confirmed':
                                                    $statusClass = 'bg-info text-white';
                                                    $statusIcon = 'fa-check-circle';
                                                    $statusText = 'Đã xác nhận';
                                                    break;
                                                case 'accepted':
                                                    $statusClass = 'bg-primary text-white';
                                                    $statusIcon = 'fa-check';
                                                    $statusText = 'Đã chấp nhận';
                                                    break;
                                                case 'completed':
                                                    $statusClass = 'bg-success text-white';
                                                    $statusIcon = 'fa-check-double';
                                                    $statusText = 'Hoàn thành';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'bg-danger text-white';
                                                    $statusIcon = 'fa-times-circle';
                                                    $statusText = 'Đã hủy';
                                                    break;
                                                case 'declined':
                                                    $statusClass = 'bg-secondary text-white';
                                                    $statusIcon = 'fa-times';
                                                    $statusText = 'Từ chối';
                                                    break;
                                                default:
                                                    $statusClass = 'bg-secondary text-white';
                                                    $statusIcon = 'fa-info-circle';
                                                    $statusText = ucfirst($booking->status);
                                            }
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?> rounded-pill px-3 py-2">
                                            <i class="fas <?php echo $statusIcon; ?> me-1"></i>
                                            <?php echo $statusText; ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" 
                                                    onclick="viewInvoice(<?php echo $booking->id; ?>)"
                                                    title="Xem hóa đơn">
                                                <i class="fas fa-file-invoice"></i>
                                            </button>
                                            <a href="<?php echo url('user/chat/' . $booking->id); ?>" 
                                               class="btn btn-sm btn-outline-info rounded-pill"
                                               title="Nhắn tin">
                                                <i class="fas fa-comments"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill"
                                                    onclick="printInvoice(<?php echo $booking->id; ?>)"
                                                    title="In hóa đơn">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <!-- Previous Button -->
                        <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link rounded-pill me-2" 
                               href="<?php echo url('user/bookings?page=' . max(1, $currentPage - 1)); ?>"
                               aria-label="Previous">
                                <span aria-hidden="true">&laquo; Trước</span>
                            </a>
                        </li>

                        <!-- Page Numbers -->
                        <?php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                        
                        // Show first page if not in range
                        if ($startPage > 1) {
                            echo '<li class="page-item"><a class="page-link rounded-pill" href="' . url('user/bookings?page=1') . '">1</a></li>';
                            if ($startPage > 2) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }
                        
                        // Show page numbers
                        for ($i = $startPage; $i <= $endPage; $i++) {
                            $activeClass = ($i == $currentPage) ? 'active' : '';
                            echo '<li class="page-item ' . $activeClass . '">';
                            echo '<a class="page-link rounded-pill" href="' . url('user/bookings?page=' . $i) . '">' . $i . '</a>';
                            echo '</li>';
                        }
                        
                        // Show last page if not in range
                        if ($endPage < $totalPages) {
                            if ($endPage < $totalPages - 1) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link rounded-pill" href="' . url('user/bookings?page=' . $totalPages) . '">' . $totalPages . '</a></li>';
                        }
                        ?>

                        <!-- Next Button -->
                        <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link rounded-pill ms-2" 
                               href="<?php echo url('user/bookings?page=' . min($totalPages, $currentPage + 1)); ?>"
                               aria-label="Next">
                                <span aria-hidden="true">Sau &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                
                <!-- Pagination Info -->
                <?php if (isset($currentPage) && isset($totalPages)): ?>
                <div class="text-center mt-3">
                    <p class="text-muted mb-0">
                        Trang <?php echo $currentPage; ?> / <?php echo $totalPages; ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Invoice Modal -->
        <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="invoiceModalLabel">
                            <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>
                            Chi Tiết Hóa Đơn
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="invoiceModalBody">
                        <!-- Invoice content will be loaded here -->
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary rounded-pill" onclick="printInvoice()">
                            <i class="fas fa-print me-2"></i>In Hóa Đơn
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Pagination Styles */
.pagination {
    gap: 5px;
}

.page-link {
    border: 1px solid #dee2e6;
    color: #667eea;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.page-link:hover {
    background-color: #667eea;
    color: white;
    border-color: #667eea;
    transform: translateY(-2px);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
}

.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    cursor: not-allowed;
    opacity: 0.5;
}
</style>

<script>
function viewInvoice(bookingId) {
    // Load invoice details via AJAX
    fetch('<?php echo url('user/invoice/'); ?>' + bookingId)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('invoiceModalBody').innerHTML = data.html;
                var invoiceModalElement = document.getElementById('invoiceModal');
                var invoiceModal = new bootstrap.Modal(invoiceModalElement);
                invoiceModal.show();
                // Store booking ID for print function
                invoiceModalElement.setAttribute('data-booking-id', bookingId);
            } else {
                alert('Không thể tải hóa đơn. Vui lòng thử lại.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Đã xảy ra lỗi. Vui lòng thử lại.');
        });
}

function printInvoice(bookingId) {
    if(bookingId) {
        // Open print page in new window
        window.open('<?php echo url('user/invoice/print/'); ?>' + bookingId, '_blank');
    } else {
        // Print current modal content
        var modal = document.getElementById('invoiceModal');
        var bookingId = modal ? modal.getAttribute('data-booking-id') : null;
        if(bookingId) {
            window.open('<?php echo url('user/invoice/print/'); ?>' + bookingId, '_blank');
        } else {
            alert('Không tìm thấy mã đơn hàng');
        }
    }
}
</script>