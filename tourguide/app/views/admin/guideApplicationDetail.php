<div class="container py-4">
    <h2>Guide Application Detail</h2>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <strong>Applicant:</strong> <?php echo htmlspecialchars($application->name); ?> (<?php echo htmlspecialchars($application->email); ?>)
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Location:</strong> <?php echo htmlspecialchars($application->location); ?><br>
                    <strong>Phone:</strong> <?php echo htmlspecialchars($application->phone); ?><br>
                    <strong>Submitted At:</strong> <?php echo htmlspecialchars($application->created_at); ?><br>
                </div>
                <div class="col-md-6">
                    <?php if ($application->profile_image): ?>
                        <img src="<?php echo url('public/uploads/avatars/' . $application->profile_image); ?>" alt="Profile Image" class="img-thumbnail" style="max-width: 150px;">
                    <?php endif; ?>
                </div>
            </div>
            <div class="mb-3">
                <strong>Certifications:</strong><br>
                <div class="bg-light p-2 rounded"> <?php echo nl2br(htmlspecialchars($application->certifications)); ?> </div>
            </div>
            <div class="mb-3">
                <strong>Bio:</strong><br>
                <div class="bg-light p-2 rounded"> <?php echo nl2br(htmlspecialchars($application->bio)); ?> </div>
            </div>
            <div class="mb-3">
                <strong>Experience:</strong><br>
                <div class="bg-light p-2 rounded"> <?php echo nl2br(htmlspecialchars($application->experience)); ?> </div>
            </div>
            <div class="mb-3">
                <strong>Specialties:</strong> <?php echo htmlspecialchars($application->specialty); ?>
            </div>
            <div class="mb-3">
                <strong>Languages:</strong> <?php echo htmlspecialchars($application->languages ?? ''); ?>
            </div>
            <div class="mb-3">
                <strong>Hourly Rate:</strong> $<?php echo htmlspecialchars($application->hourly_rate); ?>
                <br><strong>Daily Rate:</strong> $<?php echo htmlspecialchars($application->daily_rate); ?>
            </div>
            <form method="POST" class="d-flex gap-2">
                <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                <a href="<?php echo url('admin/guideApplications'); ?>" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div> 