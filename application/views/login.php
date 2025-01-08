<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>CodeInsect | Admin System Log in</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>Applikasi Kasir</b><br></a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">Sign In</p>
      <?php $this->load->helper('form'); ?>
      <div class="row">
        <div class="col-md-12">
          <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
        </div>
      </div>
      <?php
      $this->load->helper('form');
      $error = $this->session->flashdata('error');
      if ($error) {
      ?>
        <div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <?php echo $error; ?>
        </div>
      <?php }
      $success = $this->session->flashdata('success');
      if ($success) {
      ?>
        <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <?php echo $success; ?>
        </div>
      <?php } ?>

      <form action="<?php echo site_url('auth/proses_login'); ?>" method="post" id="loginform">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" placeholder="Username" name="username" required />
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" placeholder="Password" name="password" required />
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-8">
            <!-- <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Remember Me
                </label>
              </div>  -->
          </div><!-- /.col -->
          <div class="col-xs-4">
            <input type="submit" class="btn btn-primary btn-block btn-flat" value="Sign In" />
          </div><!-- /.col -->
        </div>
      </form>

      <a href="<?php echo base_url() ?>forgotPassword">Forgot Password</a><br>

    </div><!-- /.login-box-body -->
  </div><!-- /.login-box -->

  <script src="<?php echo base_url(); ?>assets/js/jQuery-2.1.4.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
</body>

</html>

<script>
  function validate() {
    var form = $("#loginform");
    var submit = $("#loginform .btn-submit");
    $(form).validate({
      errorClass: 'invalid',
      errorElement: 'em',

      highlight: function(element) {
        $(element).parent().removeClass('state-success').addClass("state-error");
        $(element).removeClass('valid');
      },

      unhighlight: function(element) {
        $(element).parent().removeClass("state-error").addClass('state-success');
        $(element).addClass('valid');
      },

      debug: false,
      rules: {
        username: {
          required: true,
        },
        password: {
          required: true,
        },
      },
      messages: {},
      //ajax form submition
      submitHandler: function(form) {
        $(form).ajaxSubmit({
          dataType: 'json',
          beforeSend: function() {
            $(submit).attr('disabled', true);
          },
          success: function(data) {
            $('.preloader').hide();
            if (data['is_error'] == true) {
              swal({
                title: "Error!",
                text: data['error_msg'],
                showConfirmButton: false,
                timer: 2025,
                type: "error"
              });
              $(submit).attr('disabled', false);
            } else {
              window.location = data['redirect'];

            }
          },
          error: function() {
            swal({
              title: "Oops!",
              text: "Something went wrong , please check your login",
              showConfirmButton: false,
              timer: 1999,
              type: "warning"
            });
          }
        });
      },
      errorPlacement: function(error, element) {
        error.insertAfter(element.parent());
      },
    });
  }
</script>