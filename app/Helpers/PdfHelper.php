<?php

namespace App\Helpers;

class PdfHelper
{
    public static function getLogoBase64()
    {
        $path = public_path('assets/img/logo.png');
        if (! file_exists($path)) {
            return null;
        }
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        return 'data:image/'.$type.';base64,'.base64_encode($data);
    }
}
