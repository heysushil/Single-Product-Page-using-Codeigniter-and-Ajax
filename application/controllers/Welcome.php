<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->helper('url','form');
	}

	public function index()
	{
		$data['result'] = $this->db->query("SELECT id,name,brand_name,price,qnt,(SELECT price*qnt) as total_product_qnt_price, (SELECT SUM(price*qnt) FROM cart) as total_amount FROM `cart`")->result_array();
		// echo '<pre>'; print_r($data);
		$this->load->view('index', $data);
	}

	public function qnt_form_data()
	{
		$id = $this->input->post('id');
		$qnt = $this->input->post('qnt');
		$new_qnt_value = $this->input->post('new_qnt_value');

		// Updated value on table
		$updateData = $this->db->update('cart', array('qnt'=>$new_qnt_value), array('id'=>$id));
		
		// Get new data form tabe
		$data['result'] = $this->db->query("SELECT id,name,brand_name,price,qnt,(SELECT price*qnt) as total_product_qnt_price, (SELECT SUM(price*qnt) FROM cart) as total_amount FROM `cart`")->result_array();

		// echo '<pre>'; print_r($data['result']);

		// $response = $data;
		echo json_encode($data);
	}

	public function delete_product()
	{
		$id = $this->input->post('id');
		$result = $this->db->delete('cart', array('id'=>$id));
		if($result === true){
			$response['success'] = '<p class="alert alert-success" style="width: 100%;">Product deleted successfully.</p>';
			echo json_encode($response);
		}else{
			$response['error'] = '<p class="alert alert-danger" style="width: 100%;">Problem on deleteing product. Try again.</p>';
			echo json_encode($response);
		}
	}

	public function add_new_product_form_data()
	{
		try{
			if($this->form_validation->run('add_new_product_form_data') === FALSE){
				$res['error'] = validation_errors();
				echo json_encode($res);
			}else{
				$formData['name'] = $this->input->post('name');
				$formData['brand_name'] = $this->input->post('brand_name');
				$formData['price'] = $this->input->post('price');
				$formData['qnt'] = $this->input->post('qnt');
				$result = $this->db->insert('cart',$formData);
				if($result === true){
					$res['success'] = '<p class="alert alert-success" style="width: 100%;">New product added successfully.</p>';
					echo json_encode($res);
				}
			}
		}catch(Exception $ex){
			echo $ex->getMessage();
		}
	}

	public function db()
	{
		$this->db->query("CREATE TABLE IF NOT EXISTS cart(
			id int(5) not null auto_increment primary key,
			name varchar(100) not null,
			brand_name varchar(100) not null,
			price int(5) not null,
			qnt int(4) not null			
		)");
	}
}

// name
// brand_name
// price
// qnt
// total_price
// sub_total
// fix_shipping
// grand_total
