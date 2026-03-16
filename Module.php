<?php

namespace humhub\modules\qrcode;

class Module extends \humhub\components\Module
{
    public $id = 'qrcode';

    public function getName(): string
    {
        return 'QR Code Generator';
    }

    public function getDescription(): string
    {
        return 'Generador de código QR';
    }
}
