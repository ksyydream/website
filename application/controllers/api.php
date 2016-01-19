<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('api_model');
		$this->load->model('house_model');
	}
	
	public function send_text($open_id) {
		$this->api_model->send_text($open_id, urlencode("您收到了一条消息。<a href='http://www.funmall.com.cn/b_house/view_chat/{$open_id}'>点击查看</a>"));
	}
	
	public function update_weixin_user($openid, $broker_id) {
		$this->api_model->update_weixin_user($openid);
		
		$redis = new Redis();
		$redis->connect(REDIS_HOST, REDIS_PORT);
		$redis->auth(REDIS_AUTH);
		
		$key = "map:" . $broker_id;
		$users = $redis->lrange($key, 0, -1);
		if(!in_array($openid, $users)) {
			$redis->lpush($key, $openid);
		}
	}
	
	public function unsubscribe_weixin_user($openid) {
		
	}
	
	public function view_art($open_id, $broker_id) {
		//$this->api_model->update_weixin_user($open_id);
		
		$token = $this->api_model->get_or_create_token();
		$access_token = $token['token'];
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
		$result = file_get_contents($url);
		$jsonInfo = json_decode($result, true);
		$this->assign('subscribe', $jsonInfo['subscribe']);

		$ticket = $this->api_model->get_or_create_jsapi_ticket();
		$signPackage = $this->getSignPackage($ticket);
		$this->assign('signPackage', $signPackage);
		
		$article = $this->house_model->get_article($broker_id);
		$this->assign('article', $article);
		$this->display('broker/article.html');
	}
	
	public function search_house() {
		$results = $this->api_model->search_house_by_name($_POST['keyword']);
		$content = array();
		if(!empty($results)) {
			foreach ($results as $h) {
				$content[] = array(
					'Title' => $h['region_name'] . $h['xq_name'] . $h['room'] . '室' . $h['lounge'] . '厅 ' . $h['acreage'] . '㎡' . $h['total_price'] . '万',
					'Description' => '',
					'PicUrl' => 'http://www.funmall.com.cn/uploadfiles/pics/' . $h['bg_pic'],
					'Url' => 'http://www.funmall.com.cn/b_house/view_detail/' . $h['id']
				);
			}
		}
		$wx_open_id = $_POST['open_id'];
		$this->session->set_userdata('wx_open_id', $wx_open_id);
		
		echo json_encode($content);
	}
	
///////////////////////////////////////////////////////////////////////////	
	public function index() {
		$articles = array(
			array(
				'title' => urlencode('周市 宇业天逸华庭 3室2厅 104 60万'),
				'url' => 'http://www.funmall.com.cn/b_house/view_detail/2904',
				'picurl' => 'http://www.funmall.com.cn/uploadfiles/pics/20151102135539/1/817a0b92fbf7bc3bd0dde2cd1de60277.png'
			),
			array(
				'title' => urlencode('城中 锦晟花园 5室1厅 120 100万'),
				'url' => 'http://www.funmall.com.cn/b_house/view_detail/2791',
				'picurl' => 'http://www.funmall.com.cn/uploadfiles/pics/20151030134650/1/283f930940902011b0e3aa4b683cfaf3.jpg'
			),
			array(
				'title' => urlencode('城中 金鹰天地 4室2厅 170 238万'),
				'url' => 'http://www.funmall.com.cn/b_house/view_detail/2836',
				'picurl' => 'http://www.funmall.com.cn/uploadfiles/pics/20151030163107/1/dca6a48adf15403065c5a23ecd0eae59.png'
			)
		);
		$ret = $this->api_model->send_message('orFu-vgK-snskoQdDgMkBe-jFe1k', $articles);
		var_dump($ret);
	}
	
	public function test_ticket() {
		$ret = $this->api_model->get_or_create_ticket(1);
		var_dump($ret);
	}
	
	public function test_jsapi_ticket() {
		$ret = $this->api_model->get_or_create_jsapi_ticket();
		var_dump($ret);
	}
	
	public function get_wx_user_info($open_id) {
		$token = $this->api_model->get_or_create_token();
		$access_token = $token['token'];
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
		$result = file_get_contents($url);
		$jsonInfo = json_decode($result, true);
		var_dump($jsonInfo);
		header("Content-type: text/html; charset=utf-8");
	}
}