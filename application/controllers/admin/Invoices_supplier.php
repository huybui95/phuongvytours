<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Invoices_supplier extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoices_supplier_model');
        $this->load->model('credit_notes_model');
        $this->load->library('security');
    }

    /* Get all invoices_supplier in case user go on index page */
    public function index($id = '')
    {
        $this->list_invoices_supplier($id);
    }

    /* List all invoices_supplier datatables */
    public function list_invoices_supplier($id = '')
    {
        if (staff_cant('view', 'invoices_supplier')
            && staff_cant('view_own', 'invoices_supplier')
            && get_option('allow_staff_view_invoices_supplier_assigned') == '0') {
            access_denied('invoices_supplier');
        }

        close_setup_menu();

        $this->load->model('payment_modes_model');
        $data['payment_modes']        = $this->payment_modes_model->get('', [], true);
        $data['invoices_supplier_id']            = $id;
        $data['title']                = _l('invoices_supplier');
        $data['invoices_supplier_years']       = $this->invoices_supplier_model->get_invoices_supplier_years();
        $data['invoices_supplier_sale_agents'] = $this->invoices_supplier_model->get_sale_agents();
        $data['invoices_supplier_statuses']    = $this->invoices_supplier_model->get_statuses();
        $data['invoices_supplier_table'] = App_table::find('invoices_supplier');
        $data['dsinvoices_supplier'] = $this->invoices_supplier_model->dsinvoices_supplier();
        
        $this->load->view('admin/invoices_supplier/manage', $data);
    }

    /* List all recurring invoices_supplier */
    // public function recurring($id = '')
    // {
    //     if (staff_cant('view', 'invoices_supplier')
    //         && staff_cant('view_own', 'invoices_supplier')
    //         && get_option('allow_staff_view_invoices_supplier_assigned') == '0') {
    //         access_denied('invoices_supplier');
    //     }

    //     close_setup_menu();

    //     $data['invoiceid']            = $id;
    //     $data['title']                = _l('invoices_supplier_list_recurring');
    //     $data['invoices_supplier_years']       = $this->invoices_supplier_model->get_invoices_supplier_years();
    //     $data['invoices_supplier_sale_agents'] = $this->invoices_supplier_model->get_sale_agents();
    //     $this->load->view('admin/invoices_supplier/recurring/list', $data);
    // }

    public function table($supplierid = '')
    {
        if (staff_cant('view', 'invoices_supplier')
            && staff_cant('view_own', 'invoices_supplier')
            && get_option('allow_staff_view_invoices_supplier_assigned') == '0') {
            ajax_access_denied();
        }
        
        $this->load->model('payment_modes_model');
        $data['payment_modes'] = $this->payment_modes_model->get('', [], true);
            App_table::find('invoices_supplier')->output([
                'supplierid' => $supplierid,
                'data'     => $data,
            ]);
    }

    public function client_change_data($customer_id, $current_invoice = '')
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('projects_model');
            $data                     = [];
            $data['billing_shipping'] = $this->clients_model->get_customer_billing_and_shipping_details($customer_id);
            $data['client_currency']  = $this->clients_model->get_customer_default_currency($customer_id);

            $data['customer_has_projects'] = customer_has_projects($customer_id);
            $data['billable_tasks']        = $this->tasks_model->get_billable_tasks($customer_id);

            if ($current_invoice != '') {
                $this->db->select('status');
                $this->db->where('id', $current_invoice);
                $current_invoice_status = $this->db->get(db_prefix() . 'invoices_supplier')->row()->status;
            }

            $_data['invoices_supplier_to_merge'] = !isset($current_invoice_status) || (isset($current_invoice_status) && $current_invoice_status != Invoices_supplier_model::STATUS_CANCELLED) ? $this->invoices_supplier_model->check_for_merge_invoice($customer_id, $current_invoice) : [];

            $data['merge_info'] = $this->load->view('admin/invoices_supplier/merge_invoice', $_data, true);

            $this->load->model('currencies_model');

            $__data['expenses_to_bill'] = !isset($current_invoice_status) || (isset($current_invoice_status) && $current_invoice_status != Invoices_supplier_model::STATUS_CANCELLED) ? $this->invoices_supplier_model->get_expenses_to_bill($customer_id) : [];

            $data['expenses_bill_info'] = $this->load->view('admin/invoices_supplier/bill_expenses', $__data, true);
            echo json_encode($data);
        }
    }

    public function update_number_settings($id)
    {
        $response = [
            'success' => false,
            'message' => '',
        ];

        if (staff_can('edit',  'invoices_supplier')) {
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'invoices_supplier', [
                'prefix' => $this->input->post('prefix'),
            ]);
            
            if ($this->db->affected_rows() > 0) {
                $this->invoices_supplier_model->save_formatted_number($id);
               
                $response['success'] = true;
                $response['message'] = _l('updated_successfully', _l('invoice'));
            }
        }
        
        echo json_encode($response);
        die;
    }

    public function validate_invoice_number()
    {
        $isedit          = $this->input->post('isedit');
        $number          = $this->input->post('number');
        $date            = $this->input->post('date');
        $original_number = $this->input->post('original_number');
        $number          = trim($number);
        $number          = ltrim($number, '0');

        if ($isedit == 'true') {
            if ($number == $original_number) {
                echo json_encode(true);
                die;
            }
        }

        if (total_rows('invoices_supplier', [
            'YEAR(date)' => date('Y', strtotime(to_sql_date($date))),
            'number' => $number,
            'status !=' => Invoices_supplier_model::STATUS_DRAFT,
        ]) > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function add_note($rel_id)
    {
        if ($this->input->post() && user_can_view_invoice($rel_id)) {
            $this->misc_model->add_note($this->input->post(), 'invoice', $rel_id);
            echo $rel_id;
        }
    }

    public function get_notes($id)
    {
        if (user_can_view_invoice($id)) {
            $data['notes'] = $this->misc_model->get_notes($id, 'invoice');
            $this->load->view('admin/includes/sales_notes_template', $data);
        }
    }

    public function pause_overdue_reminders($id)
    {
        if (staff_can('edit',  'invoices_supplier')) {
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'invoices_supplier', ['cancel_overdue_reminders' => 1]);
        }
        redirect(admin_url('invoices_supplier/list_invoices_supplier/' . $id));
    }

    public function resume_overdue_reminders($id)
    {
        if (staff_can('edit',  'invoices_supplier')) {
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'invoices_supplier', ['cancel_overdue_reminders' => 0]);
        }
        redirect(admin_url('invoices_supplier/list_invoices_supplier/' . $id));
    }

    public function mark_as_cancelled($id)
    {
        if (staff_cant('edit', 'invoices_supplier') && staff_cant('create', 'invoices_supplier')) {
            access_denied('invoices_supplier');
        }

        $success = $this->invoices_supplier_model->mark_as_cancelled($id);

        if ($success) {
            set_alert('success', _l('invoice_marked_as_cancelled_successfully'));
        }

        redirect(admin_url('invoices_supplier/list_invoices_supplier/' . $id));
    }

    public function unmark_as_cancelled($id)
    {
        if (staff_cant('edit', 'invoices_supplier') && staff_cant('create', 'invoices_supplier')) {
            access_denied('invoices_supplier');
        }
        $success = $this->invoices_supplier_model->unmark_as_cancelled($id);
        if ($success) {
            set_alert('success', _l('invoice_unmarked_as_cancelled'));
        }
        redirect(admin_url('invoices_supplier/list_invoices_supplier/' . $id));
    }

    public function copy($id)
    {
        if (!$id) {
            redirect(admin_url('invoices_supplier'));
        }
        if (staff_cant('create', 'invoices_supplier')) {
            access_denied('invoices_supplier');
        }
        $new_id = $this->invoices_supplier_model->copy($id);
        if ($new_id) {
            set_alert('success', _l('invoice_copy_success'));
            redirect(admin_url('invoices_supplier/invoice/' . $new_id));
        } else {
            set_alert('success', _l('invoice_copy_fail'));
        }
        redirect(admin_url('invoices_supplier/invoice/' . $id));
    }

    public function get_merge_data($id)
    {
        $invoice = $this->invoices_supplier_model->get($id);
        $cf      = get_custom_fields('items');

        $i = 0;

        foreach ($invoice->items as $item) {
            $invoice->items[$i]['taxname']          = get_invoice_item_taxes($item['id']);
            $invoice->items[$i]['long_description'] = clear_textarea_breaks($item['long_description']);
            $this->db->where('item_id', $item['id']);
            $rel              = $this->db->get(db_prefix() . 'related_items')->result_array();
            $item_related_val = '';
            $rel_type         = '';
            foreach ($rel as $item_related) {
                $rel_type = $item_related['rel_type'];
                $item_related_val .= $item_related['rel_id'] . ',';
            }
            if ($item_related_val != '') {
                $item_related_val = substr($item_related_val, 0, -1);
            }
            $invoice->items[$i]['item_related_formatted_for_input'] = $item_related_val;
            $invoice->items[$i]['rel_type']                         = $rel_type;

            $invoice->items[$i]['custom_fields'] = [];

            foreach ($cf as $custom_field) {
                $custom_field['value']                 = get_custom_field_value($item['id'], $custom_field['id'], 'items');
                $invoice->items[$i]['custom_fields'][] = $custom_field;
            }
            $i++;
        }
        echo json_encode($invoice);
    }

    public function get_bill_expense_data($id)
    {
        $this->load->model('expenses_model');
        $expense = $this->expenses_model->get($id);

        $expense->qty              = 1;
        $expense->long_description = clear_textarea_breaks($expense->description);
        $expense->description      = $expense->name;
        $expense->rate             = $expense->amount;
        if ($expense->tax != 0) {
            $expense->taxname = [];
            array_push($expense->taxname, $expense->tax_name . '|' . $expense->taxrate);
        }
        if ($expense->tax2 != 0) {
            array_push($expense->taxname, $expense->tax_name2 . '|' . $expense->taxrate2);
        }
        echo json_encode($expense);
    }

    /* Add new invoice or update existing */
    public function invoice_supplier($id = '')
    {
        
        if ($this->input->post()) {
            $invoice_data = $this->input->post();      
            if ($id == '') {
               
                if (staff_cant('create', 'invoices_supplier')) {
                    access_denied('invoices_supplier');
                }               
                $id = $this->invoices_supplier_model->add($invoice_data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('invoice')));
                    redirect(admin_url('invoices_supplier'));

                    if (isset($invoice_data['save_and_record_payment'])) {
                        $this->session->set_userdata('record_payment', true);
                    } elseif (isset($invoice_data['save_and_send_later'])) {
                        $this->session->set_userdata('send_later', true);
                    }
                }
            } else {
                if (staff_cant('edit', 'invoices_supplier')) {
                    access_denied('invoices_supplier');
                }

                // If number not set, is draft
                if (hooks()->apply_filters('validate_invoice_number', true) && isset($invoice_data['number'])) {
                    $number = trim(ltrim($invoice_data['number'], '0'));
                    if (total_rows('invoices_supplier', [
                        'YEAR(date)' => (int) date('Y', strtotime(to_sql_date($invoice_data['date']))),
                        'number'     => $number,
                        'status !='  => Invoices_supplier_model::STATUS_DRAFT,
                        'id !='      => $id,
                    ])) {
                        set_alert('warning', _l('invoice_number_exists'));

                        redirect(admin_url('invoices_supplier'));
                    }
                }
                
                $success = $this->invoices_supplier_model->update($invoice_data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('invoice')));
                }

                redirect(admin_url('invoices_supplier'));
            }
        }
        
        if ($id == '') {
            $title                  = _l('create_new_invoice');
            $data['billable_tasks'] = [];
           
        } else {                  
            $invoices_supplier = $this->invoices_supplier_model->get($id);
            if (!$invoices_supplier || !user_can_view_invoice($id)) {
                blank_page(_l('invoice_not_found'));
            }

            $data['invoices_supplier_to_merge'] = $this->invoices_supplier_model->check_for_merge_invoice($invoices_supplier->supplierid, $invoices_supplier->id);
            $data['expenses_to_bill']  = $this->invoices_supplier_model->get_expenses_to_bill($invoices_supplier->supplierid);

            $data['invoices_supplier']        = $invoices_supplier;
            $data['edit']           = true;
            $data['billable_tasks'] = $this->tasks_model->get_billable_tasks($invoices_supplier->supplierid, !empty($invoices_supplier->project_id) ? $invoices_supplier->project_id : '');

            $title = _l('edit', _l('invoice')) . ' - ' . format_invoice_number($invoices_supplier->id);
        }

        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }

        $this->load->model('payment_modes_model');
        $data['payment_modes'] = $this->payment_modes_model->get('', [
            'expenses_only !=' => 1,
        ]);

        $this->load->model('taxes_model');
        $data['taxes'] = $this->taxes_model->get();
        $this->load->model('invoice_items_model');
        $this->load->model('supplier_model');

        $data['ajaxItems'] = false;
        if (total_rows(db_prefix() . 'items') <= ajax_on_total_items()) {
            $data['items'] = $this->invoice_items_model->get_grouped();
        } else {
            $data['items']     = [];
            $data['ajaxItems'] = true;
        }
        $data['items_groups'] = $this->invoice_items_model->get_groups();

        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();

        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $data['staff']     = $this->staff_model->get('', ['active' => 1]);
        $data['title']     = $title;
        $data['bodyclass'] = 'invoice';
         $ds_suppliers = $this->supplier_model->ds_suppliers();
        $data['ds_suppliers'] = $ds_suppliers;
        $this->load->view('admin/invoices_supplier/invoice_supplier', $data);
    }

    public function image() {
        if (!empty($_FILES['profile_boss_photo'])) {
            $file = $_FILES['profile_boss_photo'];
            $allowed_types = ['image/png', 'image/jpeg', 'image/gif'];
    
            if (in_array($file['type'], $allowed_types)) {
                // Define upload directory and ensure it exists
                $upload_path = FCPATH . 'uploads/invoice_supplier_images/';
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0755, true);
                }
    
                // Generate unique filename
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = uniqid('profile_') . '.' . $ext;
                $destination = $upload_path . $filename;
    
                // Move uploaded file to destination
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    // Generate URL for the uploaded image
                    $base_url = base_url();
                    $image_url = $base_url . 'uploads/invoice_supplier_images/' . $filename;
    
                    $response = [
                        'image_url' => $image_url,
                        'status' => 'success',
                        'csrfHash' => $this->security->get_csrf_hash()
                    ];
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($response));
                } else {
                    $this->output
                        ->set_status_header(500)
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'error' => 'Failed to save image',
                            'csrfHash' => $this->security->get_csrf_hash()
                        ]));
                }
            } else {
                $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'error' => 'Invalid image file',
                        'csrfHash' => $this->security->get_csrf_hash()
                    ]));
            }
        } else {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => 'No file uploaded',
                    'csrfHash' => $this->security->get_csrf_hash()
                ]));
        }
    }

    /* Get all invoice data used when user click on invoiec number in a datatable left side*/
    public function get_invoice_data_ajax($id)
    {
        if (staff_cant('view', 'invoices_supplier')
            && staff_cant('view_own', 'invoices_supplier')
            && get_option('allow_staff_view_invoices_supplier_assigned') == '0') {
            echo _l('access_denied');
            die;
        }

        if (!$id) {
            die(_l('invoice_not_found'));
        }

        $invoice = $this->invoices_supplier_model->get($id);

        if (!$invoice || !user_can_view_invoice($id)) {
            echo _l('invoice_not_found');
            die;
        }

        $template_name = 'invoice_send_to_customer';

        if ($invoice->sent == 1) {
            $template_name = 'invoice_send_to_customer_already_sent';
        }

        $data = prepare_mail_preview_data($template_name, $invoice->clientid);

        // Check for recorded payments
        $this->load->model('payments_model');
        $data['invoices_supplier_to_merge']          = $this->invoices_supplier_model->check_for_merge_invoice($invoice->clientid, $id);
        $data['members']                    = $this->staff_model->get('', ['active' => 1]);
        $data['payments']                   = $this->payments_model->get_invoice_payments($id);
        $data['activity']                   = $this->invoices_supplier_model->get_invoice_activity($id);
        $data['totalNotes']                 = total_rows(db_prefix() . 'notes', ['rel_id' => $id, 'rel_type' => 'invoice']);
        $data['invoice_recurring_invoices_supplier'] = $this->invoices_supplier_model->get_invoice_recurring_invoices_supplier($id);

        $data['applied_credits'] = $this->credit_notes_model->get_applied_invoice_credits($id);
        // This data is used only when credit can be applied to invoice
        if (credits_can_be_applied_to_invoice($invoice->status)) {
            $data['credits_available'] = $this->credit_notes_model->total_remaining_credits_by_customer($invoice->clientid);

            if ($data['credits_available'] > 0) {
                $data['open_credits'] = $this->credit_notes_model->get_open_credits($invoice->clientid);
            }

            $customer_currency = $this->clients_model->get_customer_default_currency($invoice->clientid);
            $this->load->model('currencies_model');

            if ($customer_currency != 0) {
                $data['customer_currency'] = $this->currencies_model->get($customer_currency);
            } else {
                $data['customer_currency'] = $this->currencies_model->get_base_currency();
            }
        }

        $data['invoice'] = $invoice;

        $data['record_payment'] = false;
        $data['send_later']     = false;

        if ($this->session->has_userdata('record_payment')) {
            $data['record_payment'] = true;
            $this->session->unset_userdata('record_payment');
        } elseif ($this->session->has_userdata('send_later')) {
            $data['send_later'] = true;
            $this->session->unset_userdata('send_later');
        }

        $this->load->view('admin/invoices_supplier/invoice_preview_template', $data);
    }

    public function apply_credits($invoice_id)
    {
        $total_credits_applied = 0;
        foreach ($this->input->post('amount') as $credit_id => $amount) {
            $success = $this->credit_notes_model->apply_credits($credit_id, [
            'invoice_id' => $invoice_id,
            'amount'     => $amount,
        ]);
            if ($success) {
                $total_credits_applied++;
            }
        }

        if ($total_credits_applied > 0) {
            update_invoice_status($invoice_id, true);
            set_alert('success', _l('invoice_credits_applied'));
        }
        redirect(admin_url('invoices_supplier/list_invoices_supplier/' . $invoice_id));
    }

    // public function get_invoices_supplier_total()
    // {
    //     if ($this->input->post()) {
    //         load_invoices_supplier_total_template();
    //     }
    // }

    /* Record new inoice payment view */
    public function record_invoice_payment_ajax($id)
    {
        $this->load->model('payment_modes_model');
        $this->load->model('payments_model');
        $data['payment_modes'] = $this->payment_modes_model->get('', [
            'expenses_only !=' => 1,
        ]);
        $data['invoice']  = $this->invoices_supplier_model->get($id);
        $data['payments'] = $this->payments_model->get_invoice_payments($id);
        $this->load->view('admin/invoices_supplier/record_payment_template', $data);
    }

    /* This is where invoice payment record $_POST data is send */
    public function record_payment()
    {
        if (staff_cant('create', 'payments')) {
            access_denied('Record Payment');
        }
        if ($this->input->post()) {
            $this->load->model('payments_model');
            $id = $this->payments_model->process_payment($this->input->post(), '');
            if ($id) {
                set_alert('success', _l('invoice_payment_recorded'));
                redirect(admin_url('payments/payment/' . $id));
            } else {
                set_alert('danger', _l('invoice_payment_record_failed'));
            }
            redirect(admin_url('invoices_supplier'));
        }
    }

    /* Send invoice to email */
    public function send_to_email($id)
    {
        $canView = user_can_view_invoice($id);
        if (!$canView) {
            access_denied('invoices_supplier');
        } else {
            if (staff_cant('view', 'invoices_supplier') && staff_cant('view_own', 'invoices_supplier') && $canView == false) {
                access_denied('invoices_supplier');
            }
        }

        try {
            $statementData = [];
            if ($this->input->post('attach_statement')) {
                $statementData['attach'] = true;
                $statementData['from']   = to_sql_date($this->input->post('statement_from'));
                $statementData['to']     = to_sql_date($this->input->post('statement_to'));
            }

            $success = $this->invoices_supplier_model->send_invoice_to_client(
                $id,
                '',
                $this->input->post('attach_pdf'),
                $this->input->post('cc'),
                false,
                $statementData
            );
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        // In case client use another language
        load_admin_language();
        if ($success) {
            set_alert('success', _l('invoice_sent_to_client_success'));
        } else {
            set_alert('danger', _l('invoice_sent_to_client_fail'));
        }
        redirect(admin_url('invoices_supplier'));
    }

    /* Delete invoice payment*/
    public function delete_payment($id, $invoiceid)
    {
        if (staff_cant('delete', 'payments')) {
            access_denied('payments');
        }
        $this->load->model('payments_model');
        if (!$id) {
            redirect(admin_url('payments'));
        }
        $response = $this->payments_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('payment')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('payment_lowercase')));
        }
        redirect(admin_url('invoices_supplier'));
    }

    /* Delete invoice */
    public function delete($id)
    {
        if (staff_cant('delete', 'invoices_supplier')) {
            access_denied('invoices_supplier');
        }
        if (!$id) {
            redirect(admin_url('invoices_supplier'));
        }
        $success = $this->invoices_supplier_model->delete($id);

        if ($success) {
            set_alert('success', _l('deleted', _l('invoice')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_lowercase')));
        }
        redirect(previous_url() ?: $_SERVER['HTTP_REFERER']);
    }

    public function delete_attachment($id)
    {
        $file = $this->misc_model->get_file($id);
        if ($file->staffid == get_staff_user_id() || is_admin()) {
            echo $this->invoices_supplier_model->delete_attachment($id);
        } else {
            header('HTTP/1.0 400 Bad error');
            echo _l('access_denied');
            die;
        }
    }

    /* Will send overdue notice to client */
    public function send_overdue_notice($id)
    {
        $canView = user_can_view_invoice($id);
        if (!$canView) {
            access_denied('invoices_supplier');
        } else {
            if (staff_cant('view', 'invoices_supplier') && staff_cant('view_own', 'invoices_supplier') && $canView == false) {
                access_denied('invoices_supplier');
            }
        }

        $send = $this->invoices_supplier_model->send_invoice_overdue_notice($id);
        if ($send) {
            set_alert('success', _l('invoice_overdue_reminder_sent'));
        } else {
            set_alert('warning', _l('invoice_reminder_send_problem'));
        }
        redirect(admin_url('invoices_supplier'));
    }

    /* Generates invoice PDF and senting to email of $send_to_email = true is passed */
    public function pdf($id)
    {
        if (!$id) {
            redirect(admin_url('invoices_supplier/list_invoices_supplier'));
        }

        $canView = user_can_view_invoice($id);
        if (!$canView) {
            access_denied('invoices_supplier');
        } else {
            if (staff_cant('view', 'invoices_supplier') && staff_cant('view_own', 'invoices_supplier') && $canView == false) {
                access_denied('invoices_supplier');
            }
        }

        $invoice        = $this->invoices_supplier_model->get($id);
        $invoice        = hooks()->apply_filters('before_admin_view_invoice_pdf', $invoice);
        $invoice_number = format_invoice_number($invoice->id);

        try {
            $pdf = invoice_pdf($invoice);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $type = 'D';

        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }

        if ($this->input->get('print')) {
            $type = 'I';
        }

        $pdf->Output(mb_strtoupper(slug_it($invoice_number)) . '.pdf', $type);
    }

    public function mark_as_sent($id)
    {
        if (!$id) {
            redirect(admin_url('invoices_supplier/list_invoices_supplier'));
        }
        if (!user_can_view_invoice($id)) {
            access_denied('Invoice Mark As Sent');
        }

        $success = $this->invoices_supplier_model->set_invoice_sent($id, true);

        if ($success) {
            set_alert('success', _l('invoice_marked_as_sent'));
        } else {
            set_alert('warning', _l('invoice_marked_as_sent_failed'));
        }

        redirect(admin_url('invoices_supplier'));
    }

    public function get_due_date()
    {
        if ($this->input->post()) {
            $date    = $this->input->post('date');
            $duedate = '';
            if (get_option('invoice_due_after') != 0) {
                $date    = to_sql_date($date);
                $d       = date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime($date)));
                $duedate = _d($d);
                echo $duedate;
            }
        }
    }
}
