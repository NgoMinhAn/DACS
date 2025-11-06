<!-- Contact Requests List for Guide -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Contact Requests</h3>
                </div>
                <div class="card-body p-4">
                    <?php if (empty($contact_requests)): ?>
                        <div class="alert alert-info text-center mb-0">No contact requests yet.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Message</th>
                                        <th>Sent At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($contact_requests as $i => $req): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= htmlspecialchars($req->name) ?></td>
                                            <td><a href="mailto:<?= htmlspecialchars($req->email) ?>"><?= htmlspecialchars($req->email) ?></a></td>
                                            <td><?= nl2br(htmlspecialchars($req->message)) ?></td>
                                            <td><?= isset($req->created_at) ? htmlspecialchars($req->created_at) : '' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    <a href="<?php echo url('guide/dashboard'); ?>" class="btn btn-secondary mt-4"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div> 