<?php
defined('BASEPATH') or exit('No direct script access allowed');

$columns = ['name', 'view_file', 'active'];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'tourtemplates';

$result  = data_tables_init($columns, $sIndexColumn, $sTable, [], [], ['id']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['name'];
    $row[] = $aRow['view_file'];
    $row[] = $aRow['active'] ? 'Có' : 'Không';

    $row[] = '
        <a href="' . admin_url('tour_templates/template/' . $aRow['id']) . '" class="btn btn-default btn-sm">Sửa</a>
        <a href="' . admin_url('tour_templates/delete/' . $aRow['id']) . '" class="btn btn-danger btn-sm _delete">Xoá</a>';

    $output['aaData'][] = $row;
}

// Xuất JSON ra cho DataTable
echo json_encode($output);
die();
