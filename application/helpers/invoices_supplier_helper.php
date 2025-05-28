<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Get invoice short_url
 * @since  Version 2.7.3
 * @param  object $invoices_supplier
 * @return string Url
 */



function get_invoice_supplier_item_taxes($itemid)
{
    $CI = &get_instance();
    $CI->db->where('itemid', $itemid);
    $CI->db->where('rel_type', 'invoices_supplier');
    $taxes = $CI->db->get(db_prefix() . 'item_tax')->result_array();
    $i     = 0;
    foreach ($taxes as $tax) {
        $taxes[$i]['taxname'] = $tax['taxname'] . '|' . $tax['taxrate'];
        $i++;
    }

    return $taxes;
}

function format_invoice_supplier_number($id)
{
    $CI = &get_instance();

    if (!is_object($id)) {
        $CI->db->select('date,number,prefix,number_format,status')
            ->from(db_prefix() . 'invoices_supplier')
            ->where('id', $id);

        $invoices_supplier = $CI->db->get()->row();
    } else {
        $invoices_supplier = $id;

        $id = $invoices_supplier->id;
    }

    if (!$invoices_supplier) {
        return '';
    }

    return $invoices_supplier->prefix.''.$id;
}