<?php

  try {
      $pdo = new PDO("mysql:host=localhost;dbname=pos_db", "root", "");
      //echo "connection Sucessfull";
  } catch (PDOException $f) {
      echo $f->getmessage();
  }

  //include_once"conectdb.php";
  session_start();
  if ($_SESSION["useremail"] == "") {
      header("location:index.php");
  }
  include_once "header.php";

  if (isset($_POST["btnaddproduct"])) {
    $productname = $_POST["txtpname"];
    $category = $_POST["txtselect_option"];
    $purchaseprice = $_POST["txtprice"];
    $stock = $_POST["txtstock"];
    $expiration_date = date("Y-m-d H:i:s", strtotime($_POST["pexpiration_date"]));
    $expiration_time = date("Y-m-d H:i:s", strtotime($_POST["pexpiration_time"]));
    $loc = $_POST["txtloc"];
    $description = $_POST["txtdesc"];

    $insert = $pdo->prepare("insert into tbl_product(pname,pcategory,saleprice,pstock,pexpiration_date,pexpiration_time,ploc,pdescription) 
    values(:pname,:pcategory,:saleprice,:pstock,:pexpiration_date,:pexpiration_time,:ploc,:pdescription)");
    $insert->bindParam(":pname", $productname);
    $insert->bindParam(":pcategory", $category);
    $insert->bindParam(":saleprice", $purchaseprice);
    $insert->bindParam(":pstock", $stock);
    $insert->bindParam(":pexpiration_date", $expiration_date);
    $insert->bindParam(":pexpiration_time", $expiration_date);
    $insert->bindParam(":ploc", $loc);
    $insert->bindParam(":pdescription", $description);

    if ($insert->execute()) {
        echo "<script type='text/javascript'>
        jQuery(function validation(){
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Saved',
            text: 'Product Added Succesfully',
            showConfirmButton: false,
            timer: 3000
            })    
          });   
        </script>";
    } else {
        "<script type='text/javascript'>
        jQuery(function validation(){
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Failed',
            text: 'Unable to Add Product',
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
          <h1 class="m-0">Stock In</h1>
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
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title"><a href="productlist.php"
                class= "btn btn-primary" role="button" >
                Back To Product List
              </a>
            </h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          
          <form  action="" method="post">
            <div class="card-body">
              <div class="form-group">
                <label>Items Name</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Name" name="txtpname">
              </div>
                  
              <div class="form-group">
                <label>Category</label>
                <select  class="form-control"  placeholder="Choose category to bind" name="txtselect_option" required>
                <option value="" disabled selected>Select Category</option>
                    
                <?php
                $select = $pdo->prepare(
                  "select *from tbl_category order by catid desc"
                );
                $select->execute();
                  while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                    extract($row); ?>
                      <option>         
                      <?php echo $row["category"]; ?>
                      </option>        
                  <?php
                    }
                  ?>
                </select>
              </div>
  
              <div class="form-group">
                <label for="exampleInputPassword1">Price</label>
                <input type="text" class="form-control"  placeholder="Enter price to be added" name="txtprice" required>
              </div>
 
              <div class="form-group">
                <label for="exampleInputPassword1">Stock</label>
                <input type="text" class="form-control"  placeholder="Enter Quantity" name="txtstock" required>
               </div>

              <div class="form-group">
                <label>Expiration Date</label>
                <div class="input-group date"   data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="reservationdate" name="pexpiration_date" required >
                  <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i>
                    </div>
                  </div>
                </div>
              </div> 
              
            <!-- Unit of measurement dropdown -->
              <!-- <div class="form-group">
                <label>Unit of Measurement</label>
                    <select  class="form-control"  placeholder="Choose unit of measuremnet" name="" required>
                        <option value="piece">Piece</option>
                        <option value="container">Container</option>
                        <option value="box">Box</option>
                        <!- <option value=""> </option> -->
                    <!-- </select>    
              </div> --> 
                 
              <div class="form-group">
                <label>Location</label>
                <select  class="form-control"  placeholder="Choose location to bind" name="txtloc" required>
                  <option value="" disabled selected>Select Location</option>
                          
                  <?php
                    $selectloc = $pdo->prepare(
                      "select *from tbl_location order by locid desc"
                    );
                    
                    $selectloc->execute();
                      while ($row = $selectloc->fetch(PDO::FETCH_ASSOC)) {
                        extract($row); ?>
                          <option>
                                          
                        <?php echo $row["loc"]; ?>
                          </option>
                        <?php
                      }
                  ?>         
                </select>      
              </div>
                 
              <div class="form-group">
                <label>Description</label>
                <textarea type="text" class="form-control"  placeholder="Enter details" name="txtdesc" rows="4" ></textarea>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-primary" name="btnaddproduct">Add Item</button> 
            </div>
          
          </form>
        </div>
        <!-- /.card -->           
      </div>
      <!--/.col (left) -->
      <!-- right column -->       
      <!-- /.right column -->
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
  $(document).ready(function(){    
  $("#reservationdate").datetimepicker({pickTime: true });
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

<?php include_once "footer.php";
?>