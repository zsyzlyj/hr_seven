  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        C、D岗提成明细
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12 col-xs-12">
          <div class="box">
            <div class="box-header">
              </br>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div>
                <form action="<?php echo base_url('wage/excel_cd') ?>" class="form-horizontal" method="post" role="form">
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
                        <button type='submit' class='btn-info form-control'>下载</button>
                      </div>
                      <input type="hidden" id="dtp_input1" value="" /><br/>                    
                    </div>
                  </fieldset>
                </form>
              </div>
              <hr />
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
      $("#zqMainMenu").addClass('active');
      $("#cdMainMenu").addClass('active');
      $(".form_datetime").datetimepicker({
        bootcssVer:3,
        format: 'yyyy-mm',
        startView:3,
        minView:3,
        startDate:"2019-06",
        autoclose:true,
        pickerPosition: "-left"
      });
      
    });
    
  </script>