<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 房源列表页/详情页控制器
 *
 * @package		app
 * @subpackage	core
 * @category	controller
 * @author		bin.shen
 *
 */
class House extends MY_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('house_model');
	}
	
	public function index() {
		
	}
	
	public function new_house_list() {
		
		$search_region_list = $this->house_model->get_search_region_list();
		
		//$search_region_list = $this->house_model->get_room_news();
		$this->assign('search_region_list', $search_region_list);
		
		$search_style_list = $this->house_model->get_search_style_list();
		$this->assign('search_style_list', $search_style_list);
		
		$data = $this->house_model->get_new_house_list();
		$ids = array();
		$xq_ids = array();
		
		foreach ($data['res_list'] as &$d) {
			$d->feature_list = explode(",", $d->feature);
			$region_id = $d->region_id;
			if($region_id < 6) {
				$d->region_fullname = "玉山镇-" . $d->region_name;
			} else {
				$d->region_fullname = $d->region_name . "-" . $d->region_name;
			}
			$ids[] = $d->id;
			$xq_ids[] = $d->xq_id;
		}
		$rooms = $this->house_model->get_house_rooms($ids);
		$news = $this->house_model->get_house_news($xq_ids);
		$recommend_list = $this->house_model->get_recommend_list();
		foreach($recommend_list as $k=>$v){
			$recommend_list[$k]['feature'] = explode(",", $v['feature']);
		}
		
		$this->assign('rooms', $rooms);
		$this->assign('news', $news);
		$this->assign('recommend_list', $recommend_list);
		$this->assign('new_house_list', $data);
		
		$pager = $this->pagination->getPageLink('/house/new_house_list', $data['countPage'], $data['numPerPage']);
		$this->assign('pager', $pager);

		$this->assign('search_text', $this->input->post('search_text'));
		$this->assign('search_region', $this->input->post('search_region'));
		$this->assign('search_style', $this->input->post('search_style'));
		$this->assign('search_price', $this->input->post('search_price'));
		$this->assign('search_acreage', $this->input->post('search_acreage'));
		$this->assign('search_type', $this->input->post('search_type'));
		$this->assign('search_feature', $this->input->post('search_feature'));
		
		$this->assign('search_order', $this->input->post('search_order') ? $this->input->post('search_order') : 1);
		$this->assign('order_price_dir', $this->input->post('order_price_dir') ? $this->input->post('order_price_dir') : 1);
		
		$this->display('new_house_list.html');
	}
	
	public function second_hand_list() {
		
		$search_region_list = $this->house_model->get_search_region_list();
		$this->assign('search_region_list', $search_region_list);
		
		$search_style_list = $this->house_model->get_search_style_list();
		$this->assign('search_style_list', $search_style_list);
		
		$data = $this->house_model->get_second_hand_list();
		foreach ($data['res_list'] as &$d) {
			$d->feature_list = explode(",", $d->feature);
			$d->unit_price = intval($d->total_price * 10000 / $d->acreage);
			$region_id = $d->region_id;
			if($region_id < 6) {
				$d->region_fullname = "玉山镇-" . $d->region_name;
			} else {
				$d->region_fullname = $d->region_name . "-" . $d->region_name;
			}
		}
		$this->assign('second_hand_list', $data);
		
		$pager = $this->pagination->getPageLink('/house/second_hand_list', $data['countPage'], $data['numPerPage']);
		$this->assign('pager', $pager);
		
		$this->assign('search_text', $this->input->post('search_text'));
		$this->assign('search_region', $this->input->post('search_region'));
		$this->assign('search_style', $this->input->post('search_style'));
		$this->assign('search_price', $this->input->post('search_price'));
		$this->assign('search_acreage', $this->input->post('search_acreage'));
		$this->assign('search_type', $this->input->post('search_type'));
		$this->assign('search_feature', $this->input->post('search_feature'));
		
		$this->assign('search_order', $this->input->post('search_order') ? $this->input->post('search_order') : 1);
		$this->assign('order_price_dir', $this->input->post('order_price_dir') ? $this->input->post('order_price_dir') : 1);
		
		$this->display('second_hand_list.html');
	}
	
	private function get_monthly_payment($rate, $months, $amount) {
		$v = 1 + ($rate / 12);
		$t = -($months / 12) * 12;
		$value = ($amount * ($rate / 12))/(1 - pow($v, $t));
		return round($value, 2);
	}
	
	public function second_hand_detail($id) {
		
		$house = $this->house_model->get_second_hand_detail($id);
		$house['feature_list'] = explode(",", $house['feature']);
		$house['unit_price'] = intval($house['total_price'] * 10000 / $house['acreage']);
		$house['first_pay'] = intval($house['total_price'] * 0.3);
		$house['monthly_pay'] = $this->get_monthly_payment(0.054, 240, $house['total_price'] * 10000 * 0.7);
		
		$broker_house_count = $this->house_model->get_broker_house_count($house['broker_id']);
		$house['broker_house_count'] = $broker_house_count;
		
		$house['house_pics'] = $this->house_model->get_second_hand_house_pics($id);
		
		$this->assign('house', $house);
		
		$this->display('second_hand_detail.html');
	}
	
	public function new_house_detail($id) {
		$rooms = $this->house_model->get_house_rooms($id);
		$house = $this->house_model->get_new_house_detail($id);
		$huxing = $this->house_model->get_new_house_huxing($id);
		$house['feature_list'] = explode(",", $house['feature']);
		$news = $this->house_model->get_house_news_row($house['xq_id']);
		$pics = $this->house_model->get_new_house_pics($id);
		$prices = $this->house_model->get_new_house_price($id,$house['region_id'],$house['substyle_id']);
		$this->assign('huxing', $huxing);
		$this->assign('prices', $prices);
		$this->assign('house', $house);
		$this->assign('pics', $pics);
		$this->assign('news', $news);
		$this->assign('rooms', $rooms[$id]);
		$this->display('new_house_detail.html');
	}
	
	public function article_list($id){
		$data = $this->house_model->get_article_list($id);
		$this->assign('list', $data['list']);
		$this->assign('tag', $data['tag']);
		$this->display('article_list.html');
	}
	
	public function article_detail($h_id,$id){
		$data = $this->house_model->get_article_detail($h_id,$id);
		$this->assign('detail', $data['detail']);
		$this->assign('tag', $data['tag']);
		$this->display('article_detail.html');
	}
	
	public function huxing_list($h_id,$count='all',$pageNum=1){
		$rooms = $this->house_model->get_house_rooms($h_id);
		$this->assign('rooms', $rooms[$h_id]);
		$data = $this->house_model->get_huxing_list($h_id,$count,$pageNum);
		$this->assign('huxing_list', $data);
		$this->assign('tag', $data['tag']);
		$pager = $this->pagination->getPageLink('/house/huxing_list/'.$h_id.'/'.$count, $data['countPage'], $data['numPerPage'],5);
		$this->assign('pager', $pager);
		$this->display('huxing_list.html');
	}

}
