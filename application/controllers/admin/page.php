<?php defined('BASEPATH') or exit('No direct script access allowed');

class Page extends AppController
{
    public function index()
    {
        $this->output->set_status_header('404');
        $this->view('error_404');
    }

    public function view($page = null)
    {
        $twiggy = $this->config->item('twiggy');
        is_file(APPPATH.$twiggy['themes_base_dir'].'/'.$twiggy['default_theme'].'/pages/'.$page.$twiggy['template_file_ext']) or show_404();
        $this->display('pages/'.$page);
    }
}

/* End of file Page.php */
/* Location: ./application/modules/Page/controllers/Page.php */