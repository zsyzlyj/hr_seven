<?php 

class Model_wage_zq extends CI_Model{
	public function __construct(){
		parent::__construct();
	}

	public function getByDateAndSheet($date,$sheet){
		if($date and $sheet){
			$query=$this->db->get_where('wage_zq',array('date_tag'=>$date,'sheet_tag'=>$sheet));
			if($query)
				return $query->result_array();
			else return NULL;
		}
		else return NULL;
	}
	public function getByDateAndName($date,$id,$workbook){
		if($date and $id){
			$query=$this->db->get_where('wage_zq',array('date_tag'=>$date,'username'=>$id,'workbook_tag'=>$workbook));
			if($query)
				return $query->result_array();
			else return NULL;
		}
		else return NULL;
	}
	public function getData(){
		$sql = "SELECT * FROM wage_zq WHERE user_id != ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}
	public function getById($userId = null){
		if($userId){
			$sql = "SELECT * FROM wage_zq WHERE user_id = ?";	
			$query = $this->db->query($sql, array($userId));
			return $query->row_array();
		}
	}
	public function getByDept($dept){
		$sql = "SELECT * FROM wage_zq where locate(?,dept)";
		$query = $this->db->query($sql, array($dept));
		return $query->result_array();
	}
	public function create($data = ''){
		if($data){
			$create = $this->db->insert('wage_zq', $data);
			return ($create == true) ? true : false;
		}
	}
	public function createbatch($data){
		if($data){
			$insert = $this->db->insert_batch('wage_zq', $data);
			return ($insert == true) ? true : false;
		}
	}
	/*
		更新用户的权限
	*/
	public function update($data=array(),$id){
		$this->db->where('user_id',$id);
		$update = $this->db->update('wage_zq', $data);
		return ($update == true) ? true : false;	
	}
	public function deleteByDate($date,$workbook){
		$sql='delete from wage_zq where locate(?,date_tag) and workbook_tag=?';
		$delete = $this->db->query($sql,array($date,$workbook));
		return ($delete == true) ? true : false;
	}
	public function delete($id){
		$this->db->where('user_id', $id);
		$delete = $this->db->delete('wage_zq');
		return ($delete == true) ? true : false;
	}
	public function exportData($dept = null){	
		if($dept){	
			$sql = "SELECT * FROM wage_zq WHERE locate(?,dept)";
			$query = $this->db->query($sql, array($dept));
			return $query;
		}
		else return NULL;
	}
	public function deleteAll(){
		$sql='delete from wage_zq';
		$delete = $this->db->query($sql);
	}
	
	public function getModeById($id){
		if($id){
			$sql = "SELECT service_mode FROM wage_zq WHERE user_id = ?";	
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}
}