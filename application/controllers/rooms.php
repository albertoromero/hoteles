<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rooms extends AppController {

    public function __construct()
    {
        parent::__construct();
        isset($this->Hotel) or $this->load->model('Hotel');
        isset($this->Room) or $this->load->model('Room');
        isset($this->Condition) or $this->load->model('Condition');
        isset($this->Image) or $this->load->model('Image');
    }


    /**
     * Add room to database
     *
     */
    public function add($hotels_id = null)
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Nombre', 'trim|required|is_unique[rooms.name]');
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

	                //Create Path to upload images
	             	$folderName = $room;
				    $pathToUpload = './upload/' . $folderName;
				    if ( ! file_exists($pathToUpload) )
				    {
				        mkdir($pathToUpload . '/thumbs', 0777, TRUE);
				    }

                    $this->session->set_flashdata(App::MSG_SUCCESS, 'Registrado correctamente!');
                    redirect(site_url('rooms/add/'.$hotels_id));
                }

           }
        }

        $this->title('Habitaciones')
             ->set('rooms', $this->Room->getAllBy('hotels_id', $hotels_id))
             ->set('hotel', $this->Hotel->getById($hotels_id))
             ->display('rooms/add');
    }


    /**
     * Add rooms conditions
     *
     */
    public function conditions($rooms_id = null)
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('description', 'Descripcion', 'required');
            $this->form_validation->set_rules('extra', 'Extra', 'required');
            $this->form_validation->set_rules('maxperson', 'Max. Personas', 'is_natural_no_zero');
            $this->form_validation->set_rules('qtd', 'Cantidad', 'is_natural_no_zero');
            $this->form_validation->set_rules('price', 'price', 'required|is_numeric');

            if ($this->form_validation->run()) {
	            $description = $this->input->post('description');
	            $extra = $this->input->post('extra');
	            $maxperson = $this->input->post('maxperson');
	            $qtd = $this->input->post('qtd');
	            $price = $this->input->post('price');


                $condition = $this->Condition->add(
                    array(
			            'description' => $description,
			            'extra' 	  => $extra,
			            'maxperson'   => $maxperson,
			            'qtd' 		  => $qtd,
			            'price' 	  => $price,
                        'rooms_id'    => $rooms_id
                    )
                );

                if ($condition && !empty($condition)) {
                    $this->session->set_flashdata(App::MSG_SUCCESS, 'Registrado correctamente!');
                    redirect(site_url('rooms/conditions/'.$rooms_id));
                }
           }
        }

        $this->title('Detalles Habitacion')
             ->set('room', $this->Room->getById($rooms_id))
             ->set('conditions', $this->Condition->getAllBy('rooms_id', $rooms_id))
             ->display('rooms/conditions');
    }

    public function edit($id)
    {
        try {
            if (empty($id)) {
                throw new Exception('Error ID');
            }

            if ($this->input->post()) {
	            $this->form_validation->set_rules('name', 'Nombre', 'trim|required');
	            $this->form_validation->set_rules('description', 'Descripcion', 'required');

	            if ($this->form_validation->run()) {

	                $name = $this->input->post('name');
	                $description = $this->input->post('description');
	                $hotels_id = $this->input->post('hotels_id');

	                $data = array(
	                    'name' => $name,
	                    'description' => $description,
	                    'hotels_id' => $hotels_id
                    );

                    if ($this->Room->edit($data, $id)) {
                        $this->session->set_flashdata(App::MSG_SUCCESS, 'Editado correctamente!');
                        redirect(site_url('rooms/add/'.$hotels_id));
                    } else {
                        $this->form_validation->set_post_validation_error('errror_save', 'No fue posible editar.');
                    }
	           }
            }

	        $this->title('Editar Habitacion')
	             ->set('room', $this->Room->getById($id))
	             ->display('rooms/edit');
        } catch (Exception $e) {
            show_404();
        }        
    }

    public function delete($room_id) {
    	$result = $this->Room->getById($room_id);
    	$hotels_id = $result['hotels_id'];
    	if ($room_id != null && is_numeric($room_id)) {
    		if($this->Room->delete($room_id)) {
    				$folderName = $room_id;
				    $pathToUpload = FCPATH . 'upload/'. $folderName;
				    if ( ! file_exists($pathToUpload) )
				    {
				    	//@TODO: unlink for delete images
				        rmdir($pathToUpload . '/thumbs');
				    }
    			$this->session->set_flashdata(App::MSG_SUCCESS, 'Eliminado correctamente!');
                redirect(site_url('rooms/add/'.$hotels_id));
    		} else {
    			$this->session->set_flashdata('Error', 'No fue posible eliminar el registro!');
                redirect(site_url('rooms/add/'.$hotels_id));
    		}
    	}
    }

    public function delete_conditions($id) {
    	$result = $this->Room->getById($id);
    	$room_id = $result['id'];
    	if ($id != null && is_numeric($id)) {
    		if($this->Condition->delete($id)) {
    			$this->session->set_flashdata(App::MSG_SUCCESS, 'Eliminado correctamente!');
                redirect(site_url('rooms/conditions/'.$room_id));
    		} else {
    			$this->session->set_flashdata('Error', 'No fue posible eliminar el registro!');
                redirect(site_url('rooms/conditions/'.$room_id));
    		}
    	}
    }


    public function upload_foto($room_id) {
        $config['upload_path'] = FCPATH . 'upload/'.$room_id;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '0';
        $config['max_width'] = '0';
        $config['max_height'] = '0';
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $this->session->set_flashdata('Error', 'No fue posible incluir la foto!');
            redirect('/rooms/images/'.$room_id);
        } else {
            $data = array('upload_data' => $this->upload->data());
            return $data['upload_data']['file_name'];
        }
    }

    public function images($rooms_id) {
    	if (isset($_FILES['userfile'])) {
	    	$foto = $this->upload_foto($rooms_id);
	    	if (!empty($foto)) {
                $image = $this->Image->add(
                    array(
                        'name' => $foto,
                        'rooms_id' => $rooms_id
                    )
                );
                if ($image && !empty($image)) {
                    $this->session->set_flashdata(App::MSG_SUCCESS, 'Registrado correctamente!');
                    redirect(site_url('/rooms/images/'.$rooms_id));
                }
	    	}
    	}

		$this->title('Imagenes')
			 ->set('path',  site_url('upload/'.$rooms_id))
			 ->set('images', $this->Image->getAllBy('rooms_id', $rooms_id))
             ->set('room', $this->Room->getById($rooms_id))
             ->display('rooms/image');    	
    }

}