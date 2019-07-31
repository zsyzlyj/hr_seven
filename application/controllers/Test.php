
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends Admin_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('model_auth');
        $this->load->model('model_wage_oracle');
        $this->load->model('model_wage_gw_attr');
        $this->load->model('model_wage_gw');
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
        header('Content-Type: application/vnd.openxmlformats-officedocument.gwreadsheetml.sheet');
        header('Content-Digwosition: attachment;filename="'.$filename);
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
    public function gw_excel_put($filename){
        $this->load->library('phpexcel');//ci框架中引入excel类
        $this->load->library('PHPExcel/IOFactory');
 
        //先做一个文件上传，保存文件
        $path=$_FILES['file'];
        
        //根据上传类型做不同处理
        if(strstr($_FILES['file']['name'],'xlsx')){
            $reader = new PHPExcel_Reader_Excel2007();
            $filePath = 'uploads/wage/gw'.$filename.'.xlsx';
            move_uploaded_file($path['tmp_name'],$filePath);
        }
        elseif(strstr($_FILES['file']['name'], 'xls')){
            $reader = IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
            $filePath = 'uploads/wage/gw'.$filename.'.xls';
            move_uploaded_file($path['tmp_name'],$filePath);
            
        }
        //薪酬文件记录写入
        
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ','BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ','CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ','DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ'); 
        $PHPExcel = $reader->load($filePath, 'utf-8'); // 载入excel文件
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
        $columnCnt = array_search($highestColumm, $cellName); 

        $data = array();
        $wage_attr = array();
        $wage = array();
        $this->model_wage_gw->deleteByDate($filename);
        for($rowIndex = 1; $rowIndex <= $highestRow; $rowIndex++){        //循环读取每个单元格的内容。注意行从1开始，列从A开始
            $temp = array();
            for($colIndex = 0; $colIndex <= $columnCnt; $colIndex++){
                $cellId = $cellName[$colIndex].$rowIndex;  
                $cell = $sheet->getCell($cellId)->getValue();
                $cell = $sheet->getCell($cellId)->getCalculatedValue();
                if($cell instanceof PHPExcel_RichText){ //富文本转换字符串
                    $cell = $cell->__toString();
                }
                if($cell===0){
                    $temp[$colIndex] = 0;    
                }
                else $temp[$colIndex] = $cell;
            }
            if($rowIndex==1){
                foreach($temp as $k => $v){
                    $wage_attr['attr'.($k+1)]=$v;
                }
            }
            else{
                foreach($temp as $k => $v){
                    $wage['attr'.($k+1)]=$v;
                }
                array_push($data,$wage);
                unset($wage);
            }
            unset($temp);
        }
        
        $this->model_wage_gw_attr->create($wage_attr);
        $this->model_wage_gw->createbatch($data);   
    }
    public function import(){
        
        if($_SERVER['REQUEST_METHOD'] == 'POST' and array_key_exists('chosen_month',$_POST)){
            $doc_name=substr($_POST['chosen_month'],0,4).substr($_POST['chosen_month'],5,6);
            if(strstr($doc_name,'1899')){
                $this->session->set_flashdata('error', '请选择月份!!');
                $this->render_super_template('super/wage_gw_import',$this->data);
            }
            else{
                if(strlen($doc_name)<=7 and $doc_name!=''){
                    if($_FILES){
                        if($_FILES['file']){
                            if($_FILES['file']['error'] > 0){
                                $this->session->set_flashdata('error', '请选择文件!!');
                                $this->render_super_template('super/gw_charge_import',$this->data);
                            }
                            else{
                                $this->session->set_flashdata('success', '工资导入成功！');
                                $this->gw_excel_put($doc_name);
                                $this->data['wage_data']=$this->model_wage_gw->getDataByNameAndDate($name,$doc_name);
                                $this->data['attr_data']=$this->model_wage_gw_attr->getWageGwData();
                                $this->data['chosen_month']="";
                                $this->render_super_template('wage/wage_gw_charge',$this->data);
                                #$this->data['import_list']=$this->model_wage_gw->getDatetag();
                                #$this->render_super_template('super/wage_gw_import_list',$this->data);
                            }
                        }
                    }
                    else{
                        $this->session->set_flashdata('error', '请选择文件!!');
                        $this->render_super_template('super/wage_gw_import',$this->data);
                    }
                }
            }
        }
        else{
            $this->render_super_template('super/wage_gw_import',$this->data);
        }
    }
    public function gw_charge(){
        $this->data['wage_data']="";
        $this->data['attr_data']="";
        $this->data['chosen_month']="";
        $name='陈力';
        $doc_name='201905';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->data['chosen_month']=$_POST['chosen_month'];
            $doc_name=substr($_POST['chosen_month'],0,4).substr($_POST['chosen_month'],5,6);
            if(strlen($doc_name)<=7 and $doc_name!=""){
                $doc_name='201905';
                $this->data['attr_data']=$this->model_wage_gw_attr->getData($doc_name);
                if(!empty($this->data['attr_data'])){
                    $doc_name='201905';
                    $this->data['wage_data']=$this->model_wage_gw->getDataByNameAndDate($name,$doc_name);
                }
            }
            /*
            $log=array(
                'user_id' => $this->data['user_id'],
                'username' => $this->data['user_name'],
                'login_ip' => $_SERVER["REMOTE_ADDR"],
                'staff_action' => '查看'.$this->data['chosen_month'].'固网提成',
                'action_time' => date('Y-m-d H:i:s')
            );
            $this->model_log_action->create($log);
            unset($log);
            */
            $this->render_template('wage/wage_gw_charge',$this->data);
            
        }
        else{ 
            $this->render_template('wage/wage_gw_charge',$this->data);
        }
    }
}