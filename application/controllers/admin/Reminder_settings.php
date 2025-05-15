<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reminder_settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reminder_settings_model');
    }

    public function index()
    {
        if ($this->input->post()) {
            $data = [
                'enable_birthday' => $this->input->post('enable_birthday') ? 1 : 0,
                'enable_tour_reminder' => $this->input->post('enable_tour_reminder') ? 1 : 0,
                'tour_reminder_days' => (int) $this->input->post('tour_reminder_days'),
            ];
            $this->Reminder_settings_model->save_settings($data);
            set_alert('success', 'Cập nhật cài đặt thành công');
            redirect(admin_url('reminder_settings'));
        }

        $data['settings'] = $this->Reminder_settings_model->get_settings();
        $data['title']    = 'Cài đặt lịch nhắc tự động';
        $this->load->view('admin/reminder_settings/manage', $data);
    }
}
