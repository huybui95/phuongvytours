<?php

defined('BASEPATH') or exit('No direct script access allowed');

class suppliers extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('supplier_model');
    }

    /* List all suppliers */
    public function index()
    {
        close_setup_menu();

        if (!has_permission('suppliers', '', 'view') && !has_permission('suppliers', '', 'view_own')) {
            access_denied('suppliers');
        }
        $ds_suppliers = $this->supplier_model->ds_suppliers();

        // foreach ($ds_suppliers as $key => $item) {
        //     $arr_area = explode(',', $item['area_id']);
        //     $ds_suppliers[$key]['ds_suppliers'] = $this->supplier_model->get_areas_by_id($arr_area);
        // }
        $data['ds_suppliers'] = $ds_suppliers;
        $this->load->view('admin/suppliers/manage', $data);
    }
    public function supplier($id = '')
{
    if ($this->input->post()) {
        $data = [
            'name'             => $this->input->post('name'),
            'contact_person'   => $this->input->post('contact_person'),
            'typesupplier_id'  => $this->input->post('typesupplier_id'),
            'address'          => $this->input->post('address'),
            'city'             => $this->input->post('city'),
            'phone'            => $this->input->post('phone'),
            'position'         => $this->input->post('position'),
            'website'          => $this->input->post('website'),
            'link_image'       => $this->input->post('link_image'),
        ];

        if ($id == '') {
            if (!has_permission('suppliers', '', 'create')) {
                access_denied('suppliers');
            }

            $id = $this->supplier_model->add($data);

            if ($id) {
                handle_image_supplier_upload($id);
                set_alert('success', _l('added_successfully', _l('suppliers')));
                redirect(admin_url('suppliers'));
            }
        } else {
            if (!has_permission('suppliers', '', 'edit')) {
                access_denied('suppliers');
            }

            $success = $this->supplier_model->update($data, $id);
            handle_image_supplier_upload($id);
            if ($success) {
                set_alert('success', _l('updated_successfully', _l('suppliers')));
            }
            redirect(admin_url('suppliers'));
        }
        
    }
    
    if($id != '') {
            $data['supplier'] = $this->supplier_model->get($id, [], true);
    }
    $data['ds_typesuppliers'] = $this->supplier_model->ds_typesuppliers();

    $this->load->view('admin/suppliers/suppliers', $data);
}

    public function delete($id)
    {
        // var_dump('aaaa');
        $response = $this->supplier_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('suppliers')));
            redirect(admin_url('suppliers'));
        } else {
            set_alert('warning', _l('problem_deleting', _l('suppliers')));
        }
    }
    
    public function typesuppliers() {
        $this->load->model('supplier_model');
        $ds_typesuppliers = $this->supplier_model->ds_typesuppliers();
        $data['ds_typesuppliers'] = $ds_typesuppliers;
        $this->load->view('admin/suppliers/typesuppliers/manage',$data);
        
    }
    public function debts() {
        $this->load->model('supplier_model');
        $ds_debts = $this->supplier_model->ds_debts();
        $data['ds_debts'] = $ds_debts;
        $this->load->view('admin/suppliers/debts/manage',$data);
        
    }

    public function debt_supplier($id = '')
    {
        $this->load->model('supplier_model');
        $staff_id = get_staff_user_id();
        if ($this->input->post()) {
            $data = [
                'supplier_id'     => $this->input->post('supplier_id'),
                'invoice_number'  => $this->input->post('invoice_number'),
                'invoice_date'    => $this->input->post('invoice_date'),
                'due_date'        => $this->input->post('due_date'),
                'amount'          => $this->input->post('amount'),
                'paid_amount'     => $this->input->post('paid_amount'),
                'remaining_amount'=> $this->input->post('remaining_amount'),
                'created_by'=>  $staff_id,
                'status'          => $this->input->post('status'),
                'note'            => $this->input->post('note'),
            ];
            // var_dump($data);
            // die();
            if ($id == '') {
                if (!has_permission('debts', '', 'create')) {
                    access_denied('suppliers/debts');
                }

                $id = $this->supplier_model->add_debts($data);

                if ($id) {
                    handle_image_supplier_debt($id);
                    set_alert('success', _l('added_successfully', _l('debts_add_new')));
                    redirect(admin_url('suppliers/debts'));
                }
            } else {
                if (!has_permission('debts', '', 'edit')) {
                    access_denied('debts');
                }

                $success = $this->supplier_model->update_debts($data, $id);

                if ($success) {
                    handle_image_supplier_debt($id);
                    set_alert('success', _l('updated_successfully', _l('debts_edit')));
                }
                set_alert('success', _l('updated_successfully', _l('debts_edit')));
                redirect(admin_url('suppliers/debts'));
            }
        }
        $this->load->model('invoices_supplier_model');
    if ($id != '') {
        $data['debt'] = $this->supplier_model->get_debts($id);
        $data['invoices_suppliers'] = $this->supplier_model->get_invoices_suppliers($id);
    }
    
    $data['suppliers'] = $this->supplier_model->ds_suppliers();

    $this->load->view('admin/suppliers/debts/supplier', $data);
    }

    public function delete_debt($id)
    {
        // var_dump('aaaa');
        $response = $this->supplier_model->delete_debt($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('invoices_supplier_overdue')));
            redirect(admin_url('suppliers/debts'));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoices_supplier_overdue')));
        }
    }

    public function get_invoices_by_supplier()
    {
        $supplier_id = $this->input->post('supplier_id');
        if (!$supplier_id) {
            echo json_encode([]);
            return;
        }

        $this->load->model('supplier_model');
        $invoices = $this->supplier_model->get_invoices_by_supplier($supplier_id);
        echo json_encode($invoices);
    }

    public function get_invoices_info() {
        $invoice_ids = $this->input->post('invoice_ids');
        $this->load->model('Supplier_model');
        $invoices = $this->Supplier_model->get_invoices_by_ids($invoice_ids);
    
        echo json_encode($invoices);
    }


    public function suppliers() {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($this->input->post('id')) {
                // Edit
                $id = $data['id'];
                unset($data['id']);
                
                $success = $this->supplier_model->update_typesuppliers($data, $id);
                $message = $success ? _l('update_succes_supplier') : _l('update_error_supplier');
            } else {
                unset($data['id']);
                
                // Chuyển đổi thành chữ thường
                $slug = mb_strtolower($data['name'],'UTF-8');
                $diacritics = array(
                    'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
                    'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
                    'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
                    'đ' => 'd',
                    'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
                    'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
                    'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
                    'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
                    'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
                    'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
                    'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
                    'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
                    'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
                );
                
                // Thay thế các ký tự có dấu trong chuỗi
                $slug = str_replace(array_keys($diacritics), array_values($diacritics), $slug);
                // Thay thế khoảng trắng bằng dấu gạch nối
                $slug = preg_replace('~[^\p{L}\p{N}]+~u', '-', $slug);
                
                // Cắt bỏ dấu gạch nối ở đầu và cuối, giảm nhiều dấu gạch nối thành một
                $slug = preg_replace('~-+~', '-', trim($slug, '-'));
                $data['slug']=$slug;
                // var_dump($data);
                // die();
                $success = $this->supplier_model->add_typesuppliers($data);
                $message = $success ? _l('new_succes_supplier') : _l('new_error_supplier');
            }
            set_alert($success ? 'success' : 'danger', $message);
            redirect(admin_url('suppliers/typesuppliers'));
        }
    }
    public function get_typesuppliers($id) {
        if ($this->input->is_ajax_request()) {
            $numbererror = $this->supplier_model->get_typesuppliers($id);
            echo json_encode($numbererror);
        } else {
            show_404();
        }
    }
    public function delete_typesuppliers($id)
    {
        // var_dump($id);
        // if (!$id) {
        //     redirect(admin_url('electricitywaters/numbererrors'));
        // }
        $response = $this->supplier_model->delete_typesuppliers($id);
        if ($response == true) {
            set_alert('success', _l('deleted','groups_supplier'));
        } else {
            set_alert('warning', _l('problem_deleting', 'groups_supplier'));
        }
        redirect(admin_url('suppliers/typesuppliers'));
    }
}
