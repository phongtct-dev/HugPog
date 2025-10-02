<?php
// File: project/public/submit_review.php
require_once '../includes/controllers/ReviewController.php';
$controller = new ReviewController();
$controller->handleSubmitReview();
?>