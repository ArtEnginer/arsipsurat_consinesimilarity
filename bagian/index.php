<!DOCTYPE html>
<?php
session_start();
include "login/ceksession.php";
?>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Arsip Surat Kota Pekanbaru </title>

  <!-- Bootstrap -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="../assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">

  <!-- bootstrap-progressbar -->
  <link href="../assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
  <!-- JQVMap -->
  <link href="../assets/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
  <!-- bootstrap-daterangepicker -->
  <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">

  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">

      <!-- Profile and Sidebarmenu -->
      <?php
      include("sidebarmenu.php");
      ?>
      <!-- /Profile and Sidebarmenu -->

      <!-- top navigation -->
      <?php
      include("header.php");
      ?>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="row">
          <div class="col-md-12">
            <div class="">
              <div class="x_content">
                <div class="row">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <br><br>
                      <center>
                        <h1><b>Selamat Datang, <?php echo $_SESSION['nama']; ?></b></h1>
                      </center>
                      <br><br>
                    </div>
                  </div>
                  <?php include '../koneksi/koneksi.php';
                  $sql1    = "SELECT * FROM tb_suratmasuk WHERE disposisi1 = '" . $_SESSION['nama'] . "' || disposisi2 = '" . $_SESSION['nama'] . "' || disposisi3 = '" . $_SESSION['nama'] . "'";
                  $query1    = mysqli_query($db, $sql1);
                  $jumlah1   = mysqli_num_rows($query1);
                  ?>
                  <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="tile-stats">
                      <div class="icon"><i class="fa fa-inbox"></i>
                      </div>
                      <div class="count"><?php echo "$jumlah1" ?></div>
                      <h3>Surat Masuk</h3>
                      <p>Telah diarsipkan</p>
                    </div>
                  </div>
                  <?php include '../koneksi/koneksi.php';
                  $sql2    = "SELECT * FROM tb_suratkeluar WHERE nama_bagian = '" . $_SESSION['nama'] . "'";
                  $query2    = mysqli_query($db, $sql2);
                  $jumlah2   = mysqli_num_rows($query2);
                  ?>
                  <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="tile-stats">
                      <div class="icon"><i class="fa fa-send"></i>
                      </div>
                      <div class="count"><?php echo "$jumlah2" ?></div>
                      <h3>Surat Keluar</h3>
                      <p>Telah Diarsipkan</p>
                    </div>
                  </div>
                  <?php include '../koneksi/koneksi.php';
                  $sql3    = "SELECT * FROM tb_bagian";
                  $query3    = mysqli_query($db, $sql3);
                  $jumlah3   = mysqli_num_rows($query3);
                  ?>
                  <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="tile-stats">
                      <div class="icon"><i class="fa fa-group (alias)"></i>
                      </div>
                      <div class="count"><?php echo "$jumlah3" ?></div>

                      <h3>Bagian</h3>
                      <p>Telah Didaftarkan</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="">
                <h2>Pencarian Surat :</h2>
              </div>
              <div class="x_content">
                <form action="consinesimilarity.php" method="post">
                  <div class="form-group">
                    <!-- kata kunci -->
                    <label for="katakunci">Kata Kunci :</label>
                    <input type="text" class="form-control" id="katakunci" name="katakunci" placeholder="Masukkan Kata Kunci">
                  </div>
                  <div class="form-group">
                    <!-- jenis surat -->
                    <label for="jenissurat">Jenis Surat :</label>
                    <select class="form-control" id="jenissurat" name="jenissurat">
                      <option value="0">Pilih Jenis Surat</option>
                      <option value="1">Surat Masuk</option>
                      <option value="2">Surat Keluar</option>
                    </select>
                  </div>
                  <div class="row ">
                    <div class="col-md-6">
                      <div class="form-group">
                        <!-- tanggal awal -->
                        <label for="tglawal">Tanggal Awal :</label>
                        <input type="date" class="form-control" id="tglawal" name="tglawal">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <!-- tanggal akhir -->
                        <label for="tglakhir">Tanggal Akhir :</label>
                        <input type="date" class="form-control" id="tglakhir" name="tglakhir">
                      </div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary ">Cari</button>
                </form>
              </div>
            </div>
          </div>

          <div class="col-md-8">
            <div class="card x_panel">
              <div class="card-body">
                <div id="hasilpencarian"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <!-- NProgress -->
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <!-- Chart.js -->
  <script src="../assets/vendors/Chart.js/dist/Chart.min.js"></script>
  <!-- gauge.js -->
  <script src="../assets/vendors/gauge.js/dist/gauge.min.js"></script>
  <!-- bootstrap-progressbar -->
  <script src="../assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <!-- iCheck -->
  <script src="../assets/vendors/iCheck/icheck.min.js"></script>
  <!-- Skycons -->
  <script src="../assets/vendors/skycons/skycons.js"></script>
  <!-- Flot -->
  <script src="../assets/vendors/Flot/jquery.flot.js"></script>
  <script src="../assets/vendors/Flot/jquery.flot.pie.js"></script>
  <script src="../assets/vendors/Flot/jquery.flot.time.js"></script>
  <script src="../assets/vendors/Flot/jquery.flot.stack.js"></script>
  <script src="../assets/vendors/Flot/jquery.flot.resize.js"></script>
  <!-- Flot plugins -->
  <script src="../assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
  <script src="../assets/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
  <script src="../assets/vendors/flot.curvedlines/curvedLines.js"></script>
  <!-- DateJS -->
  <script src="../assets/vendors/DateJS/build/date.js"></script>
  <!-- JQVMap -->
  <script src="../assets/vendors/jqvmap/dist/jquery.vmap.js"></script>
  <script src="../assets/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
  <script src="../assets/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="../assets/vendors/moment/min/moment.min.js"></script>
  <script src="../assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>


  <script>
    $(document).ready(function() {
      $("form").on("submit", function(event) {
        event.preventDefault();

        var katakunci = $("#katakunci").val();
        var jenissurat = $("#jenissurat").val();
        var tglawal = $("#tglawal").val();
        var tglakhir = $("#tglakhir").val();

        $.ajax({
          url: "consinesimilarity.php",
          type: "POST",
          data: {
            katakunci: katakunci,
            jenissurat: jenissurat,
            tglawal: tglawal,
            tglakhir: tglakhir
          },
          success: function(data) {
            var results = JSON.parse(data);
            var output = "<h3>Hasil Pencarian:</h3><table class='table table-striped'><thead><tr><th>Perihal</th><th>Similarity</th><th>Action</th></tr></thead><tbody>";
            $.each(results, function(index, result) {
              if (result.similarity > 0) {
                var jenisSurat = (result.jenis_surat == 1) ? 'surat_masuk' : 'surat_keluar';
                var link = "<a class='btn btn-sm btn-success' href='" + jenisSurat + "/" + result.file + "' target='_blank'>Lihat Surat</a>";
                output += "<tr><td>" + result.perihal + "</td><td>" + result.similarity + "</td><td>" + link + "</td></tr>";
              }
            });
            output += "</tbody></table>";
            $("#hasilpencarian").html(output);
          }


        });
      });
    });
  </script>
</body>

</html>