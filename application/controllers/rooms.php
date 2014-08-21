<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rooms extends AppController {

    public function __construct()
    {
        parent::__construct();
        isset($this->Hotel) or $this->load->model('Hotel');
        isset($this->Room) or $this->load->model('Room');
    }

    public function delete($room_id) {
    	$result = $this->Room->getById($room_id);
    	$hotels_id = $result['hotels_id'];
    	if ($room_id != null && is_numeric($room_id)) {
    		if($this->Room->delete($room_id)) {
    			$this->session->set_flashdata(App::MSG_SUCCESS, 'Eliminado correctamente!');
                redirect(site_url('hotels/add_2/'.$hotels_id));
    		} else {
    			$this->session->set_flashdata('Error', 'No fue posible eliminar el registro!');
                redirect(site_url('hotels/add_2/'.$hotels_id));
    		}
    	}
    }
}