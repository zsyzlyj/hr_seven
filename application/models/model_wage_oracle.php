<?php 

class Model_wage_oracle extends CI_Model{
	var $db_oracle;
	public function __construct(){
		parent::__construct();
		$this->db_oracle=$this->load->database('oracle',TRUE);
	}
	public function test(){
		#$sql = "SELECT count(*) FROM T_SALARY_PERSON";
		#$sql = "select distinct lx1 from t_salary_detail where acct_month='201905' and lx='商企' and duty='客户经理' and falg='1'";
		$sql = "select * from t_salary_detail where  acct_month='201905' and lx='商企' and duty='客户经理' and falg='0' and lx1='后付费提成'";
		
		$query = $this->db_oracle->query($sql);
		return $query->result_array();
	}
	public function getById($id){
		$sql = "SELECT person_name,lx,duty FROM T_SALARY_person WHERE pspt_id=?";
		$query = $this->db_oracle->query($sql,$id);
		return $query->row_array();
	}
	public function getLx1($lx,$duty){
		$sql = "select distinct lx1 from t_salary_detail where acct_month='201905' and lx=? and duty=? and falg='0'";
		$query = $this->db_oracle->query($sql,array($lx,$duty));
		return $query->result_array();
	}
	public function getAttr($lx,$duty,$lx1){
		$sql = "select * from t_salary_detail where acct_month='201905' and lx=? and duty=?	and falg='0' and lx1=?";
		$query = $this->db_oracle->query($sql,array($lx,$duty,$lx1));
		return $query->result_array();
	}
	public function getDetail($name,$lx,$duty,$lx1){
		$sql = "select * from t_salary_detail where acct_month='201905' and STAFF_NAME=? and lx=? and duty=? and falg='1' and lx1=?";
		$query = $this->db_oracle->query($sql,array($name,$lx,$duty,$lx1));
		return $query->result_array();
	}
	public function getAllTable(){
		#$sql = "SELECT count(*) FROM T_SALARY_PERSON";
		$sql = "SELECT table_name FROM all_tables";
		$query = $this->db_oracle->query($sql);
		return $query->result_array();
	}
	public function lqp_test(){
		$sql = "SELECT count(*) FROM LQP_TEST";
		$query = $this->db_oracle->query($sql);
		return $query->result_array();
	}
	public function lqp_temp_test(){
		$sql = "SELECT count(*) FROM LQP_TEMP";
		$query = $this->db_oracle->query($sql);
		return $query->result_array();
	}
	/* get the brand data */
	public function getWageData(){
		$sql = "SELECT * FROM local_zd where rownum=1";
		$query = $this->db_oracle->query($sql);
		return $query->result_array();
	}
	public function getDailyData($id,$date){
		$sql = "SELECT user_no,charge,fgs,devlop_depart,rwny FROM local_zd_near where develop_staff_id=? and acct_month=?";
		$query = $this->db_oracle->query($sql,array($id,$date));
		return $query;	
	}
	public function getByName($staff_name = null){
		if($staff_name){
			$sql = "SELECT * FROM wage WHERE staff_name = ?";	
			$query = $this->db->query($sql, array($staff_name));
			return $query->row_array();
		}
	}
	public function getWageByName($staff_name = null){
		if($staff_name){
			$sql = "SELECT yff_charge,hff_charge,gw_charge,cl_charge,qf_charge,other1,other2,other3,other4,end_charge FROM wage WHERE staff_name = ?";	
			$query = $this->db->query($sql, array($staff_name));
			return $query->row_array();
		}
	}
	public function getWageById($userId = null){
		if($userId){
			$sql = "SELECT * FROM wage WHERE user_id = ?";	
			$query = $this->db->query($sql, array($userId));
			return $query->row_array();
		}
	}
	public function getWageByDept($dept = null){
		if($dept){
			$sql = "SELECT * FROM wage WHERE locate(?,department)";
			$query = $this->db->query($sql, array($dept));
			return $query->result_array();
		}
	}
	public function getWageByDate($date = null){
		if($date){
			$sql = "SELECT * FROM wage WHERE locate(?,date_tag)";	
			$query = $this->db->query($sql, array($date));
			return $query->result_array();
		}
	}
	public function getWageByDateAndId($id,$date){
		if($date and $id){
			$query=$this->db->where_in('user_id', $id)->get_where('wage',array('date_tag'=>$date));
			#$sql = "SELECT * FROM (select * from wage where user_id in ?) as t WHERE locate(?,date_tag)";	
			#$query = $this->db->query($sql, array($id,$date));
			return $query->row_array();
		}
	}
	public function getWageByDateAndIdset($id,$date){
		if($date and $id){
			$query=$this->db->where_in('user_id', $id)->get_where('wage',array('date_tag'=>$date));
			#$sql = "SELECT * FROM (select * from wage where user_id in ?) as t WHERE locate(?,date_tag)";	
			#$query = $this->db->query($sql, array($id,$date));
			return $query->result_array();
		}
	}
	public function getWageByDateAndDept($dept,$date){
		if($date and $dept){	
			$sql = "SELECT * FROM (select * from wage where locate(?,department)) as t WHERE locate(?,date_tag)";	
			$query = $this->db->query($sql, array($dept,$date));
			return $query->result_array();
		}
	}
	public function exportWageData($id = null){
		$sql = "SELECT * FROM wage";
		return $this->db->query($sql);
	}
	public function exportmydeptWageData($dept=null){
		if($dept){
			$sql = "SELECT * FROM wage WHERE locate(?,department)";
			$query = $this->db->query($sql, array($dept));
			return $query;
		}

	}
	public function create($data){
		if($data){
			$insert = $this->db->insert('wage', $data);
			return ($insert == true) ? true : false;
		}
	}
	public function createbatch($data){
		if($data){
			$insert = $this->db->insert_batch('wage', $data);
			return ($insert == true) ? true : false;
		}
	}
	public function deleteAll(){
		$sql='delete from wage';
		$delete = $this->db->query($sql);
		return ($delete == true) ? true : false;
	}
	public function getDatetag(){
		$sql='select distinct date_tag from wage order by date_tag';
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	public function deleteByDate($date){
		$sql='delete from wage where locate(?,date_tag)';
		$delete = $this->db->query($sql,array($date));
		return ($delete == true) ? true : false;
	}
	public function countAvg($date_set,$user_id){
		#$query=$this->db->where('user_id',$user_id)->where_in('date_tag', $date_set)->select_avg('total')->from('wage')->get();
		$query=$this->db->select_avg('total')->where('user_id',$user_id)->where_in('date_tag', $date_set)->get('wage');
		return $query->row_array();
	}
	public function countSum($date_set,$user_id){
		#$query=$this->db->where('user_id',$user_id)->where_in('date_tag', $date_set)->select_avg('total')->from('wage')->get();
		$query=$this->db->select_sum('total')->where('user_id',$user_id)->where_in('date_tag', $date_set)->get('wage');
		return $query->row_array();
	}
	public function getDeptByDate($date_set){
		$query=$this->db->from('wage')->where_in('date_tag', $date_set)->get();
		return $query->result_array();
	}
}