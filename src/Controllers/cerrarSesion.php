<?php

session_start();
unset($_SESSION['logged_in']);
session_destroy();

echo json_encode(['success' => true, 'message' => 'Sesión cerrada correctamente.']);
exit;
