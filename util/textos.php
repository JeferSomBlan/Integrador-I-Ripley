<?php

if (!function_exists('limpiar_html')) {

    function limpiar_html($texto) {
        return htmlspecialchars($texto, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

}

if (!function_exists('formatear_nombre')) {

    function formatear_nombre($nombre) {
        $nombre_limpio = preg_replace('/\s+/', ' ', trim($nombre));
        return ucwords(strtolower($nombre_limpio));
    }

}