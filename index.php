
<?php 
include 'INC/header.php'; 
?>
<?php
include 'LIB/config.php'; 
include 'LIB/Database.php'; 
$db = new Database();
?>

        <div class="myform">
   <?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$permited  = array('jpg', 'jpeg', 'png', 'gif');
		
		$file_name =  $_FILES['image']['name'];
		$file_size =  $_FILES['image']['size'];
		$file_tmp  =  $_FILES['image']['tmp_name'];
        
        $div = explode('.', $file_name);
        $file_ext = strtolower(end($div));
       $unique_image = substr(md5(time()), 0 , 10). '.'.$file_ext;
       $uploaded_image = "UPLOADS/".$unique_image;
      
       if (empty($file_name)) {
       	echo "<span class='error'>Please Select any Image.
		      </span>";
       }elseif ($file_size > 1048576) {
       	echo "<span class='error'>Image Size should be less then 1MB.
		      </span>";
       }elseif (in_array($file_ext, $permited) === false) {
       	echo "<span class='error'>You can upload only:-".implode(',', $permited)."</span>";
       }else{  
		move_uploaded_file($file_tmp, $uploaded_image);
		$query = "INSERT INTO tbl_image(image) VALUES('$uploaded_image')";
		$inserted_rows = $db->insert($query);
		if ($inserted_rows) {
		 echo "<span class='success'>Image Inserted Successfully.
		      </span>";
		}else {
		 echo "<span class='error'>Image Not Inserted !</span>";
		}
		}
    }
         ?>

        	<form action="" method="post" enctype="multipart/form-data">
        		
            <table>
            	 <tr>
            	 	<td>Select Image</td>
            	 	<td><input type="file" name="image"/></td>
            	 </tr>

                 <tr>
                  <td></td>
                <td><input type="submit" name="submit" value="Upload"/></td>

                 </tr>
   
              </table>
        	</form>
        	<?php 
            $query = " select * from tbl_image order by id desc limit 1";
            $getImage = $db->select($query);
            if ($getImage) {
            	while ($result = $getImage->fetch_assoc()){
        	?>
     <!--     <img src="<?php echo $result['image']; ?>" height="100px" width="200px"/> -->
        	<?php } } ?>

         <table>
         	
            <tr>
            	<th>NO.</th>
            	<th>Image.</th>
            	<th>Action.</th>
            </tr>

            <?php 
            if (isset($_GET['del'])) {
            	$id = $_GET['del'];
            
          $getquery = " select * from tbl_image where id ='$id'";
            $getImage = $db->select($getquery);
            if ($getImage) {
            	while ($imgdata = $getImage->fetch_assoc()){	
            		$delimg = $imgdata['image'];
            		unlink($delimg);
            	}
            }
           
            $query = "delete from tbl_image where id = '$id'";
            $delImage = $db->delete($query);
            if ($delImage) {
		 echo "<span class='success'>Image Deleted Successfully.
		      </span>";
		}else {
		 echo "<span class='error'>Image Not Deleted !</span>";
		}
         }
            ?>

          <?php 
            $query = " select * from tbl_image";
            $getImage = $db->select($query);
            if ($getImage) {
            	$i=0;
            	while ($result = $getImage->fetch_assoc()){
            		$i++;
          ?>
          
          <tr>
          	<td><?php echo $i;?></td>
          	<td><img src="<?php echo $result['image']; ?>" height="40px" width="50px"/></td>
          	<td><a href="?del=<?php echo $result['id'];?>">Delete</a></td>

          </tr>
      <?php } } ?>
         </table>


        </div>

 <?php include 'INC/footer.php'; ?>