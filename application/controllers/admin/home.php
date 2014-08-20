<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HomeController
 *
 * @package    CodeIgniter
 */
class Home extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->twiggy->title()->append($this->config->item('site_description'));
        $this->checkIsLoged();
    }

    public function index()
    {
        $this->display('home/index');
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
