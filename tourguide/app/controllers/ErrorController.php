<?php
class ErrorController {
    public function notFound() {
        http_response_code(404);
        $title = 'Page Not Found';
        require_once VIEW_PATH . '/shares/header.php';
        require_once VIEW_PATH . '/pages/notFound.php';
        require_once VIEW_PATH . '/shares/footer.php';
    }
}


