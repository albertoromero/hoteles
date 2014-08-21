<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotels extends AppController {

    public function __construct()
    {
        parent::__construct();
        isset($this->Hotel) or $this->load->model('Hotel');
        isset($this->Room) or $this->load->model('Room');
    }

    public function index() {

        $this->title('Hoteles')
             ->set('hotels', $this->Hotel->getAll())
             ->display('hotels/index');
    }

    /**
     * Add hotel to database
     *
     */
    public function add_1()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Nombre', 'trim|required');
            $this->form_validation->set_rules('description', 'Descripcion', 'required');
            $this->form_validation->set_rules('address', 'Direccion', 'required');
            $this->form_validation->set_rules('checkin', 'Check-in', 'required|valid_date[Y-m-d]');
            $this->form_validation->set_rules('checkout', 'Check-out', 'required|valid_date[Y-m-d]');

            if ($this->form_validation->run()) {

                $name = $this->input->post('name');
                $description = $this->input->post('description');
                $address = $this->input->post('address');
                $checkin = $this->input->post('checkin');
                $checkout = $this->input->post('checkout');

                //Validar si la fecha de checkin es inferior al dia actual
                $hoy = date('Y-m-d');
                if(strcmp($checkin,$hoy) < 0) {
                    $this->form_validation->set_post_validation_error('error_checkin', 'Check-In tiene que ser una fecha actual.');
                } else if(strcmp($checkin,$checkout) > 0) {
                    $this->form_validation->set_post_validation_error('error_checkin', 'Check-In tiene que ser menor que Check-out.');
                } else {
                    $hotel = $this->Hotel->add(
                        array(
                            'name' => $name,
                            'description' => $description,
                            'address' => $address,
                            'checkin' => $checkin,
                            'checkout' => $checkout
                        )
                    );

                    if ($hotel && !empty($hotel)) {
                        $this->session->set_flashdata(App::MSG_SUCCESS, 'Registrado correctamente!');
                        redirect(site_url('hotels/add_2'));
                    }
                }
           }
        }
        $this->title('Hoteles')
             ->display('hotels/add_1');
    }

    /**
     * Add room to database
     *
     */
    public function add_2($hotels_id = null)
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Nombre', 'trim|required');
            $this->form_validation->set_rules('description', 'Descripcion', 'required');

            if ($this->form_validation->run()) {

                $name = $this->input->post('name');
                $description = $this->input->post('description');

                $room = $this->Room->add(
                    array(
                        'name' => $name,
                        'description' => $description,
                        'hotels_id' => $hotels_id
                    )
                );

                if ($room && !empty($room)) {
                    $this->session->set_userdata('lastRoomId', $room);
                    $this->session->set_flashdata(App::MSG_SUCCESS, 'Registrado correctamente!');
                    redirect(site_url('hotels/add_2/'.$hotels_id));
                }
           }
        }

        $this->title('Habitaciones')
             ->set('rooms', $this->Room->getAllBy('hotels_id', $hotels_id))
             ->set('hotel', $this->Hotel->getById($hotels_id))
             ->display('hotels/add_2');
    }

}
