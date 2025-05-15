<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reminder_settings_model extends App_Model
{
    private $table = 'tblreminder_settings';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Lấy cài đặt lịch nhắc
     */
    public function get_settings()
    {
        $settings = $this->db->get($this->table)->row();

        // Nếu chưa có, tạo mặc định
        if (!$settings) {
            $default = [
                'enable_birthday'      => 1,
                'enable_tour_reminder' => 1,
                'tour_reminder_days'   => 3,
            ];
            $this->db->insert($this->table, $default);
            return $default;
        }

        return [
            'enable_birthday'      => (int)$settings->enable_birthday,
            'enable_tour_reminder' => (int)$settings->enable_tour_reminder,
            'tour_reminder_days'   => (int)$settings->tour_reminder_days,
        ];
    }

    /**
     * Lưu cài đặt lịch nhắc
     */
    public function save_settings($data)
    {
        if ($this->db->count_all($this->table) == 0) {
            return $this->db->insert($this->table, $data);
        }
        return $this->db->update($this->table, $data);
    }
}
