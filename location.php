<?php

  try{
  $pdo = new PDO("mysql:host=localhost;dbname=pos_db","root","");
  //echo "connection Sucessfull";
  }
  
  catch(PDOException $f){
    echo $f->getmessage();
  }

  //include_once"conectdb.php";
  session_start();
  if ($_SESSION["useremail"]=="" ){
    header("location:index.php");
  }
  
  include_once"header.php";
  if(isset($_POST["btnSave"])){
    $loc=$_POST["txtloc"];
      
    if(empty($loc)){
      $eror="<script type='text/javascript'>
        jQuery(function validation(){
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Failed',
            text: 'Field is empty',
            showConfirmButton: false,
            timer: 3000
          })
        });  
      </script>";
      echo $eror;
    }
 
    if(!isset($eror)){
      $insert=$pdo->prepare("insert into tbl_location(loc) values(:loc)");
      $insert->bindParam(":loc",$loc);
          
      if($insert->execute()){  
        echo "<script type='text/javascript'>
          jQuery(function validation(){
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Done',
              text: 'Location added succesfully',
              showConfirmButton: false,
              timer: 3000
            })
          });
        </script>";
      }
    }
  }


  if(isset($_POST["btndelete"])){
    $dltid=$_POST["btndelete"];
    $delete=$pdo->prepare("delete from tbl_location where locid='$dltid'");
   
    if ($delete->execute()){
      echo "<script type='text/javascript'>
        jQuery(function validation(){
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Deleted',
            text: 'Location deleted succesfully',
            showConfirmButton: false,
            timer: 3000
          })
        });
      </script>";

    }else{
      echo "<script type='text/javascript'>
        jQuery(function validation(){
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Can not Delet',
            text: 'Location not deleted',
            showConfirmButton: false,
            timer: 3000
          })    
        });
      </script>";
    }  
  }
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Locations</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
          <!-- <li class="breadcrumb-item"><a href="logout.php">LOGOUT</a></li> -->
          <li class="breadcrumb-item active">Admin Dashboard</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content container-fluid">
    <div class="row">
      <!-- left column -->
      <div class="col-md-6">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Location Addition</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" action="" method="post">
            <div class="card-body">
              <div class="form-group">
                <label for="exampleInputEmail1">Location Name</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Name" name="txtloc">
              </div>  
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-primary" name="btnSave">Submit</button>
            </div>
           </form>
        </div>
        <!-- /.card -->
      </div>
      <!--/.col (left) -->
      <!-- right column -->

      <div class="col-md-6">    
        <div class="card card-success">
            <div class="card-header">
              <h3 class="card-title">LOCATIONS</h3>
            </div>
          <div class="card-body">
            <form  action="" method="POST">
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <td>No.</td>
                    <td>LOCATION</td>
                    <!-- <td>EDIT</td> 
                    also add this in td below
                    <td><button input type=\"submit\" value=\".$row->locid.\" class=\"btn btn-success\" name=\"btnedit\">EDIT</button></td> -->
                    <td>DELETE</td>
                  </tr>  
                </thead>

                <tbody>
                  <?php  
                    $selectloc=$pdo->prepare("select * from tbl_location order by locid desc");
                    $selectloc->execute();
                    
                    while($row=$selectloc->fetch(PDO::FETCH_OBJ))
                    {echo "
                      <tr>
                        <td>$row->locid</td>
                        <td>$row->loc</td>
                        <td><button input type=\"submit\" value=" .$row->locid."\"  class=\"btn btn-danger\" name=\"btndelete\">DELETE</button></td>
                      </tr>";
                    }
                  ?>
                </tbody>
              </table>
            </form>
          </div>
        </div>
      </div>
      <!-- /.right column -->
    </div> 
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>


<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
  <div class="p-3">
    <h5>Title</h5>
    <p>Sidebar content</p>
  </div>
</aside>
<!-- /.control-sidebar -->


<?php
include_once"footer.php";
?>