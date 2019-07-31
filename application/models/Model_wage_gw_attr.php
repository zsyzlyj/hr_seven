<?php 

class Model_wage_gw_attr extends CI_Model{
	public function __construct(){
		parent::__construct();
	}

	/* get the brand data */
	public function getWageGwData(){
		$sql = "SELECT * FROM wage_gw_attr";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	public function getData(){
		$sql = "SELECT * From wage_gw_attr";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	
	
	public function getWageAttrDataByDate($date){
		$sql = "SELECT * FROM wage_gw_attr where attr1 = ?";
		$query = $this->db->query($sql,array($date));
		return $query->row_array();
	}
	public function getWageAttrByDateAndId($date = null,$id=null){
		if($date){
			$query=$this->db->get_where('wage_gw_attr',array('date_tag' => $date, 'user_id' => $id));
			
			return $query->row_array();
		}
	}
	public function create($data){
		if($data){
			$insert = $this->db->insert('wage_gw_attr', $data);
			return ($insert == true) ? true : false;
		}
	}
	public function createbatch($data){
		if($data){
			$insert = $this->db->insert_batch('wage_gw_attr', $data);
			return ($insert == true) ? true : false;
		}
	}
	public function deleteByDate($date){
		$sql='delete from wage_gw_attr where locate(?,date_tag)';
		$delete = $this->db->query($sql,array($date));
		return ($delete == true) ? true : false;
	}

}