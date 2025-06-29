<?php defined('BASEPATH') or exit('No direct script access allowed');

class Tour_templates extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tour_templates_model');
    }

    /**
     * Trang danh sách template
     */
    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('tour_templates');
        }

        $data['title'] = 'Quản lý mẫu tour';
        $this->load->view('admin/tour_templates/manage', $data);
    }

    /**
     * View table datatables
     */
    public function table()
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }

        $this->load->view('admin/tour_templates/table');
    }

    /**
     * Thêm / Cập nhật template
     */
    public function template($id = '')
    {
        if ($this->input->post()) {
            $data = [
                'name'         => $this->input->post('name'),
                'view_file'    => $this->input->post('view_file'),
                'html_content' => $this->input->post('html_content', false), // Không lọc HTML
                'description'  => $this->input->post('description'),
                'active'       => $this->input->post('active') ? 1 : 0,
            ];

            if ($id === '') {
                $insert_id = $this->tour_templates_model->add($data);
                set_alert('success', 'Thêm template thành công');
                redirect(admin_url('tour_templates/template/' . $insert_id));
            } else {
                $this->tour_templates_model->update($id, $data);
                set_alert('success', 'Cập nhật template thành công');
                redirect(admin_url('tour_templates/template/' . $id));
            }
        }

        if ($id != '') {
            $data['template'] = $this->tour_templates_model->get($id);
            if (!$data['template']) {
                blank_page('Không tìm thấy template', 'danger');
            }
        }

        $data['title'] = $id ? 'Cập nhật template' : 'Thêm template';
        $this->load->view('admin/tour_templates/template', $data);
    }

    /**
     * Xoá template
     */
    public function delete($id)
    {
        if (!$id) {
            redirect(admin_url('tour_templates'));
        }

        if ($this->tour_templates_model->delete($id)) {
            set_alert('success', 'Xoá thành công');
        } else {
            set_alert('warning', 'Không thể xoá mẫu này');
        }

        redirect(admin_url('tour_templates'));
    }
    public function ajax_get_template_content($id)
{
    if (!is_staff_member()) {
        ajax_access_denied();
    }

    $template = $this->tour_templates_model->get($id);

    if ($template) {
        echo json_encode([
            'success' => true,
            'content' => $template->html_content,
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Không tìm thấy mẫu',
        ]);
    }
}

}
