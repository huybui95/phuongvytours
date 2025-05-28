<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('get_typesupplier_name')) {
    /**
     * Lấy tên nhóm nhà cung cấp từ ID
     *
     * @param int $id
     * @return string
     */
    function get_typesupplier_name($id)
    {
        $CI =& get_instance();
        $CI->load->model('supplier_model');

        $typesupplier = $CI->supplier_model->get_typesupplier_by_id($id);

        return $typesupplier ? $typesupplier['name'] : '';
    }
    function get_supplier_name($id)
    {
        $CI =& get_instance();
        $CI->load->model('supplier_model');

        $typesupplier = $CI->supplier_model->get_supplier_by_id($id);

        return $typesupplier ? $typesupplier['name'] : '';
    }
}
