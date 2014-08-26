<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends AppController {

    public function __construct()
    {
        parent::__construct();
        isset($this->Hotel) or $this->load->model('Hotel');
        isset($this->Room) or $this->load->model('Room');
        isset($this->Condition) or $this->load->model('Condition');
        isset($this->Reserve) or $this->load->model('Reserve');
    }

    public function day() 
    {
    	if ($this->input->post()) {
            $this->form_validation->set_rules('day', 'Fecha', 'required|valid_date[Y-m-d]');

            if ($this->form_validation->run()) {
                $day = $this->input->post('day');
        	}

        } else {
        	$day = date('Y-m-d');
        }

    	$reserves = $this->Reserve->getAllBy('created', $day);
    	if ($reserves){
    		$reports = array();
    		foreach ($reserves as $key=>$reserve) {
    			$hotel = $this->Hotel->getById($reserve['hotels_id']);
    			$room = $this->Room->getById($reserve['rooms_id']);
    			$condition = $this->Condition->getById($reserve['conditions_id']);
    			$reports[$key]['hotel'] = $hotel['name'];
    			$reports[$key]['room'] = $room['name'];
    			$reports[$key]['condition'] = $room['description'];
    			$reports[$key]['checkin'] = $reserve['checkin'];
    			$reports[$key]['checkout'] = $reserve['checkout'];
    			$reports[$key]['price'] = $reserve['price'];
    			$reports[$key]['email'] = $reserve['client'];
    			$reports[$key]['date'] = $reserve['created'];
    		}
    		$this->set('reports', $reports);
    	} else {
            $this->form_validation->set_post_validation_error('error_day', $day . ' sin reservas.');
    	}

        $this->title('Relatório diario')
             ->display('reports/report');
    }

    public function week() 
    {
    	if ($this->input->post()) {
            $this->form_validation->set_rules('day', 'Fecha', 'required|valid_date[Y-m-d]');

            if ($this->form_validation->run()) {
                $day = $this->input->post('day');
                $date = explode('-', $day);
                $week = date("Y-m-d", mktime(0, 0, 0, $date[1], $date[2]+7, $date[0]));
        	}

        } else {
        	$day  = date('Y-m-d');
        	$week = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")+1,  date("Y")));
        }

        $string_query = "SELECT * FROM reserves WHERE created BETWEEN '".$day."' AND '".$week."'";
        $query = $this->db->query($string_query);
        $reserves = array();
    	foreach ($query->result_array() as $row) {
	        $reserves[] = $row;
    	}

    	if ($reserves){
    		$reports = array();
    		foreach ($reserves as $key=>$reserve) {
    			$hotel = $this->Hotel->getById($reserve['hotels_id']);
    			$room = $this->Room->getById($reserve['rooms_id']);
    			$condition = $this->Condition->getById($reserve['conditions_id']);
    			$reports[$key]['hotel'] = $hotel['name'];
    			$reports[$key]['room'] = $room['name'];
    			$reports[$key]['condition'] = $room['description'];
    			$reports[$key]['checkin'] = $reserve['checkin'];
    			$reports[$key]['checkout'] = $reserve['checkout'];
    			$reports[$key]['price'] = $reserve['price'];
    			$reports[$key]['email'] = $reserve['client'];
    			$reports[$key]['date'] = $reserve['created'];
    		}
    		$this->set('reports', $reports);
    	} else {
            $this->form_validation->set_post_validation_error('error_week', $day.' - '.$week.' sin reservas.');
    	}

        $this->title('Relatório senanal del '.$day.' al '.$week)
             ->display('reports/report');
    }

    public function best() 
    {
    	$string_query = "SELECT COUNT(*) AS total, h.name 
    		FROM reserves r
    		INNER JOIN hotels h ON h.id = r.hotels_id									
    		GROUP BY r.hotels_id";

    	$query = $this->db->query($string_query);

    	foreach ($query->result() as $row) {
	        $reports[] = $row;
    	}

	    $this->title('Relatório diario')
			 ->set('reports', $reports)
		     ->display('reports/best');
    }
}

/* End of file reports.php */
/* Location: ./application/controllers/reports.php */