
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('model_auth');
		
        $this->load->model('model_wage_oracle');
		$this->data['user_name'] = $this->session->userdata('user_name');
        $this->data['user_id'] = $this->session->userdata('user_id');
	}
	public function excel(){
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        $result = $this->model_wage_oracle->salary_person_test();
        // Field names in the first row
        $fields = $result->list_fields();
        $col = 0;
        foreach ($fields as $field){
			/*
            $v="";
            switch($field){
                case 'name':$v="姓名\t";break;
                case 'department':$v="部门\t";break;
                case 'initdate':$v="开始工作时间\t";break;
                case 'indate':$v="入职时间\t";break;
                case 'Companyage':$v="社会工龄\t";break;
                case 'Totalage':$v="公司工龄\t";break;
                case 'Totalday':$v="可休假总数\t";break;
                case 'Lastyear':$v="去年休假数\t";break;
                case 'Thisyear':$v="今年休假数\t";break;
                case 'Bonus':$v="荣誉休假数\t";break;
                case 'Used':$v="已休假数\t";break;
                case 'Rest':$v="未休假数\t";break;
                case 'Jan':$v="一月\t";break;
                case 'Feb':$v="二月\t";break;
                case 'Mar':$v="三月\t";break;
                case 'Apr':$v="四月\t";break;
                case 'May':$v="五月\t";break;
                case 'Jun':$v="六月\t";break;
                case 'Jul':$v="七月\t";break;
                case 'Aug':$v="八月\t";break;
                case 'Sep':$v="九月\t";break;
                case 'Oct':$v="十月\t";break;
                case 'Nov':$v="十一月\t";break;
                case 'Dece':$v="十二月\t";break;
                case 'user_id':$v="身份证号\t";break; 	
                default:break;
            }
            if($v != ""){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $v);
                $col++;
			}
			*/
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
		}
		$row = 2;
        foreach($result->result() as $data){
            $col = 0;
            foreach ($fields as $field){
				$objPHPExcel->getActiveSheet(0)->setCellValueByColumnAndRow($col, $row, $data->$field);
                $col++;
            }
            $row++;
        }

        $objPHPExcel->setActiveSheetIndex(0);
		$filename = date('YmdHis');
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename);
        header('Cache-Control: max-age=0');

        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
	public function index(){
		//$user_data=$this->model_wage_oracle->salary_person_test();
		$this->excel();
		/*
		echo var_dump($user_data);
		echo '<br />';
		echo var_dump($this->model_wage_oracle->salary_person_test());
		echo '<br />';
		echo var_dump($this->model_wage_oracle->lqp_test());
		echo '<br />';
		echo var_dump($this->model_wage_oracle->lqp_temp_test());
		echo '<br />';
*/
		/*
		foreach($user_data as $k => $v){
			foreach($v as $a => $b)
				echo $b;
		}
		*/
    }
}