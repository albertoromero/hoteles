<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * UsersController
 *
 * @package    CodeIgniter
 */
class Users extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->twiggy->title()->append($this->config->item('site_description'));
        isset($this->User) or $this->load->model('User');
    }

    public function index()
    {
        $this->checkIsLoged();
        $this->display('Users/index');
    }

    public function login()
    {
        $this->display('users/login');
    }

    /**
     * Add users to database
     *
     */
    public function add()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('username', 'Usuario', 'trim|required|alpha|max_length[20]|is_unique[users.username]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[32]|matches[repassword]');
            $this->form_validation->set_rules('repassword', 'Confirmacion Password', 'trim|required|matches[password]');

            if ($this->form_validation->run()) {
                $email = trim($this->input->post('email'));
                $username = trim($this->input->post('username'));
                $password = trim($this->input->post('password'));

                $user = $this->User->add(
                    array(
                        'email' => $email,
                        'username' => $username,
                        'password' => $this->User->encript($password),
                    )
                );
                if ($user && !empty($user)) {
                    $this->session->set_flashdata(App::MSG_SUCCESS, 'Registrado correctamente!');
                    if ($this->doLogin($username, $password)) {
                        redirect('/painel');
                    }
                }
           }
        }
        $this->title('Registro')->display('users/add');
    }

}


/* End of file Users.php */
/* Location: ./application/controllers/Users.php */
