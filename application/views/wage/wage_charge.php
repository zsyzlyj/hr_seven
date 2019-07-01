  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        文件下载
      </h1>
    </section>
    <hr />
    <!-- Main content -->
    <section class="content">
      <div class="col-md-12 col-xs-12">
        <div class="row">
          <h3>本月提成明细</h3>
          <br />
          <a href="<?php echo base_url('wage/excel_charge') ?>" class="btn btn-info">下载</a>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
  <script type="text/javascript">
    $(document).ready(function() {

      $("#chargeMainMenu").addClass('active');
      
    });
    
  </script>