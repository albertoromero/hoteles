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
        isset($this->Hotel) or $this->load->model('Hotel');
    }

    public function index()
    {
        $this->title('Hoteles')
             ->set('hotels', $this->Hotel->getAll())
             ->display('hotels/index');
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
