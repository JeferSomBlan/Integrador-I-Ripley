<?php
session_start();

// Obtener la respuesta del usuario
$captchaRespuesta = intval($_POST['captcha']);

// Verificar si la respuesta es correcta
if ($captchaRespuesta === $_SESSION['captcha_result']) {
    echo json_encode(['success' => true, 'resultado' => $_SESSION['captcha_result']]);
} else {
    // Generar un nuevo CAPTCHA
    $numero1 = rand(1, 10);
    $numero2 = rand(1, 10);
    $_SESSION['captcha_result'] = $numero1 + $numero2;

    echo json_encode([
        'success' => false,
        'numero1' => $numero1,
        'numero2' => $numero2
    ]);
}
?>
