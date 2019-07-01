
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
        $id=null;
        $attr=array();
        $data=array();
        $active_counter=0;
        //获取个人信息
        $id='230302198910155828';
        $user_data=$this->model_wage_oracle->getById($id);
        //获取线条信息
        $lx1_set=$this->model_wage_oracle->getlx1($user_data['LX'],$user_data['DUTY']);
        //
        foreach($lx1_set as $k =>$v){
            $objPHPExcel->setActiveSheetIndex($active_counter);
            $objPHPExcel->getActiveSheet($active_counter)->setTitle($v["LX1"]);
            $col = 0;
            $fields=array();
            $result=array();
            $fields=$this->model_wage_oracle->getAttr($user_data['LX'],$user_data['DUTY'],$v);
            foreach ($fields as $a => $b){
                foreach($b as $c => $d){
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $d);
                    $col++;
                }
            }
            $row = 2;
            $result=$this->model_wage_oracle->getDetail($user_data['PERSON_NAME'],$user_data['LX'],$user_data['DUTY'],$v);
            foreach($result as $a => $b){
                $col = 0;
                foreach ($b as $c => $d){
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $d);
                    $col++;
                }
                $row++;
            }
            unset($fields);
            unset($result);
            $active_counter++;
            $objPHPExcel->createSheet();
        }
        
        
        
		
		$filename = $user_data['PERSON_NAME'].date('m').'月';
        ob_end_clean();
        #header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename);
        header('Cache-Control: max-age=0');

        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
	public function index(){
        
        $this->excel();
        #$this->model_wage_oracle->test();
		#$this->excel();
		//echo var_dump($this->model_wage_oracle->test());
		
		/*
		echo var_dump($this->model_wage_oracle->salary_person_test());
		echo '<br />';
		echo var_dump($this->model_wage_oracle->lqp_test());
		echo '<br />';
		echo var_dump($this->model_wage_oracle->lqp_temp_test());
		echo '<br />';
		*/
/**/
		/*
		foreach($user_data as $k => $v){
			foreach($v as $a => $b)
				echo $b;
		}
		*/
    }
}