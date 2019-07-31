<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
      <h1>
        固网明细查询
      </h1>
    </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="box">
          <div class="box-header">
          
          <h4>
          <p>
          </p>
          </h4>
          </br>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div>
              <form action="<?php echo base_url('wage/gw_charge')?>" class="form-horizontal" method="post" role="form">
                <fieldset>
                  <legend></legend>
                  <div class="form-group">
                    <label for="dtp_input1" class="col-md-1 control-label">月份选择</label>
                    <div class="input-group date form_datetime col-md-5" data-date-format="yyyy-mm" data-link-field="dtp_input1">
                      <?php if($chosen_month):?>
                      <input class="form-control" name="chosen_month" size="16" type="text" value="<?php echo $chosen_month;?>" readonly>
                      <?php else:?>
                      <input class="form-control" name="chosen_month" size="16" type="text" value="<?php echo date('Y-m');?>" readonly>
                      <?php endif;?>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                      <button type='submit' class='btn-info form-control'>查询</button>
                    </div>
                    <input type="hidden" id="dtp_input1" value="" /><br/>                    
                  </div>
                </fieldset>
              </form>
            </div>
            <hr />
            <?php if($chosen_month): ?>
            <h4><?php echo date_format(date_create($chosen_month),"Y年m月");?>固网双线提成</h4>
            <hr />
            <hr />
            <?php endif;?>
            <div style="overflow:scroll;">
              <fieldset>
              <table id="wageTable"class="table table-striped table-bordered table-responsive" style="white-space:nowrap;text-align: center;">
                <thead>
                  <tr style="">
                  <?php $counter=0;?>
                  <?php foreach($attr_data as $k => $v): ?>
                    <th style="text-align:center;border-color:silver"><?php echo $v;?></th>
                  <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php if($wage_data):?>
                    <?php foreach($wage_data as $k => $v): ?>
                    <?php $counter=0;?>
                    <tr>
                      <?php foreach($v as $a => $b):?>
                      <td style="border-color:silver"><?php echo $b?></td>
                      <?php endforeach; ?>  
                    </tr>
                    <?php endforeach; ?>
                    
                  <?php elseif($chosen_month!=""): ?>
                      <h4>无当月工资记录</h4>
                  <?php endif; ?>
                </tbody>
              </table>
              </fieldset>
            </div>
          </div>
      </div>
    </div>  

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function() { 
      $("#gwchargeMainMenu").addClass('active');
      $(".form_datetime").datetimepicker({
        bootcssVer:3,
        format: 'yyyy-mm',
        startView:3,
        minView:3,
        startDate:(new Date().getFullYear()-2).toString()+'-'+new Date().getMonth().toString(),
        autoclose:true,
        pickerPosition: "-left"
      });
      $('#wageTable').DataTable({
        language:{
            "sProcessing": "处理中...",
            "sLengthMenu": "显示 _MENU_ 项",
            "sZeroRecords": "没有匹配结果",
            "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
            "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
            "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
            "sInfoPostFix": "",
            "sSearch": "搜索:",
            "sUrl": "",
            "sEmptyTable": "表中数据为空",
            "sLoadingRecords": "载入中...",
            "sInfoThousands": ",",
            "oPaginate":{
                "sFirst": "首页",
                "sPrevious": "上页",
                "sNext": "下页",
                "sLast": "末页"
            },
            "oAria":{
                "sSortAscending": ": 以升序排列此列",
                "sSortDescending": ": 以降序排列此列"
            }
        }      
      });

    
    });
    
    
  </script>
