<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reserves extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->twiggy->title()->append($this->config->item('site_description'));
        isset($this->Hotel) or $this->load->model('Hotel');
        isset($this->Room) or $this->load->model('Room');
        isset($this->Condition) or $this->load->model('Condition');
        isset($this->Reserve) or $this->load->model('Reserve');
    }

    public function add()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('qtd', 'Cantidad', 'required|is_natural_no_zero');
            $this->form_validation->set_rules('checkin', 'Check-in', 'required|valid_date[Y-m-d]');
            $this->form_validation->set_rules('checkout', 'Check-out', 'required|valid_date[Y-m-d]');

            if (!empty($this->input->post('email'))) {
                $this->complete_reserve($this->input->post());
            }

            if ($this->form_validation->run()) {
                $hotel = $this->Hotel->getById($this->input->post('hotels_id'));  
                $room  = $this->Room->getById($this->input->post('rooms_id'));  
                $condition  = $this->Condition->getById($this->input->post('conditions_id'));  

                $qtd = $this->input->post('qtd');
                $checkin = $this->input->post('checkin');
                $checkout = $this->input->post('checkout');
                $price = $this->input->post('qtd') * $this->input->post('price');

                //Validar si la fecha de checkin es inferior al dia actual
                $hoy = date('Y-m-d');
                if(strcmp($checkin,$hoy) < 0) {
                    $this->form_validation->set_post_validation_error('error_checkin', 'Check-In tiene que ser una fecha actual.');
                } else if(strcmp($checkin,$checkout) > 0) {
                    $this->form_validation->set_post_validation_error('error_checkin', 'Check-In tiene que ser menor que Check-out.');
                } else {
                    $reserva = array(
                        'qtd'       => $qtd,
                        'checkin'   => $checkin,
                        'checkout'  => $checkout,
                        'price'     => $price
                    );

                }
           }
            $this->title('Confirmar reserva')
                 ->set('reserva', $reserva)
                 ->set('room', $room)
                 ->set('hotel', $hotel)
                 ->set('condition', $condition)
                 ->display('reserves/add');
        } else {
            redirect(site_url('home'.$hotel));
        }
    }

    public function complete_reserve($data) {
        $reserve = $this->Reserve->add(
            array(
              'checkin'         => $data['checkin'], 
              'checkout'        => $data['checkout'],
              'price'           => $data['price'],
              'rooms_id'        => $data['rooms_id'],
              'conditions_id'   => $data['conditions_id'],
              'hotels_id'       => $data['hotels_id'],
              'client'          => $data['email'],
              'created'         => date('Y-m-d')
            )
        );

        if ($reserve && !empty($reserve)) {
            $this->session->set_flashdata(App::MSG_SUCCESS, 'Registrado correctamente!');
            redirect(site_url('home/index'));
        }
    }
}

/* End of file reserva.php */
/* Location: ./application/controllers/reserva.php */
