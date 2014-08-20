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


    /**
     * Make Login
     *
     * @param string username
     * @param string password
     * @return boolean
     */
    private function doLogin($username, $password)
    {
        if ($data = $this->User->checkUser($username, $password)) {
            return $this->setSession($data);
        }
        return false;
    }

    public function login()
    {
        if ($this->getCurUser()) {
            if ($this->input->is_ajax_request()) {
                $msg['success'] = 'Espere...';
                $msg['redirect'] = site_url('/panel');
                $this->outputJson($msg);
            } else {
                redirect('/panel');
            }
        }

        if ($this->input->post()) {
            $username = trim($this->input->post('username'));
            $password = trim($this->input->post('password'));

            $this->form_validation->set_rules('username', 'Usuario', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');

            if ($this->form_validation->run()) {
                $username = trim($this->input->post('username'));
                $password = trim($this->input->post('password'));

                if (!$this->doLogin($username, $password)) {
                    //$this->session->set_flashdata('error_login', 'Login y/o password invalidos.');
                    $this->form_validation->set_post_validation_error('error_login', 'Login y/o password invalidos.');
                } else {
                    redirect('/panel');
                }
            }
        }
        $this->display('users/login');
    }

    
    /**
     * Logoff a user
     *
     */
    public function logout()
    {
        if (!$this->getCurUser()) {
            redirect('/');
        }
        $this->session->sess_destroy();
        redirect('/');
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
                        redirect('/panel');
                    }
                }
           }
        }
        $this->title('Registro')->display('users/add');
    }

    /**
     * Set the Session
     *
     * @param array data
     */
    private function setSession(array $data)
    {
        if (!empty($data) && is_array($data) && isset($data['id'])) {
            $this->session->set_userdata(array('user_logged' => $data));
            return true;
        }
        return false;
    }

}


/* End of file Users.php */
/* Location: ./application/controllers/Users.php */
