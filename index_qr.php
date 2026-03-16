<?php

/**
 * Custom page — Tarjeta de fidelización Vegalsa Eroski.
 *
 * La generación del SVG se delega al módulo vegalsa-qrcode,
 * que debe estar instalado y activado en HumHub.
 *
 * En HumHub el autoload de Yii2/Composer ya está disponible,
 * por lo que no hace falta ningún require_once adicional.
 */

// ── Autoloader del módulo (vendor autocontenido, sin tocar la raíz) ──────────
require_once Yii::getAlias('@app') . '/modules/qrcode/vendor/autoload.php';

use humhub\modules\qrcode\components\QrGenerator;

// ── EAN hardcodeado (fase de pruebas) ────────────────────────────────────────
$ean     = QrGenerator::completarEAN13('0044027700130');
$qr_svg  = '';
$error   = '';

try {
    $qr_svg = QrGenerator::generate($ean);
} catch (RuntimeException $e) {
    $error = 'Error al generar el código QR: ' . $e->getMessage();
} catch (\Throwable $e) {
    $error = 'Error inesperado: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarjeta de descuento</title>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root { --green: #2e9e6b; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef1f0;
            font-family: 'Nunito', sans-serif;
            padding: 2rem;
        }

        .card {
            background: var(--green);
            border-radius: 28px;
            padding: 1.5rem 1.5rem 2rem;
            width: 100%;
            max-width: 360px;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            box-shadow:
                0 20px 60px rgba(46, 158, 107, 0.35),
                0 4px 16px rgba(0, 0, 0, 0.15);
            animation: appear 0.5s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes appear {
            from { opacity: 0; transform: translateY(22px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.25);
        }

        .card-logo {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .card-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.01em;
        }

        .qr-panel {
            background: #fff;
            border-radius: 16px;
            padding: 1.25rem 1rem 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-panel svg {
            display: block;
            width: 100%;
            height: auto;
        }

        .ean-number {
            color: #fff;
            font-family: 'Share Tech Mono', monospace;
            font-size: 1.05rem;
            letter-spacing: 0.12em;
            text-align: center;
            opacity: 0.92;
        }

        .error {
            color: #fff;
            background: rgba(0,0,0,0.2);
            border-radius: 10px;
            font-size: 0.85rem;
            text-align: center;
            padding: 0.75rem 1rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="card-logo">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 2000 679" width="100" height="34">
                    <image href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAB9AAAAKnCAMAAADp+SaVAAAC/VBMVEVHcEwQgkEQgkH///8QgkEQgkH///8QgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkH///8QgkEQgkEQgkEQgkH7z80QgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkHsMScQgkEQgkEQgkEQgkEQgkEQgkHsMScQgkEQgkEQgkHsMSfsMScQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkEQgkHsMScQgkEQgkEQgkEQgkEQgkHsMScQgkH///8QgkHsMScQgkHsMSf////sMSf////sMSf////sMSfsMSfsMSfsMSf////sMScQgkHsMScQgkH////////sMSfsMSfsMSfsMSfsMSf////sMSfsMSfsMScQgkHsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSfsMSf////////////////////sMSf////sMSf////////////////////////////////////////////////////////////////////////////////sMScQgkH///8RaLLrMCb+/v7sNCrsNy372NbrLyUPZ7H3pqHrLiT++/v+9/fsMijrLCL97ezwXFT+/f31kYzsOC/0hX/84N8Wa7T3qKT96un4uLQcb7X709HrKyDxZl7vUkn1mZT1j4rA1urtPDMNZbHsNSv85uT+9PTycWqYvt7S4/H2n5r97+783NvuS0L6zszuRj3vTkXzgnzwWFDtQDf0ioT2o576ycfwX1f98vHxa2T6xML5vLntQzr3rar5wL371tTzfXfr8vjf6/XydG3vVEz4s7D5+/3zeHGGstj1lpBEiMLz9/v2nJc3gL4sebsjcbZ3qdPrMCXwY1u0z+demcuqyOL2pqFPj8VpoM+hw+HK3e73pKC+QUliU31uUHZyT3SVyRanAAAAnXRSTlMAueBcFwT+/vzw+4XOAfbrNUBKVZ4RBeT0AXzJCwb5IR3DDQmrGi6T3JcC2LIq+16m6L6vnP5sPFsG/bahd3JHj0/ugNUDZxQlijj1YgQx3FINpREP936Ohdbj3ulDY9FoNZ8x0FkrbQnD7iem8WuXyXwmIbsWrVK0Nk0ecxo8QEi36EEW00X8Qwkk9+9NHSrIiFWTdC/zY5eam51rf/CehgAAi1ZJREFUeNrs3V1rI+cZBuAltARKaaGQA1NISpOUEsr0pAPVNBRKESXoQAhjJBlFB7IlJLH6sGxLlr+yR2H/xLO/tR9pSA6cXa+tjxnpun7CfR88zLzvPPMCnqh/Nzt53R5POt9p3Hbve+e1A8EAQFEcnlyP6/NpK0mS7AeV6sVkfD3rywcAcq8069avTodpPChtDTrtWVlMAJBf5VpvPG+2snirUXNyXxMWAORTrde9alXSeLdsOF9cCgwAcufgbjGpZmk8Uno0XxxKDQDyZXZ70Yo03kerc+LOOwDkR6lXb1bivaXNsYd0AMiJ/knjOImnSEedngvvAJAD5WWjmsWTDV6Z6ACwdbOzZhrPkFbHJSkCwLbPzo/iedLhmYN0ACjS2fnDKg0THQC2eHaexkpUGvbGAcB2xnm9mcaqVBrO0QFgG2fn1SRWJx26GQcAG1aa3R4nsVrHC0vjAGCTbsanWazc6VKyALAxtfbFKNYgvboRLgBsRv/V1SiNtUjqjtEBYBP6y04ri3VpdSUMAGtX7jWqaazRtCdkAFiz2nh6FGuV1vtiBoB1Kl1fjdJYs9a1oAFgfcq9+jCNtUsvLHUHgLW5GTfT2ITKWNgAsLa37ZXYkOlM3gCwBuXepBUbk5zZAAsAK3dw2W4msUGnJ0IHgBUrv55XYqOyM/viAGC1bhrVNDaseS53AFih/mJwFBuXjMuiB4CVmU2GaWzBqW/RAWBVDtvTLLZi5B8tALAaASf1xhsZCd+ZSz6lBvwPdNeLoFcaBP4AiCCzM8/xPpLfbgf/YA6K9J6EAIY2lXOKmpL4jrCjlQC9YHrb84h2bLGFVHKlUEyuoiAIuZ4HQNi8srgR9CqZC0O2mH6sM7+GiLJipS8Q6ibJLmH4IwkYGcBXaVIANqAByBhUF4b5tFQmYGrFp5c0dStiqhvH4qjMNtQGRGXmjXIGd4DP7G7Rb8KFpBsQAAAABJRU5ErkJggg==" x="0" y="0" width="2000" height="679"/>
                </svg>
            </div>
            <span class="card-name">Mi tarjeta</span>
        </div>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php else: ?>
            <div class="qr-panel">
                <?= $qr_svg ?>
            </div>
            <p class="ean-number"><?= htmlspecialchars($ean) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
