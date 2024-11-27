<?php
session_start();

// Destroy the session to log the user out
session_destroy();

// Redirect to home page with a query parameter to trigger the toast notification
header("Location: index.php?logout=success");
exit;
?>
