<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Super_gw_wage extends Admin_Controller {
	public function __construct(){
        parent::__construct();
        $this->data['page_title'] = 'Super';
        $this->load->model('model_notice');
        $this->load->model('model_dept');
        $this->load->model('model_users');
        $this->load->model('model_all_user');
        $this->load->model('model_wage_tag');
        $this->load->model('model_wage_attr');
        $this->load->model('model_wage_apply');
        $this->load->model('model_wage_apply_status');
        $this->load->model('model_wage_notice');
        $this->load->model('model_wage');
        $this->load->model('model_wage_doc'); 
        $this->load->model('model_func');
        $this->load->model('model_wage_sp');
        $this->load->model('model_wage_sp_attr');
        $this->load->model('model_wage_tax');
        $this->load->model('model_wage_tax_attr'); 
        $this->load->model('model_hr_content'); 
        $this->data['user_name'] = $this->session->userdata('user_id');
        $this->data['user_id'] = $this->session->userdata('user_id');
        if($this->data['user_name']==NULL){
            redirect('super_auth/login','refresh');
        }
        $this->data['permission']=$this->session->userdata('permission');
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
    public function wage_gw_import(){
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
                                $this->render_super_template('super/wage_gw_import',$this->data);
                            }
                            else{
                                $this->session->set_flashdata('success', '工资导入成功！');
                                $this->gw_excel_put($doc_name);
                                $this->data['wage']="";
                                $this->data['wage_attr']="";
                                $this->data['chosen_month']="";
                                $this->render_super_template('super/wage_gw_charge',$this->data);
                                #$this->data['import_list']=$this->model_wage_sp->getDatetag();
                                #$this->render_super_template('super/wage_sp_import_list',$this->data);
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
    public function search(){
        $this->data['wage_data']="";
        $this->data['attr_data']="";
        $this->data['chosen_month']="";
        $this->data['trueend']=0;
        $name=$this->session->userdata('user_name');
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->data['chosen_month']=$_POST['chosen_month'];
            $doc_name=substr($_POST['chosen_month'],0,4).substr($_POST['chosen_month'],5,6);
            if(strlen($doc_name)<=7 and $doc_name!=""){
                $this->data['attr_data']=$this->model_wage_gw_attr->getData($doc_name);
                if(!empty($this->data['attr_data'])){
                    $this->data['wage_data']=$this->model_wage_gw->getDataByNameAndDate($name,$doc_name);
                }
            }
            
            $this->render_super_template('super/wage_gw_charge_search',$this->data);
            
        }
        else{ 
            $this->render_super_template('super/wage_gw_charge_search',$this->data);
        }
    }
}