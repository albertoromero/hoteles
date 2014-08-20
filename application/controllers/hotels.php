<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotels extends AppController {

    public function __construct()
    {
        parent::__construct();
        isset($this->Hotel) or $this->load->model('Hotel');
    }

    /**
     * Add hotel to database
     *
     */
    public function add()
    {
        if ($this->input->post()) {

            $this->form_validation->set_rules('type', 'Tipo', 'is_natural_no_zero');
            $this->form_validation->set_rules('created', 'Fecha', 'required|valid_date[Y-m-d]');

            if ($this->form_validation->run()) {

                $description = $this->input->post('description');
                $type = $this->input->post('type');
                $time = $this->input->post('time');
                $created = $this->input->post('created');
                $modified = $created;
                $maxpulses = $this->input->post('maxpulses');
                $minpulses = $this->input->post('minpulses');
                $laps = $this->input->post('laps');
                $avgspeed = $this->input->post('avgspeed');
                $calories = $this->input->post('calories');
                $user_id = $this->getCurUser('id');

                $activity = $this->Activity->add(
                    array(
                        'description' => $description,
                        'type' => $type,
                        'time' => $time,
                        'created' => $created,
                        'modified' => $modified,
                        'maxpulses' => $maxpulses,
                        'minpulses' => $minpulses,
                        'laps' => $laps,
                        'avgspeed' => $avgspeed,
                        'calories' => $calories,
                        'user_id' => $user_id
                    )
                );
                if ($activity && !empty($activity)) {
                    $this->session->set_flashdata(App::MSG_SUCCESS, 'Registrado correctamente!');
                    redirect(site_url('hotels'));
                }
           }
        }
        $this->title('Nueva Actividad')
             ->display('hotels/add');
    }

}
