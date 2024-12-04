<?php
// src/Service/PdfGenerator.php
namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGenerator
{
    private Dompdf $dompdf;

    public function __construct()
    {
        // Configure Dompdf avec les options nécessaires
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->setIsHtml5ParserEnabled(true);

        $this->dompdf = new Dompdf($options);
    }

    public function generate(string $htmlContent, string $filename): string
    {
        $this->dompdf->loadHtml($htmlContent);

        // Configurer la taille et l'orientation du document
        $this->dompdf->setPaper('A4', 'portrait');

        // Générer le PDF
        $this->dompdf->render();

        // Enregistrer le PDF sur le serveur ou le renvoyer au navigateur
        $output = $this->dompdf->output();
        $filePath = sys_get_temp_dir() . '/' . $filename;
        file_put_contents($filePath, $output);

        return $filePath;
    }
}
