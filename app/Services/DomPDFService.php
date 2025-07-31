<?php

namespace App\Services;

require_once public_path('../app/DomPDF/autoload.inc.php');

use Dompdf\Dompdf;
use Dompdf\Options;

class DomPDFService
{
    protected $dompdf;

    public function __construct()
    {
        // Initialize DomPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isJavascriptEnabled', true);
        $options->set('debug', true);
        
        $this->dompdf = new Dompdf($options);
    }

    public function loadHtml($html)
    {
        $this->dompdf->loadHtml($html);
    }

    public function setPaper($size = 'A4', $orientation = 'portrait')
    {
        $this->dompdf->setPaper($size, $orientation);
    }

    public function render()
    {
        $this->dompdf->render();
    }

    public function stream($filename = 'document.pdf')
    {
        $this->dompdf->stream($filename);
    }

    public function download($filename = 'document.pdf')
    {
        $this->dompdf->stream($filename, ['Attachment' => 1]);
    }

    public function output()
    {
        return $this->dompdf->output();
    }
}
