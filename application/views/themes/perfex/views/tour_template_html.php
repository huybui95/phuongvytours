<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$pdf->SetFont('dejavusans', '', 10);
$pdf->writeHTML($html, true, false, true, false, '');
