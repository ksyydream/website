<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_house extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('house_model');
		$this->load->model('manage_model');
	}
	
	public function index() {
		$this->display('mobile/index.html');
	}
	
	public function view_list() {
		
		$region_list = $this->house_model->get_m_house_region();
		$this->assign('region_list', $region_list);
		
		$style_list = $this->house_model->get_search_style_list();
		$this->assign('style_list', $style_list);
		
		$house_list = $this->house_model->get_m_house_list();
		$this->assign('house_list', $house_list);
		
		$this->display('mobile/list.html');
	}
	
	public function view_detail($hid) {
		
		$house = $this->house_model->get_m_house_detail($hid);
		$house['unit_price'] = intval($house['total_price'] * 10000 / $house['acreage']);
		
		$house['house_pics_all'] = $this->house_model->get_second_hand_house_pics($hid);
		$house['house_pics'] = array_slice($house['house_pics_all'], 0, 5);
		$house['house_pics_rest'] = array_slice($house['house_pics_all'], 6, 5);
		$this->assign('house', $house);
		
		$this->display('mobile/details.html');
	}
	
	public function compare() {
		$this->display('mobile/contrast-tool.html');
	}
}