<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class M_api_model extends MY_Model {

	public function __construct () {
        parent::__construct();
    }

    public function __destruct () {
        parent::__destruct();
    }
    
    public function login($username, $password) {
    	
    	$this->db->select('a.*, b.name AS company_name, b.address, b.tel AS company_tel');
    	$this->db->from('admin a');
    	$this->db->join('company b', 'a.company_id = b.id', 'left');
    	$this->db->where('a.username', $username);
    	$this->db->where('a.passwd', sha1($password));
    	return $this->db->get()->row();
    }
    
    public function list_client($id) {
    	
    	$this->db->select('a.open_id, b.nickname, b.sex, b.headimgurl, b.realname, b.user_tel');
    	$this->db->from('wx_user a');
    	$this->db->join('weixin b', 'a.open_id = b.openid', 'inner');
    	$this->db->where('a.broker_id', $id);
    	$this->db->where('b.subscribe', 1);
    	return $this->db->get()->result();
    }
    
    public function list_house($id) {
    	
    	$this->db->select('a.*, b.name AS region_name, c.name AS orientation_name, d.name AS xiaoqu_name, d.address, d.latitude, d.longitude, e.name AS decoration_name');
    	$this->db->from('house a');
   		$this->db->join('house_region b', 'a.region_id = b.id', 'left');
   		$this->db->join('house_orientation c', 'a.orientation_id = c.id', 'left');
   		$this->db->join('xiaoqu d', 'a.xq_id = d.id', 'left');
   		$this->db->join('house_decoration e', 'a.decoration_id = e.id', 'left');
    	$this->db->where('a.broker_id', $id);
    	$this->db->order_by('a.id', 'desc');
    	return $this->db->get()->result();
    }
    
    public function get_house_slide($id) {
    	
    	$this->db->select('type_id, pic_short, is_bg');
    	$this->db->from('house_img');
    	$this->db->where('h_id', $id);
    	$this->db->where('type_id', 1);
    	return $this->db->get()->result();
    }
    
    public function get_house_detail($id) {
    	
    	$this->db->select('a.*, b.name AS region_name, c.name AS orientation_name, d.name AS xiaoqu_name, d.address, d.latitude, d.longitude, e.name AS decoration_name');
    	$this->db->from('house a');
    	$this->db->join('house_region b', 'a.region_id = b.id', 'left');
    	$this->db->join('house_orientation c', 'a.orientation_id = c.id', 'left');
    	$this->db->join('xiaoqu d', 'a.xq_id = d.id', 'left');
    	$this->db->join('house_decoration e', 'a.decoration_id = e.id', 'left');
    	$this->db->where('a.id', $id);
    	return $this->db->get()->row();
    }
}