<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
* CommmonController
*/
class CommonController extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();

        isset($this->app) or $this->load->library('App');
        isset($this->twiggy) or $this->load->library('Twiggy');

        $this->twiggy->title($this->config->item('site_name'))->set_title_separator(' - ');

        $this->set('site_name', $this->config->item('site_name'), true);
        $this->set('site_version', $this->config->item('site_version'), true);
        $this->set('site_description', $this->config->item('site_description'), true);
        $this->set('current_page', strtolower($this->app->getCurrentPage()), true);

        log_message('debug', 'AppController Class Initialized');
    }

    protected function title($str = null)
    {
        $str = trim($str);
        $this->twiggy->title()->append($str);
        $this->set('page_title', $str);
        return $this;
    }

    protected function render($view)
    {
        $this->set('elapsed_time', $this->benchmark->elapsed_time());
        return $this->twiggy->render($view);
    }

    protected function display($view)
    {
        $this->output->set_output($this->render($view));
    }

    protected function set($key, $value = null, $global = false)
    {
        $this->twiggy->set($key, $value, $global);
        return $this;
    }

    protected function outputJson($data = array(), $cache = false)
    {
        return $this->app->outputJson($data, $cache);
    }
}


/**
 * AdminController Class
 *
 * Extends CommonController
 *
 */
class AppController extends CommonController 
{

}

class AdminController extends CommonController
{
    public function checkIsLoged() 
    {
        return $this->app->checkIsLoged();
        return true;
    }
}
/* End of file AppController.php */
/* Location: ./application/core/AppController.php */
