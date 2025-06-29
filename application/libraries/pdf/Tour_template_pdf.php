<?php

defined('BASEPATH') or exit('No direct script access allowed');
include_once(__DIR__ . '/App_pdf.php');

class Tour_template_pdf extends App_pdf
{
    protected $html;
    protected $project;

    public function __construct($project, $html)
    {
        $this->project = $project;
        $this->html    = $this->fix_editor_html($html); // xử lý nội dung HTML TinyMCE

        parent::__construct();

        $this->SetTitle($this->project->name);
    }

    protected function type()
    {
        return 'tour_template';
    }

    protected function file_path()
    {
        return APPPATH . 'views/themes/perfex/views/tour_template_html.php';
    }

    public function prepare()
    {
        $this->set_view_vars('project', $this->project);
        $this->set_view_vars('html', $this->html);

        return $this->build(); // gọi file view + sinh PDF
    }
}