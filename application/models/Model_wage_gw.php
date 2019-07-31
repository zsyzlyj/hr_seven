<?php 

class Model_wage_gw extends CI_Model{
	public function __construct(){
		parent::__construct();
	}

	/* get the brand data */
	public function getWageGwData(){
		$sql = "SELECT * FORM wage_gw";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	public function getDatetag(){
		$sql='select distinct date_tag from wage_gw order by date_tag';
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	public function getWageGwByDate($date){
		if($date){
			$sql="select * from wage_gw where locate(?,attr1)";
			$query = $this->db->query($sql,array($date));
			if($query)
				return $query->result_array();
			else return NULL;
		}
	}
	public function getWageByDateAndId($date = null,$id=null){
		if($date){
			$sql="select * from wage_gw where locate(?,user_id) and locate(?,date_tag)";
			$query = $this->db->query($sql,array($id,$date));
			#$query=$this->db->where_in('user_id', $id)->get_where('wage_gw',array('date_tag'=>$date));
			return $query->row_array();
		}
	}
	public function createbatch($data){
		if($data){
			$insert = $this->db->insert_batch('wage_gw', $data);
			return ($insert == true) ? true : false;
		}
	}
	public function deleteByDate($date){
		$sql='delete from wage_gw where locate(?,attr1)';
		$delete = $this->db->query($sql,array($date));
		return ($delete == true) ? true : false;
	}
	public function getDataByNameAndDate($name,$date){
		$sql='select * from wage_gw where attr2=? and attr1=?';
		$query = $this->db->query($sql,array($name,$date));
		return $query->result_array();
	}
}