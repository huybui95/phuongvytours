<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Notifications extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reminder_settings_model');
    }

    public function send_reminders()
    {
        $settings = $this->Reminder_settings_model->get_settings();

        $today = date('d/m');
        $clients = $this->db->get(db_prefix() . 'clients')->result();
        foreach ($clients as $client) {
            $email = $client->emails;

            // 1. Nhắc sinh nhật
            if (
                $settings['enable_birthday']
                && !empty($client->birthday)
                && $this->match_day_month($client->birthday, $today)
            ) {
                $this->send_email(
                    $email,
                    '🎉 Chúc mừng sinh nhật!',
                    'Chúc mừng sinh nhật ' . $client->company . '!'
                );
            }

            // 2. Nhắc tour sắp diễn ra
            if (
                $settings['enable_tour_reminder']
                && !empty($client->expected_time_on_tour)
                && $this->is_tour_reminder_due($client->expected_time_on_tour, $settings['tour_reminder_days'])
            ) {
                $this->send_email(
                    $email,
                    '🎒 Sắp tới ngày khởi hành!',
                    'Tour của bạn sẽ diễn ra vào ' . $client->expected_time_on_tour
                );
            }
        }
    }

    private function match_day_month($dateString, $today)
    {
        $date = DateTime::createFromFormat('d/m/Y', $dateString);
        return $date && $date->format('d/m') == $today;
    }

    private function is_tour_reminder_due($tourDateStr, $daysBefore)
    {
        $tourDate = DateTime::createFromFormat('d/m/Y', $tourDateStr);
        if (!$tourDate) return false;

        $reminderDate = clone $tourDate;
        $reminderDate->modify('-' . $daysBefore . ' days');
        return $reminderDate->format('Y-m-d') == date('Y-m-d');
    }

    private function send_email($to, $subject, $message)
    {
        $this->load->library('email');
        $this->email->from(get_option('smtp_email'), get_option('companyname'));
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        @$this->email->send();
    }
}
