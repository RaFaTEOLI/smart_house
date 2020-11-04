<?php
require_once("../../root.php");
require_once($root . "/includes/parametros.php");
require_once($root . "/includes/conexao/conn.php");


testSession();
testHouseSession();

include($root . "/dao/DaoRotina.php");
include($root . "/dao/DaoAparelho.php");

$daoRotina = new DaoRotina();
$daoAparelho = new DaoAparelho();



if (isset($_GET["id"])) {
  $where = $_GET["id"];
  $rotina = $daoRotina->getRotina($conn, $where);
} else {
  if (isset($_POST["id"])) {
    $where = $_POST["id"];
    $rotina = $daoRotina->getRotina($conn, $where);
  }

  if (!isset($_POST["id"])) {
    header("Location: rotinas.php");
  }
}

$aparelhos = $daoAparelho->getAparelhos($conn, $_SESSION["casaId"]);

if (isset($_POST["aparelhoId"]) && isset($_POST["dataHora"]) && isset($_POST["descricao"])) {
  echo '<input type="hidden" id="rotinaDuplicada" value="false">';
  if ($daoRotina->alterarRotina($conn, $_POST)) {
    header("Location: rotinas.php?success=1");
  } else {
    header("Location: rotinas.php?success=0");
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
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="/plugins/jqvmap/jqvmap.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="/plugins/toastr/toastr.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="/plugins/summernote/summernote-bs4.css">
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
              <h1 class="m-0 text-dark">Rotinas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Rotinas</a></li>
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
                  <h3 class="card-title">Alterar Rotina</h3>
                  <a type="button" href="rotinas.php" class="btn btn-secondary btn-sm"><i class="fas fa-undo"></i></a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form role="form" id="quickForm" action="alterar_rotina.php" method="POST" enctype="multipart/form-data">
                  <div class="card-body">
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label>Aparelho</label>
                        <select class="form-control select2" name="aparelhoId">
                          <option value="">Selecione um aparelho</option>
                          <?php
                          while ($aparelho = mysqli_fetch_assoc($aparelhos)) {
                            if ($aparelho["aparelhoId"] == $rotina["aparelhoId"]) {
                          ?>
                              <option value="<?php echo $aparelho["aparelhoId"] ?>" selected>
                                <?= $aparelho["aparelhoId"] . ' - ' . $aparelho["nome"] ?>
                              </option>
                            <?php
                            } else {
                            ?>
                              <option value="<?php echo $aparelho["aparelhoId"] ?>">
                                <?= $aparelho["aparelhoId"] . ' - ' . $aparelho["nome"] ?>
                              </option>
                          <?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="dataHora">Data Hora</label>
                        <div class="input-group date" id="dataHora" data-target-input="nearest">
                          <input type="text" name="dataHora" class="form-control datetimepicker-input" data-target="#dataHora" value="<?= date("d/m/Y H:i:s", strtotime($rotina["dataHora"])) ?>" />
                          <div class="input-group-append" data-target="#dataHora" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-10">
                        <label for="descricao">Descrição</label>
                        <input type="text" name="descricao" class="form-control" id="descricao" placeholder="Digite uma descrição" value="<?= $rotina["descricao"] ?>">
                      </div>
                      <div class="form-group col-md-2">
                        <label for="acao">Ação</label><br>
                        <input type="checkbox" name="acao" data-bootstrap-switch data-off-color="danger" data-on-color="success" <?= $rotina["acao"] ? "checked" : "" ?>>
                      </div>
                    </div>
                    <input type="hidden" name="rotinaId" value="<?= $rotina["rotinaId"] ?>">
                    <div class="row">
                      <div class="col-md-6">
                        <button type="submit" name="submit" class="btn btn-primary">Alterar</button>
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
  <script src="/plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="/plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- jquery-validation -->
  <script src="/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="/plugins/jquery-validation/additional-methods.min.js"></script>
  <!-- Select2 -->
  <script src="/plugins/select2/js/select2.full.min.js"></script>
  <!-- InputMask -->
  <script src="/plugins/moment/moment.min.js"></script>
  <script src="/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- date-range-picker -->
  <script src="/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Bootstrap Switch -->
  <script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
  <!-- ChartJS -->
  <script src="/plugins/chart.js/Chart.min.js"></script>
  <!-- Sparkline -->
  <script src="/plugins/sparklines/sparkline.js"></script>
  <!-- DataTables -->
  <script src="/plugins/datatables/jquery.dataTables.js"></script>
  <script src="/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  <script>
    $(document).ready(function() {
      $('.select2').select2();

      const dateToday = new Date();
      //Timepicker
      $('#dataHora').datetimepicker({
        minDate: dateToday,
        format: 'DD/MM/YYYY HH:mm'
      });

      $("input[data-bootstrap-switch]").each(function() {
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
      });

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $('#quickForm').validate({
        rules: {
          aparelhoId: {
            required: true,
          },
          dataHora: {
            required: true,
          },
        },
        messages: {
          aparelhoId: {
            required: "Por favor selecione um aparelho!",
          },
          dataHora: {
            required: "Por favor informe uma data!",
          },
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
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

      const rotinaDuplicada = $('#rotinaDuplicada').val();

      if (rotinaDuplicada == "true") {
        printToast("Pessoa Duplicada!", "error");
      }
    });
  </script>
  <!-- JQVMap -->
  <script src="/plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="/plugins/jquery-knob/jquery.knob.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="/plugins/sweetalert2/sweetalert2.min.js"></script>
  <!-- Toastr -->
  <script src="/plugins/toastr/toastr.min.js"></script>
  <!-- daterangepicker -->
  <script src="/plugins/moment/moment.min.js"></script>
  <script src="/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="/plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="/dist/js/adminlte.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="/dist/js/pages/dashboard.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="/dist/js/demo.js"></script>
</body>

</html>
