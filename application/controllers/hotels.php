<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotels extends AppController {

    public function __construct()
    {
        parent::__construct();
        isset($this->Hotel) or $this->load->model('Hotel');
        isset($this->Room) or $this->load->model('Room');
        isset($this->Condition) or $this->load->model('Condition');
        isset($this->Image) or $this->load->model('Image');
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
    public function add()
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
                        redirect(site_url('rooms/add/'.$hotel));
                    }
                }
           }
        }
        $this->title('Hoteles')
             ->display('hotels/add');
    }

    /**
     * Edit Hotel
     *
     */
    public function edit($id)
    {
        try {
            if (empty($id)) {
                throw new Exception('Error ID');
            }

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
                        $data = array(
                            'name' => $name,
                            'description' => $description,
                            'address' => $address,
                            'checkin' => $checkin,
                            'checkout' => $checkout
                        );

                        if ($this->Hotel->edit($data, $id)) {
                            $this->session->set_flashdata(App::MSG_SUCCESS, 'Editado correctamente!');
                            redirect(site_url('hotels'));
                        } else {
                            $this->form_validation->set_post_validation_error('errror_save', 'No fue posible editar.');
                        }

                    }
               }
            }

            $this->title('Hoteles')
                 ->set('hotel', $this->Hotel->getById($id))
                 ->display('hotels/edit');
        } catch (Exception $e) {
            show_404();
        }        
    }


    public function delete($hotel_id) {
        $result = $this->Hotel->getById($hotel_id);
        if ($hotel_id != null && is_numeric($hotel_id)) {
            if($this->Hotel->delete($hotel_id)) {
                $this->session->set_flashdata(App::MSG_SUCCESS, 'Eliminado correctamente!');
                redirect(site_url('hotels'));
            } else {
                $this->session->set_flashdata('Error', 'No fue posible eliminar el registro!');
                redirect(site_url('hotels'));
            }
        }
    }

    public function info($id) {
        $rooms = $this->Room->getAllBy('hotels_id', $id);
        $hotel =  $this->Hotel->getById($id);
        $images = array();
        foreach ($rooms as $key=>$room) {
            $images_aux = array();
            $conditions = $this->Condition->getAllBy('rooms_id', $room['id']);
            $rooms[$key]['conditions'] = $conditions;
            $image = $this->Image->getAllBy('rooms_id', $room['id']);
            $images_aux[] = $image;
            foreach ($images_aux as $image) {
                foreach ($image as $photo) {
                    $images[] = site_url('upload/'.$room['id'].'/'.$photo['name']);;
                }  
            }
            $rooms[$key]['image'] = site_url('upload/'.$room['id'].'/'.$image[0]['name']);
        }
        $this->title('Habitaciones')
             ->set('images', $images)
             ->set('rooms', $rooms)
             ->set('hotel', $this->Hotel->getById($id))
             ->display('hotels/info');    
    }

}
