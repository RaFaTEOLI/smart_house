<?php
  require_once("../../root.php");
  require_once($root . "/includes/parametros.php");
  require_once($root . "/includes/conexao/conn.php");

  testSession();
  testHouseSession();

  include($root . "/dao/DaoCasa.php");
  include($root . "/dao/DaoPessoa.php");

  $daoCasa = new DaoCasa();
  $daoPessoa = new DaoPessoa();

  $pessoas = $daoPessoa->getPessoas($conn);
  $estados = getEstados($conn);

  if (isset($_POST["nome"]) && isset($_POST["proprietario"]) && isset($_POST["cep"])) {
    if(!$daoCasa->validarCasa($conn, $_POST["cep"])) {
      echo '<input type="hidden" id="casaDuplicada" value="false">';

      $target_dir = "../../dist/img/";
      $date = date_create();
      $basename_file = date_timestamp_get($date) . preg_replace('/\s/','', basename($_FILES["fileToUpload"]["name"]));
      $target_file = $target_dir . $basename_file;
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      // Check if image file is a actual image or fake image
      if(isset($_POST["submit"])) {
          $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
          if($check !== false) {
              echo "File is an image - " . $check["mime"] . ".";
              $uploadOk = 1;
          } else {
              echo "File is not an image.";
              $uploadOk = 0;
          }
      }
      // Check if file already exists
      if (file_exists($target_file)) {
          echo "Sorry, file already exists.";
          $uploadOk = 0;
      }
      // Check file size
      if ($_FILES["fileToUpload"]["size"] > 1000000) {
          echo "Sorry, your file is too large: " . $_FILES["fileToUpload"]["size"];
          $uploadOk = 0;
      }
      // Allow certain file formats
      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" ) {
          echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
      }
      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
          echo "Sorry, your file was not uploaded.";
      // if everything is ok, try to upload file
      } else {
          if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
              $caminho_foto = str_replace('../../', '/smart_house/', $target_file);
              echo "The file ". $basename_file . " has been uploaded on " . $caminho_foto;

              if($daoCasa->salvarCasa($conn, $_POST, $caminho_foto)) {
                header("Location: casas.php?success=1");
              }
          } else {
              echo "Sorry, there was an error uploading your file.";
          }
      }
       
    } else {
      echo '<input type="hidden" id="casaDuplicada" value="true">';
    }
    
  }

  

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $titulo ?></title>
  <link rel="shortcut icon" href="<?php echo $favicon ?>" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/smart_house/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="/smart_house/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/smart_house/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/smart_house/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="/smart_house/plugins/jqvmap/jqvmap.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="/smart_house/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="/smart_house/plugins/toastr/toastr.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="/smart_house/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/smart_house/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/smart_house/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/smart_house/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="/smart_house/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="/smart_house/plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script>
  </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include_once($root . '/template/navbar.php') ?>
  <!-- /.navbar -->

  <!-- Sidebar -->
  <?php include_once($root . '/template/sidebar.php') ?>
  <!-- /.sidebar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Casas</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Casas</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Cadastrar Casas</h3>
                <a type="button" href="casas.php" class="btn btn-secondary btn-sm"><i class="fas fa-undo"></i></a>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form role="form" id="quickForm" action="cadastrar_casa.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">
                    <div class="form-group col-md-6">
                        <label for="nome">Nome *</label>
                        <input type="text" name="nome" class="form-control" id="nome" placeholder="Digite o nome">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="endereco">Endereço *</label>
                        <input type="text" name="endereco" class="form-control" id="endereco" placeholder="Digite o endereço">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-4">
                        <label for="cidade">Cidade *</label>
                        <input type="text" name="cidade" class="form-control" id="cidade" placeholder="Digite a cidade">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="estado">Estado *</label>
                        <select class="form-control select2" name="estado" id="estado" required>
                        <option value="">Selecione o estado</option>
                            <?php
                                while($estado = mysqli_fetch_assoc($estados)) {
                            ?>
                            <option value="<?php echo $estado["estadoId"] ?>"><?php echo utf8_encode($estado["nome"]) ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="cep">CEP *</label>
                        <input type="text" name="cep" class="form-control" id="cep" placeholder="Digite o CEP" data-inputmask='"mask": "99999-999"' data-mask>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                        <label for="proprietario">Proprietário *</label>
                        <select class="form-control select2" name="proprietario" id="proprietario" required>
                        <option value="">Selecione o proprietário</option>
                            <?php
                                while($pessoa = mysqli_fetch_assoc($pessoas)) {
                            ?>
                            <option value="<?php echo $pessoa["pessoaId"] ?>"><?php echo utf8_encode($pessoa["nome"] . ' ' . $pessoa["sobrenome"]) ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="notificacaoEmail">Foto *</label>
                      <input type="file" name="fileToUpload" class="form-control" id="fileToUpload" placeholder="Escolha uma foto">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <button type="submit" name="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include_once($root . '/template/footer.php') ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/smart_house/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="/smart_house/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- jquery-validation -->
<script src="/smart_house/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="/smart_house/plugins/jquery-validation/additional-methods.min.js"></script>
<!-- Select2 -->
<script src="/smart_house/plugins/select2/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="/smart_house/plugins/moment/moment.min.js"></script>
<script src="/smart_house/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/smart_house/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="/smart_house/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="/smart_house/plugins/sparklines/sparkline.js"></script>
<!-- DataTables -->
<script src="/smart_house/plugins/datatables/jquery.dataTables.js"></script>
<script src="/smart_house/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script>
$(document).ready(function () {
  $('.select2').select2();

  $('[data-mask]').inputmask();

  //Initialize Select2 Elements
  $('.select2bs4').select2({
    theme: 'bootstrap4'
  });

  $('#quickForm').validate({
    rules: {
      nome: {
        required: true,
      },
      endereco: {
        required: true,
      },
      cidade: {
        required: true,
      },
      estado: {
        required: true,
      },
      cep: {
        required: true,
      },
      proprietario: {
        required: true,
      },
      fileToUpload: {
        required: true,
      },
    },
    messages: {
      nome: {
        required: "Por favor informe um nome!",
      },
      endereco: {
        required: "Por favor informe um endereço!",
      },
      cidade: {
        required: "Por favor informe a cidade!",
      },
      estado: {
        required: "Por favor selecione um estado!",
      },
      cep: {
        required: "Por favor informe um CEP válido!",
      },
      proprietario: {
        required: "Por favor selecione o proprietário!",
      },
      fileToUpload: {
        required: "Por favor informe uma foto!",
      },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
$(function() {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });

    const printToast = (mensagem, tipo = 'warning') => {
      Toast.fire({
        type: tipo,
        title: `${mensagem}!`
      });
    }

    const casaDuplicada = $('#casaDuplicada').val();

    if (casaDuplicada == "true") {
      printToast("Casa Duplicada!", "error");
    }
  });
</script>
<!-- JQVMap -->
<script src="/smart_house/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="/smart_house/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="/smart_house/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- SweetAlert2 -->
<script src="/smart_house/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="/smart_house/plugins/toastr/toastr.min.js"></script>
<!-- daterangepicker -->
<script src="/smart_house/plugins/moment/moment.min.js"></script>
<script src="/smart_house/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="/smart_house/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="/smart_house/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="/smart_house/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="/smart_house/dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="/smart_house/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/smart_house/dist/js/demo.js"></script>
</body>
</html>
