<?php defined('BASEPATH') or exit('No direct script access allowed');

class AppExceptions extends CI_Exceptions
{
    public function show_404($page='')
    {
        $this->config =& get_config();
        header('Location: '. $this->config['base_url'] . ltrim($this->router->directory, '/') .'page/error_404');
        exit;
    }
}

/* End of file AppExceptions.php */
/* Location: ./application/core/AppExceptions.php */