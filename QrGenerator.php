<?php

namespace humhub\modules\qrcode;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use RuntimeException;

/**
 * Genera el SVG de un código QR a partir de un EAN-13.
 *
 * Esta clase encapsula únicamente la lógica de generación:
 * no produce HTML, no tiene dependencias de HTTP ni de Yii2.
 * Puede instanciarse directamente desde cualquier controlador
 * o widget sin necesidad de llamadas HTTP.
 */
class QrGenerator
{
    // ── API pública ────────────────────────────────────────────────────────

    /**
     * Completa el dígito de control de un EAN-13 de 12 dígitos.
     * Si ya tiene 13 dígitos lo devuelve sin modificar.
     *
     * @param  string $ean  12 o 13 dígitos numéricos
     * @return string       EAN-13 con 13 dígitos
     */
    public static function completarEAN13(string $ean): string
    {
        if (strlen($ean) === 13) {
            return $ean;
        }

        $digits = str_split(substr($ean, 0, 12));
        $sum    = 0;

        foreach ($digits as $i => $d) {
            $sum += ($i % 2 === 0) ? (int)$d : (int)$d * 3;
        }

        return $ean . ((10 - ($sum % 10)) % 10);
    }

    /**
     * Genera el SVG del código QR para el EAN-13 dado.
     *
     * - Usa chillerlan/php-qrcode para renderizar en formato SVG.
     * - Devuelve el SVG completo como string, listo para incrustarse
     *   directamente en HTML con <?= $svg ?>.
     *
     * @param  string $ean  EAN-13 de 13 dígitos (usar completarEAN13 antes si hace falta)
     * @return string       SVG completo
     *
     * @throws RuntimeException  Si la librería no puede generar el QR.
     */



    public static function generate(string $ean): string
    {
        $options = new QROptions([
            'outputType'           => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel'             => QRCode::ECC_M,
            'svgViewBox'           => true,
            'svgAddXmlHeader'      => false,
            'svgUseFillAttributes' => true,   // ← necesario para que se apliquen los colores
            'drawLightModules'     => true,
            'moduleValues'         => [
                // Módulos OSCUROS (datos) → negro
                QRMatrix::M_DATA          | QRMatrix::IS_DARK => '#000000',
                QRMatrix::M_FINDER        | QRMatrix::IS_DARK => '#000000',
                QRMatrix::M_FINDER_DOT    | QRMatrix::IS_DARK => '#000000',
                QRMatrix::M_ALIGNMENT     | QRMatrix::IS_DARK => '#000000',
                QRMatrix::M_TIMING        | QRMatrix::IS_DARK => '#000000',
                QRMatrix::M_FORMAT        | QRMatrix::IS_DARK => '#000000',
                QRMatrix::M_DARKMODULE    | QRMatrix::IS_DARK => '#000000',
                QRMatrix::M_SEPARATOR                         => '#ffffff',
                // Módulos CLAROS (fondo) → blanco
                QRMatrix::M_DATA                              => '#ffffff',
                QRMatrix::M_FINDER                            => '#ffffff',
                QRMatrix::M_ALIGNMENT                         => '#ffffff',
                QRMatrix::M_TIMING                            => '#ffffff',
                QRMatrix::M_FORMAT                            => '#ffffff',
                QRMatrix::M_QUIETZONE                         => '#ffffff',
            ],
            'scale'       => 5,
            'imageBase64' => false,
        ]);

        try {
            $qrcode = new QRCode($options);
            $svg    = $qrcode->render($ean);
        } catch (\Exception $e) {
            throw new \RuntimeException('No se pudo generar el código QR: ' . $e->getMessage(), 0, $e);
        }

        if (empty($svg)) {
            throw new \RuntimeException('La librería devolvió un SVG vacío.');
        }

        return $svg;
    }
}
