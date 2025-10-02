<?php
require_once '../../includes/controllers/ReviewController.php';
$controller = new ReviewController();
$reviews = $controller->listReviewsForAdmin();
include '../../View/admin/review_management.php';
?>