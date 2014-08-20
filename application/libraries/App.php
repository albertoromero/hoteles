<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * App
 *
 * Class com métodos auxiliares da Aplicação
 *
 * @package     App
 * @author      Rodolfo Silva <contato@rodolfosilva.com>
 * @copyright   Copyright (c) 2014.
 * @since       Version 1.0
 */
class App
{
    /**
     * Nome da sessão para armazenar os dados do usuário logado
     */
    const SESSION_NAME_AUTH = 'user_logged';
    const MSG_SUCCESS = 'Exito';

    /**
     * Açoes que podem ser executadas na view
     */
    private $__acoes = array();

    /**
     * Construtor
     */
    public function __construct($config = array())
    {
        log_message('debug', 'APP Class Initialized');

        // Verifica se a class session foi carregada se não carrega no mesmo momento
        isset(app()->session) or app()->load->library('session');

        // Verifica se alguma configuração foi passada e chama o método initialize
        empty($config) or $this->initialize($config);
    }

    /**
     * Inicialização da biblioteca
     *
     * @param array $option Configurações da biblioteca
     */
    public function initialize(array $config)
    {
    }

    /**
     * Retorna o controller e o método atual
     *
     * @return string Rota atual ex: controller/action
     */
    public function getCurrentPage()
    {
        return $this->getCurrentController() . '/' . $this->getCurrentAction();
    }

    /**
     * Retorna o controller atual
     *
     * @return string Nome do controller atual
     */
    public function getCurrentController()
    {
        return app()->router->fetch_class();
    }

    /**
     * Retorna o método(ação) atual
     *
     * @return string Nome do método atual
     */
    public function getCurrentAction()
    {
        return app()->router->fetch_method();
    }

    /**
     * Converte um array em uma lista para utilizalo em um select
     *
     * @param array $array Array que sera convertido
     * @param string $key Campo do array para ser a chave
     * @param string $value Campo do array para ser o valor
     * @param string $label_firts Primeiro valor a ser listado
     * @return array Retorna um array com a lista
     */
    public function arrayToList(array $array, $key, $value, $label_firts = false)
    {
        $rtn = array();
        if ($label_firts !== false) {
            $rtn = array(null => $label_firts);
        }
        foreach ($array as $ar) {
            $rtn[$ar[$key]] = $ar[$value];
        }
        return $rtn;
    }

    /**
     * Responde a uma requisição com os dados em JSON
     *
     * @param array $data Array com os dados para serem enviados
     * @param boolean $cache Define se a resposta deve ser armazenada no cache
     */
    public function outputJson($data = array(), $cache = false)
    {
        app()->output->set_content_type('application/json');
        if ($cache) {
            app()->output->cache(24*60*60);
        }
        $data = is_array($data) ? json_encode($data) : $data;
        return app()->output->set_output($data);
    }


    /**
     * Verifica se usuário esta logado
     *
     * Verifica se o usuário esta logado, caso são esteja ele é redirecionado
     * para página de login.
     */
    public function checkIsLoged()
    {
        if (!$this->getCurUser()) {
            if (app()->input->is_ajax_request()) {
                echo json_encode(
                    array(
                        'error' => 'A sua sessão expirou. Faça login novamente.',
                        'login' => true
                    )
                );
                exit;
            } else {
                redirect('login');
            }
        }
    }

    /**
     * Recuperar dados do usuário logado
     *
     * @param string $key Nome do campo a ter o valor retornado
     * @return mixed Retorna os dados do usuário caso esteja logado do contrario retorna false
     */
    public function getCurUser($key = false)
    {
        if ($dados = app()->session->userdata(self::SESSION_NAME_AUTH)) {
            if ($key === false) {
                return $dados;
            } elseif (isset($dados[$key])) {
                return $dados[$key];
            }
        }
        return false;
    }

    /**
     * Define dados do usuário logado na sessão
     *
     * @param array $data Array com os dados do usuário logado
     */
    public function setCurUser(array $data)
    {
        if (!empty($data)) {
            app()->session->set_userdata(self::SESSION_NAME_AUTH, $data);
        }
    }

    /**
     * Destroi a sessão atual do usuário logado
     */
    public function destroyCurUser()
    {
        app()->session->unset_userdata(self::SESSION_NAME_AUTH);
    }

    /**
     * Altera os valores da sessão do usuário logado
     *
     * @param string $key Nome do campo a ter o valor alterado
     * @param mixed $value dados que serão armazenados.
     */

    public function changeValueCurUser($key, $value)
    {
        $current = $this->getCurUser();
        if ($current && is_array($current) && !empty($key) && $key != 'id') {
            $current[$key] = $value;
            $this->setCurUser($current);
        }
    }
}

/* End of file App.php */
/* Location: ./application/libraries/App.php */
