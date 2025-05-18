<!-- Tour Guide Review Page -->
<div class="container mt-4 mb-5">
    <!-- Header -->
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo url(''); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo url('tourGuide/guides'); ?>">Tour Guides</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo url('tourGuide/profile/' . $guide->guide_id); ?>"><?php echo htmlspecialchars($guide->name); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Write a Review</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Write a Review for <?php echo htmlspecialchars($guide->name); ?></h4>
                </div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($success)): ?>
                        <div class="alert alert-success">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo url('tourGuide/submitReview'); ?>" method="post" id="reviewForm">
                        <input type="hidden" name="guide_id" value="<?php echo $guide->guide_id; ?>">
                        
                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label">Rating</label>
                            <div class="star-rating">
                                <?php for($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" required>
                                <label for="star<?php echo $i; ?>" title="<?php echo $i; ?> stars">
                                    <i class="far fa-star"></i>
                                </label>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <!-- Review Text -->
                        <div class="mb-4">
                            <label for="review_text" class="form-label">Your Review</label>
                            <textarea class="form-control" id="review_text" name="review_text" rows="5" 
                                    placeholder="Share your experience with this tour guide..." required></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS for Star Rating -->
<style>
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 0.5rem;
}

.star-rating input {
    display: none;
}

.star-rating label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #ffc107;
}

.star-rating label i {
    transition: all 0.2s ease;
}
</style>

<!-- JavaScript for Star Rating -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const starLabels = document.querySelectorAll('.star-rating label');
    
    starLabels.forEach(label => {
        label.addEventListener('mouseover', function() {
            const star = this.querySelector('i');
            star.classList.remove('far');
            star.classList.add('fas');
            
            let prevSibling = this.parentElement.previousElementSibling;
            while(prevSibling && prevSibling.tagName === 'LABEL') {
                const prevStar = prevSibling.querySelector('i');
                prevStar.classList.remove('far');
                prevStar.classList.add('fas');
                prevSibling = prevSibling.previousElementSibling;
            }
        });

        label.addEventListener('mouseout', function() {
            if (!this.previousElementSibling?.checked) {
                const star = this.querySelector('i');
                star.classList.remove('fas');
                star.classList.add('far');
                
                let prevSibling = this.parentElement.previousElementSibling;
                while(prevSibling && prevSibling.tagName === 'LABEL') {
                    if (!prevSibling.previousElementSibling?.checked) {
                        const prevStar = prevSibling.querySelector('i');
                        prevStar.classList.remove('fas');
                        prevStar.classList.add('far');
                    }
                    prevSibling = prevSibling.previousElementSibling;
                }
            }
        });
    });

    // Form validation
    const form = document.getElementById('reviewForm');
    form.addEventListener('submit', function(e) {
        const rating = document.querySelector('input[name="rating"]:checked');

        if (!rating) {
            e.preventDefault();
            alert('Please select a rating');
            return;
        }
    });
});
</script> 