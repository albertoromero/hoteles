<?php defined('BASEPATH') or exit('No direct script access allowed');

class Home extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->twiggy->title()->append($this->config->item('site_description'));
        isset($this->Hotel) or $this->load->model('Hotel');
        isset($this->Room) or $this->load->model('Room');
        isset($this->Condition) or $this->load->model('Condition');
        isset($this->Image) or $this->load->model('Image');
    }

    public function index()
    {
        $hotels = $this->Hotel->getAll();
        foreach ($hotels as $key=>$hotel) {
            $rooms = $this->Room->getAllBy('hotels_id', $hotel['id']);
            $hotels[$key]['rooms'] = $rooms;
            $image = $this->Image->getBy('rooms_id', $rooms[0]['id']);
            $hotels[$key]['image'] = site_url('upload/'.$rooms[0]['id'].'/'.$image['name']);
        }

        $this->title('Inicio')
             ->set('hotels', $hotels)
             ->display('home/index');
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
