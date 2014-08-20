<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel extends AppController {

    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->display('home/panel');
    }

}

/* End of file panel.php */
/* Location: ./application/controllers/panel.php */