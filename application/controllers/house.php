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

	}
	
	public function index() {
		
	}
	
	public function new_house_list() {
		
		$this->display('new_house_list.html');
	}
	
	public function second_hand_list() {
		
		
		$this->assign('', '');
		$this->assign('', '');
		$this->assign('', '');
		$this->assign('', '');
		$this->assign('', '');
		$this->assign('', '');
		
		$this->display('second_hand_list.html');
	}
}
