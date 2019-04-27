<?php
session_start();
if(!$_SESSION['user_details']['user_id'])  
{  
    header("Location: index.php");
} 

if(!($_SESSION['user_details']['role_code'] == 1) or ($_SESSION['user_details']['role_code'] == 2) or ($_SESSION['user_details']['role_code'] == 3) or ($_SESSION['user_details']['role_code'] == 4))
{  
    header("Location: user_dashboard.php");
} 

if(isset($_POST['search']))
{
	
	if(empty($_POST['asc_name3']))
		$asc_name = '';
	else
		$asc_name = $_POST['asc_name3'];
	
	if(empty($_POST['year_id3']))
		$year_id = $_SESSION['user_details']['acyear_id'];
	else
		$year_id = $_POST['year_id3'];
		
	if(empty($_POST['user_id3']))
		$user_id = '';
	else
		$user_id = $_POST['user_id3'];
	
	
	if($user_id == 'Select' and $year_id == 'Select' and $asc_name == '')
	{
	$query = "SELECT cdt.srno, ayt.acyear, sect_name, class_name, div_name, total_stud, st.status as 'sect_status', ct.status as 'class_status', cdt.status as 'class_det_status', m.full_name, cdt.assigned_to FROM class_details_tb cdt join class_tb ct on cdt.class_id = ct.class_id join division_tb dt on cdt.div_id = dt.div_id join section_tb st on st.sect_id = ct.sect_id join ac_year_tb ayt on cdt.acyearid = ayt.acyearid join member m on cdt.assigned_to = m.user_id order by cdt.class_id, dt.div_id";
	
    $search_result = filterTable($query);
	
	}
	
	elseif($user_id == 'Select' and $year_id == 'Select' and $asc_name != '')
	{
	$year_id = $_SESSION['user_details']['acyear_id'];
		
		$query = "SELECT cdt.srno, ayt.acyear, sect_name, class_name, div_name, total_stud, st.status as 'sect_status', ct.status as 'class_status', cdt.status as 'class_det_status', m.full_name, cdt.assigned_to FROM class_details_tb cdt join class_tb ct on cdt.class_id = ct.class_id join division_tb dt on cdt.div_id = dt.div_id join section_tb st on st.sect_id = ct.sect_id join ac_year_tb ayt on cdt.acyearid = ayt.acyearid join member m on cdt.assigned_to = m.user_id where (cdt.acyearid = '$year_id' and class_name like '$asc_name%') or (cdt.acyearid = '$year_id' and sect_name like '$asc_name%')  order by cdt.class_id, dt.div_id";
	
    $search_result = filterTable($query);
	
	}
	else
	{
	
	if($user_id == 'Select')
	{
	
		$query = "SELECT cdt.srno, ayt.acyear, sect_name, class_name, div_name, total_stud, st.status as 'sect_status', ct.status as 'class_status', cdt.status as 'class_det_status', m.full_name, cdt.assigned_to FROM class_details_tb cdt join class_tb ct on cdt.class_id = ct.class_id join division_tb dt on cdt.div_id = dt.div_id join section_tb st on st.sect_id = ct.sect_id join ac_year_tb ayt on cdt.acyearid = ayt.acyearid join member m on cdt.assigned_to = m.user_id where (cdt.acyearid = '$year_id' and sect_name like '$asc_name%') or (cdt.acyearid = '$year_id' and class_name like '$asc_name%') order by cdt.class_id, dt.div_id";
	
    $search_result = filterTable($query);
	}
	else
	{
	$query = "SELECT cdt.srno, ayt.acyear, sect_name, class_name, div_name, total_stud, st.status as 'sect_status', ct.status as 'class_status', cdt.status as 'class_det_status', m.full_name, cdt.assigned_to FROM class_details_tb cdt join class_tb ct on cdt.class_id = ct.class_id join division_tb dt on cdt.div_id = dt.div_id join section_tb st on st.sect_id = ct.sect_id join ac_year_tb ayt on cdt.acyearid = ayt.acyearid join member m on cdt.assigned_to = m.user_id where (cdt.acyearid = '$year_id' and m.user_id = $user_id and sect_name like '$asc_name%') or (cdt.acyearid = '$year_id' and m.user_id = $user_id and class_name like '$asc_name%') order by cdt.class_id, dt.div_id";
	
    $search_result = filterTable($query);
	}
	
	
	
	}
	
}
else 
{
	$year_id = $_SESSION['user_details']['acyear_id'];

    $query = "SELECT cdt.srno, ayt.acyear, sect_name, class_name, div_name, total_stud, st.status as 'sect_status', ct.status as 'class_status', cdt.status as 'class_det_status', m.full_name, cdt.assigned_to FROM class_details_tb cdt join class_tb ct on cdt.class_id = ct.class_id join division_tb dt on cdt.div_id = dt.div_id join section_tb st on st.sect_id = ct.sect_id join ac_year_tb ayt on cdt.acyearid = ayt.acyearid join member m on cdt.assigned_to = m.user_id where cdt.acyearid = '$year_id' order by cdt.class_id, dt.div_id";
	
    $search_result = filterTable($query);
}

function filterTable($query)
{
	include('common/db_connect.php');
	if (!$conn) 
	{
    	die("Connection failed: " . mysqli_connect_error());
	}	
    $filter_Result = mysqli_query($conn, $query);
    return $filter_Result;
}

if(isset($_POST['update']))  
{	
	$srno  = $_POST["srno2"];	
	$year_id  = $_POST["year_id2"];	
	//$sect_id = $_POST["sect_id2"];
	$class_id = $_POST["class_id2"];
	$div_id = $_POST["div_id2"];	
	$total_stud = $_POST["stud_total2"];
	$user_id = $_POST["user_id2"];
	$status = $_POST["cds_status2"];
	
	include('common/db_connect.php');
	if (!$conn) 
	
	{
	   	die("Connection failed: " . mysqli_connect_error());
	}	
	
	$sql = "select * from class_details_tb where class_id = '$class_id' and div_id = '$div_id' and total_stud = '$total_stud' and acyearid = '$year_id' and assigned_to = '$user_id' and status = '$status'";
	
	$res = mysqli_query($conn,$sql);
	
	if (mysqli_num_rows($res) > 0) 
	{
		echo "<script>alert('Record is already exists !')</script>";
		echo "<script>window.open('assign_teacher_classdiv_each_year.php','_self')</script>";
	}
	else
	{
			$sql1="update class_details_tb set total_stud = '$total_stud', assigned_to = '$user_id', status = '$status' where srno = '$srno'";
		if(mysqli_query($conn,$sql1))
		{
			echo "<script> alert('Record is successfully updated !'); </script>";
			echo "<script>window.open('assign_teacher_classdiv_each_year.php','_self')</script>";
		}
		else
		{
			echo "<script> alert('Problem in Updation !'); </script>";
		}		
	}
}

if(isset($_POST['save']))  
{
		include('common/db_connect.php');
		if (!$conn) 
		{
    		die("Connection failed: " . mysqli_connect_error());
		}

		$class_id = $_POST["class_id1"];
		$div_id = $_POST["div_id1"];
		$user_id = $_POST["user_id1"];
		//$year_id = $_POST["year_id1"];
		$year_id = $_SESSION['user_details']['acyear_id'];
		
		$sql = "select srno from class_details_tb where class_id = '$class_id' and div_id = '$div_id' and acyearid = '$year_id'";
		$result = mysqli_query($conn, $sql);
		$n = mysqli_num_rows($result);
		if($n == 1)
		{
			while($row = mysqli_fetch_array($result))
			{
				echo $srno = $row['srno'];
			}
	
			$sql="UPDATE class_details_tb SET assigned_to = $user_id WHERE srno = $srno and acyearid = $year_id";
			if(mysqli_query($conn,$sql))
			{
				echo "<script> alert('Teacher is successfully assigned to selected class-division !'); </script>";
				echo "<script>window.open('assign_teacher_classdiv_each_year.php','_self')</script>";
			}
			else
			{
				echo "<script> alert('Problem in saving !'); </script>";

			}	
		}
		else
		{
			echo "<script> alert('Record is not exists !'); </script>";
		}
}

?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>EduEra</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">


    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">

    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

	<script src="assets/js/datetime.js"></script>
    
    
     	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 	 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>

function form_update()
{
	valid = true;
	var div_id = document.getElementById('div_id2').value;
	var stud_total = document.getElementById('stud_total2').value;
		
	if(div_id == "")
	{
		alert("Division is not available !");
		document.getElementById('div_id2').focus();
		return false;
	}
	else if(stud_total == "")
	{
		alert("Total strength field should not be left blank !");
		document.getElementById('stud_total2').focus();
		return false;
	}
	else if (confirm("Do you want to update selected yearwise class division details?"))
	{
		return valid;
	}
	else
	{
		return false;
	}	
}


function form_save()
{
	valid = true;	
	
	var sect_id = document.getElementById('sect_id1').value;
	var class_id = document.getElementById('class_id1').value;
	var div_id = document.getElementById('div_id1').value;
	var user_id = document.getElementById('user_id1').value;
	
	if(sect_id == "")
	{
		alert("Section is not available !");
		document.getElementById('sect_id1').focus();
		return false;
	}
	else if(class_id == "")
	{
		alert("Class is not available !");
		document.getElementById('class_id1').focus();
		return false;
	}
	else if(div_id == "")
	{
		alert("Division is not available !");
		document.getElementById('div_id1').focus();
		return false;
	}
	else if(user_id == "")
	{
		alert("Teacher is not available !");
		document.getElementById('user_id1').focus();
		return false;
	}
	else if (confirm("Do you want to save new yearwise class division details?"))
	{
		return valid;
	}
	else
	{
		return false;
	}	
}

function getClassForSectionForTeacherAssign()
{
	var sect_id = document.getElementById('sect_id1').value;
	var year_id = document.getElementById('year_id1').value;	
	var queryString = "sect_id=" + sect_id;
	queryString = queryString + "&year_id=" + year_id;
	//alert(queryString);	  
	var1=new XMLHttpRequest();
	var1.open("POST","getClassForSectionForTeacherAssign.php",false);
	var1.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	var1.send(queryString);
	document.getElementById("class_id1").innerHTML = var1.responseText;
	getAllAssignDivisionForClass();	
	//getNotAssignTeacherForClass();
}



function getClassForSectionForTeacherAssignUpdate()
{
	var sect_id = document.getElementById('sect_id2').value;
	var year_id = document.getElementById('year_id2').value;	
	var queryString = "sect_id=" + sect_id;
	queryString = queryString + "&year_id=" + year_id;
	//alert(queryString);	  
	var1=new XMLHttpRequest();
	var1.open("POST","getClassForSectionForTeacherAssign.php",false);
	var1.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	var1.send(queryString);
	document.getElementById("class_id2").innerHTML = var1.responseText;
	getAllAssignDivisionForClassUpdate();	
	//getNotAssignTeacherForClass();
}


function check_first_combo()
{
	var x = document.getElementById('year_id3').value;
	if (x=='Select')
	{
		alert("First select academic year");
		document.getElementById('user_id3').selectedIndex = 0;
	}

}


function getAllAssignDivisionForClass()
{
	var class_id = document.getElementById('class_id1').value;
	var year_id = document.getElementById('year_id1').value;
			
	var queryString = "class_id=" + class_id;
	queryString = queryString + "&year_id=" + year_id;
	//alert(queryString);	  
	var1=new XMLHttpRequest();
	var1.open("POST","getAllAssignDivisionForClass.php",false);
	var1.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	var1.send(queryString);
	document.getElementById("div_id1").innerHTML = var1.responseText;
}



function getAllAssignDivisionForClassUpdate()
{
	var class_id = document.getElementById('class_id2').value;
	var year_id = document.getElementById('year_id2').value;
			
	var queryString = "class_id=" + class_id;
	queryString = queryString + "&year_id=" + year_id;
	//alert(queryString);	  
	var1=new XMLHttpRequest();
	var1.open("POST","getAllAssignDivisionForClass.php",false);
	var1.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	var1.send(queryString);
	document.getElementById("div_id2").innerHTML = var1.responseText;
}


function getTeachers()
{
	var role_code = document.getElementById('role_code1').value;			
	var queryString = "role_code=" + role_code;
	//alert(queryString);	  
	var1=new XMLHttpRequest();
	var1.open("POST","getTeachers.php",false);
	var1.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	var1.send(queryString);
	document.getElementById("user_id1").innerHTML = var1.responseText;
}


function changeColor(id)
{
	var image =  document.getElementById(id);

    if (image.getAttribute('src') == "images/present.png")
    {
        image.src = "images/absent.png";
		document.getElementById("cds_status2").value = 1;
		document.getElementById("p1").style.color = "red";
		document.getElementById("p1").innerHTML = "IN-ACTIVE";
    }
    else
    {
        image.src = "images/present.png";
		document.getElementById("cds_status2").value = 0;
		document.getElementById("p1").style.color = "green";
		document.getElementById("p1").innerHTML = "ACTIVE";
    }
}

function form_confirm()
{
	valid = true;
	if (confirm("Do you want to select selected class and div for updation?"))
	{
		return valid;
	}
	else
	{
		alert("Operation is cancelled!");
		return false;
	}	
}

</script>

</head>

<body onLoad="display_ct();">
    <!-- Left Panel -->
    
    <aside id="left-panel" class="left-panel">  
	<?php
			include('common/left_panel.php');	
	?>
    </aside><!-- /#left-panel -->

    <div id="right-panel" class="right-panel">
    
 
        <!-- Header-->
       <?php
			include('common/topheader_panel.php');	
		?>
        <!-- Header-->

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Assign Teacher to Class-Division</h1>
                    </div>
                </div>
            </div>
           <div class="col-sm-8">
        <?php
			include('common/breadcrumb_right.php');	
		?>
            </div>
        </div>

        <div class="content mt-3">
            <div class="animated fadeIn">

<div style="padding:25px; width:100%;">
              
<?php

include('common/db_connect.php');

// Check connection
if (!$conn) 
{
    die("Connection failed: " . mysqli_connect_error());
}
?>

<?php
if(isset($_POST['submit']))
{

$a = $_POST['srno3'];

$result = mysqli_query($conn, "SELECT * From class_details_tb where srno = ".$a."");
										$rowsclassdetails="";
										if(mysqli_num_rows($result)>0){
										$rowsclassdetails = mysqli_fetch_array($result);
										}
										
$result = mysqli_query($conn, "SELECT * From ac_year_tb where acyearid = ".$rowsclassdetails['acyearid']."");
										$rowsyear="";
										if(mysqli_num_rows($result)>0){
										$rowsyear = mysqli_fetch_array($result);
										}
										
$result = mysqli_query($conn, "SELECT * From class_tb where class_id =".$rowsclassdetails['class_id']."");
										$rowsclass="";
										if(mysqli_num_rows($result)>0){
										$rowsclass = mysqli_fetch_array($result);
										}										

$result = mysqli_query($conn, "SELECT * From section_tb where sect_id =".$rowsclass['sect_id']."");
										$rowssect="";
										if(mysqli_num_rows($result)>0){
										$rowssect = mysqli_fetch_array($result);
										}	
										

$result = mysqli_query($conn, "SELECT * From division_tb where div_id = ".$rowsclassdetails['div_id']."");
										$rowsdiv="";
										if(mysqli_num_rows($result)>0){
										$rowsdiv = mysqli_fetch_array($result);
										}

$result = mysqli_query($conn, "SELECT * From member where user_id = ".$rowsclassdetails['assigned_to']."");
										$rowsteacher="";
										if(mysqli_num_rows($result)>0){
										$rowsteacher = mysqli_fetch_array($result);
										}


?>
                             <div class="col-lg-12">                   
                                                <div class="card" id="id2">
                                                    <div class="card-header alert alert-danger">
                                                        <strong>Update Existing Yearwise Class Division Details -</strong>
                                                    </div>
    
                                                <div class="card-body card-block">

         
        
<form action="" name="form2" id="form2" method="post">
<div class="row" style="padding-left:15px;" >

<table width="100%" align="center" border="0">
  <tr>
  
     <input type="hidden" id="srno2" name="srno2" class="form-control" style="width:240px;" maxlength="3" value="<?php echo $a; ?>" />
   <td width="25%">
<div class="form-group"><label class="form-control-label">Select Academic Year</label>
<select id="year_id2" name="year_id2" class="form-control" style="width:95%;">

<option value="<?php echo $rowsyear['acyearid']; ?>" selected="selected"><?php echo $rowsyear['acyear'] ?></option>

    <?php
				/*$sql = mysqli_query($conn, "SELECT * From ac_year_tb order by acyearid");
				$row = mysqli_num_rows($sql);
				while ($row = mysqli_fetch_array($sql))
				{
					if($rowsyear['acyearid'] != $row['acyearid'])
					{
						echo "<option value='". $row['acyearid'] ."'>" .$row['acyear'] ."</option>" ;
					}
				}*/
		?>
</select></div>
  </td>
  
  <td width="25%">
<div class="form-group"><label class="form-control-label">Select Section</label>
<select id="sect_id2" name="sect_id2" class="form-control" style="width:95%;" onChange="getClassForSectionForTeacherAssignUpdate();">

<option value="<?php echo $rowssect['sect_id']; ?>" selected="selected"><?php echo $rowssect['sect_name'] ?></option>

<?php
				/*$sql = mysqli_query($conn, "select distinct st.sect_id, st.sect_name from class_details_tb cdt join class_tb ct on cdt.class_id = ct.class_id join section_tb st on ct.sect_id = st.sect_id where cdt.acyearid = " . $_SESSION['user_details']['acyear_id'] ." and st.status = 0 and assigned_to is null order by st.sect_id");
				$row = mysqli_num_rows($sql);
				while ($row = mysqli_fetch_array($sql))
				{
					if($rowssect['sect_id'] != $row['sect_id'])
					{
						echo "<option value='". $row['sect_id'] ."'>" .$row['sect_name'] ."</option>" ;
					}
				}*/
?>    
    
</select>
</div>
     
  </td>

  <td width="25%">
<div class="form-group"><label class=" form-control-label">Select Class</label>
<select id="class_id2" name="class_id2" class="form-control" style="width:95%;" onChange="getAllAssignDivisionForClassUpdate();">


<option value="<?php echo $rowsclass['class_id']; ?>" selected="selected"><?php echo $rowsclass['class_name'] ?></option>

 <?php
			/*$sql = mysqli_query($conn, "select distinct ct.class_id, ct.class_name from class_details_tb cdt join class_tb ct on cdt.class_id = ct.class_id join section_tb st on ct.sect_id = st.sect_id where cdt.acyearid = $year_id and st.sect_id = $sect_id and ct.status = 0 and assigned_to is null order by class_id");
				$row = mysqli_num_rows($sql);
				while ($row = mysqli_fetch_array($sql))
				{
					if($rowsclass['class_id'] != $row['class_id'])
						{
							echo "<option value='". $row['class_id'] ."'>" .$row['class_name'] ."</option>" ;
						}
				}*/
?>
</select></div>
  </td>
  
  
  
    <td width="25%" rowspan="2" align="center">
    
    <div class="form-group"><label for="grade_max" class=" form-control-label">Class Division Status</label>
<?php 
echo "<input type='hidden' name='cds_status2' id='cds_status2' value='".$rowsclassdetails['status']."'<br>";
if ($rowsclassdetails['status'] == 0)
{
		$sr = "images/present.png";
		$st = "ACTIVE";
		$co = "green";				
}
else
{
		$sr = "images/absent.png";
		$st = "IN-ACTIVE";
		$co = "red";				
}
		echo "<div align='center'>";
		echo "<img id='i1' title='Current Status : ".$st."' alt='Current Status : ".$st."' src='".$sr."' onclick ='changeColor(this.id)'/>";
		
		echo "<p id='p1' style='color:".$co."'>".$st."</p>";
		echo "</div>";
?>

   </div>
    
    
  </td>
 
  
  
    </tr>
<tr> 
  <td valign="top">

<div class="form-group"><label class=" form-control-label">Select Division</label>
<select id="div_id2" name="div_id2" class="form-control" style="width:95%;">
 
<option value="<?php echo $rowsdiv['div_id']; ?>" selected="selected"><?php echo $rowsdiv['div_name']; ?></option>

 <?php
				/*$sql = mysqli_query($conn, "SELECT t1.div_id, t1.div_name from division_tb t1 where EXISTS(select t2.div_id from class_details_tb t2 where t2.div_id = t1.div_id and t2.class_id = $class_id and t2.acyearid = $year_id and t2.assigned_to is null)");
				
				$row = mysqli_num_rows($sql);
				while ($row = mysqli_fetch_array($sql))
				{
					if($rowsdiv['div_id'] != $row['div_id'])
						{
							echo "<option value='". $row['div_id'] ."'>" .$row['div_name'] ."</option>" ;
						}
				}*/
?>
</select></div>

  </td>

  <td valign="top">
  <div class="form-group"><label class="form-control-label">Total Strength</label><input type="text" id="stud_total2" name="stud_total2" class="form-control" placeholder="Enter Total Strength.." style="width:95%;" maxlength="3" required=""  value="<?php echo $rowsclassdetails['total_stud'];?>" onKeyPress="return event.charCode >= 48 && event.charCode <= 57"  />
</div>
  </td>  
  
<td valign="top">

<div class="form-group"><label class="form-control-label">Select Teacher</label>

<select id="user_id2" name="user_id2" class="form-control" style="width:95%;" >

<option value="<?php echo $rowsteacher['user_id']; ?>" selected="selected"><?php echo $rowsteacher['full_name']; ?></option>
<?php

$sql = mysqli_query($conn, "SELECT distinct m.user_id, full_name from member m join user_role_tb urt on m.role_id = urt.role_id where urt.role_code = '5' and m.status='Active' and verified = 1 order by full_name");
				
				$row = mysqli_num_rows($sql);
				while ($row = mysqli_fetch_array($sql))
				{
					if($rowsteacher['user_id'] != $row['user_id'])
						{
							echo "<option value='". $row['user_id'] ."'>" .$row['full_name'] ."</option>" ;
						}
				}
?>

</select>

</div>
  </td>

  </tr>
  </table>
  
 

</div>
                                
<div class="card-footer" align="right">
<button type="submit" name="update" class="btn btn-primary btn-sm" style="border-radius: 7px; height:33px; width:100px; font-size:14px;" title="Click to Update" onClick="return form_update();"><i class="fa fa-edit"></i> Update</button>
                                                        
<button type="reset" class="btn btn-danger btn-sm" style="border-radius: 7px; height:33px; width:100px; font-size:14px;" title="Click to Cancel" onClick="window.open('assign_teacher_classdiv_each_year.php','_self');"><i class="fa fa-times"></i> Cancel</button>
</div>
                                                     
                                                     </form>
                             </div>
                             </div>          
                             </div>        
                                                     
<?php
}
else
{
?>
 <div class="col-lg-12">
   <div class="card" id="id1">
                                                    <div class="card-header alert alert-primary">
                                                        <strong>Assign Teacher to Class-Division -</strong>
                                                    </div>
    
<div class="card-body card-block">
           
<form action="" name="form1" id="form1" method="post" onSubmit="return form_save();">

<div class="row" style="padding-left:15px;">

<table width="100%" align="left">
<tr>
<td width="15%">
<div class="form-group"><label for="sect_id" class=" form-control-label">Academic Year</label>
<select id="year_id1" name="year_id1" class="form-control" style="width:95%;" disabled>
    	<?php
				$sql = mysqli_query($conn, "SELECT acyearid, acyear From ac_year_tb where acyearid = " . $_SESSION['user_details']['acyear_id']);
				$row = mysqli_num_rows($sql);
				while ($row = mysqli_fetch_array($sql))
				{
						echo "<option value='". $row['acyearid'] ."'>" .$row['acyear'] ."</option>" ;
				}		
		?>    
</select>
</div>
</td>

<td width="25%">
<div class="form-group"><label for="sect_id" class=" form-control-label">Select Section Name</label>
<select id="sect_id1" name="sect_id1" class="form-control" style="width:95%;" onChange="getClassForSectionForTeacherAssign();">
    	<?php
				$sql = mysqli_query($conn, "select distinct st.sect_id, st.sect_name from class_details_tb cdt join class_tb ct on cdt.class_id = ct.class_id join section_tb st on ct.sect_id = st.sect_id where cdt.acyearid = " . $_SESSION['user_details']['acyear_id'] ." and st.status = 0 and assigned_to is null order by st.sect_id");
				$row = mysqli_num_rows($sql);
				while ($row = mysqli_fetch_array($sql))
				{
					echo "<option value='". $row['sect_id'] ."'>" .$row['sect_name'] ."</option>" ;
				}
		?>    
</select>
</div>
</td>

<td width="15%">
<div class="form-group"><label class="form-control-label">Select Class Name</label>
<select id="class_id1" name="class_id1" class="form-control" style="width:95%;" onChange="getAllAssignDivisionForClass();">
<script>
    getClassForSectionForTeacherAssign();
</script>
</select>
</div>
</td>

<td width="15%">
<div class="form-group"><label class="form-control-label">Select Division</label>
<select id="div_id1" name="div_id1" class="form-control" style="width:95%;" >
<script>
getAllAssignDivisionForClass();
</script>

</select>
</div>
</td>
  
<td width="30%">
<input type="hidden" name="role_code1" id="role_code1" value="5" />
<div class="form-group"><label class="form-control-label">Select Teacher</label>
<select id="user_id1" name="user_id1" class="form-control" style="width:95%;" >
<script>
	//getNotAssignTeacherForClass();
	getTeachers();
</script>
</select>

</div>
  </td>
  
  </tr>
  
  
  <tr>


  </tr>

</table>
  </div>
  <br>
 
                                                    <div class="card-footer" align="right">
                                                        <button type="submit" name="save" class="btn btn-primary btn-sm" style="border-radius: 7px; height:33px; width:100px; font-size:14px;" "title="Click to Save">
                                                            <i class="fa fa-save"></i> Save
                                                        </button>
                                                        <button type="reset" class="btn btn-danger btn-sm" style="border-radius: 7px; height:33px; width:100px; font-size:14px;" "title="Click to Reset" onClick="window.open('assign_teacher_classdiv_each_year.php','_self');">
                                                            <i class="fa fa-undo"></i> Reset
                                                        </button>
                                                    </div>
                                                     </form>
                                                </div>



                                                
                                            </div>
                                            
                                            </div>
<?php
}
?>

<div class="col-lg-12">

<div class="card">
                            <div class="card-header alert alert-success">
                                <strong class="card-title">Yearwise Assigned Teacher Class Division Details -</strong>
                            </div>
                            <div class="card-body">
 <form action="" method="post">

<table id="bootstrap-data-table-export1" align="center" class="table table-striped table-bordered" width="100%">
   <tr>			
				<!-- <td width="20%">
                  <div style="padding-top:10px;">
                  <label><strong>Enter Academic Year / Section / Class Name :</strong></label>
                  </div>
                 </td>-->
                 
				 
                 <td width="25%" align="center">
                  <div style="padding-top:0px;">
                  <label>Academic Year</label>
             <select id="year_id3" name="year_id3" class="form-control" style="width:95%;">
             
             <option value="Select">Select</option>
    	<?php
				$sql = mysqli_query($conn, "SELECT acyearid, acyear From ac_year_tb order by acyearid");				
				$row = mysqli_num_rows($sql);
				while ($row = mysqli_fetch_array($sql))
				{	
				?>
							<option value="<?php echo $row['acyearid'];?>">
                          	<?php echo $row['acyear']; ?> </option>				
		<?php					
				}	
		?>
</select>
             
             
               </div>
                 </td>
                 
                 
                     <td width="30%" align="center">
                 <div style="padding-top:0px;">
                  <label>Teacher Name</label>
             <select id="user_id3" name="user_id3" class="form-control" style="width:95%;" onChange="check_first_combo();">
             <option value="Select">Select</option>
    	<?php
				$sql = mysqli_query($conn, "SELECT distinct m.user_id, full_name from member m join user_role_tb urt on m.role_id = urt.role_id where urt.role_code = '5' and m.status='Active' and verified = 1 order by full_name");
				
				$row = mysqli_num_rows($sql);
				while ($row = mysqli_fetch_array($sql))
				{	
				?>
							<option value="<?php echo $row['user_id'];?>" 
							<?php /* if (isset($_POST['user_id3']))
								{
									if ($_POST['user_id3'] == $row['user_id'])
									{
										 echo 'selected="selected"';
									}
								} */ ?>
                                
                                >
                          	<?php echo $row['full_name']; ?> </option>				
		<?php					
				}	
		?>
</select>
             
             
               </div>
                 </td>
                 
                 
                 <td width="30%" align="center">
                  <div style="padding-top:0px;">
                  <label>Section / Class Name</label>
               <input placeholder="Enter search keyword..." name="asc_name3" class="form-control" id="asc_name3" type="text" value="<?php //if (isset($_POST['asc_name3'])) echo $_POST['asc_name3']; ?>" /> 
               </div>
                 </td>
                 
                 
                <!--<td width="15%" align="center">
                  <div style="padding-top:25px;">
                    <input type="radio" name="display_all3" <?php //if (isset($_POST['display_all3']) && $_POST['display_all3']=="1") echo "checked"; ?> id="display_all3" value="0" onClick="disablefield(this.id);"> Display All
                  </div>
                 </td>-->
                 
                 
             	 <td align="center">
                 <div style="padding-top:23px;">
              
               <button type="submit" name="search" class="btn btn-warning btn-sm" style="border-radius: 7px; height:33px; width:100px; font-size:14px;" title="Click to Search">
                                                            <i class='fa fa-search'></i> Search
                                                        </button>
                                                        </div>
                 </td>

                
                            </tr>   
                  
</table>
</form>

               <div id="GradeData">	
               <div style="overflow-x:auto;">
<table id="bootstrap-data-table-export" align="center" class="table table-striped table-bordered" width="100%">
<tr align="center">							
<th width="8%">Sr. No.</th><!--<th width="15%">Academic Year</th>--><th width="17%">Section Name</th><th width="12%">Class Name</th><th width="10%">Division Name</th><th width="10%">Total Strength</th><th width="23%">Teacher Name</th><th width="10%">Status</th><th width="10%">Action</th>
                            </tr>

                <?php
	
				$rowcount=mysqli_num_rows($search_result);
				
				if($rowcount > 0)
				{		
				$srno = 0;
				 while($row = mysqli_fetch_array($search_result)):
				 $srno++;
				 ?>
                 
                  <form action="" method="post" onSubmit="return form_confirm();" >
                <tr>
                    <td width="8%" align="center"><?php echo $srno;?>
                    <input type="hidden" name="srno3" id="srno3" value="<?php echo $row['srno'];?>" />
                    </td>
                   <!-- <td width="15%" align="center"><?php //echo $row['acyear'];?></td>-->
                    <td width="17%" align="center"><?php echo $row['sect_name'];?></td>
					<td width="12%" align="center"><?php echo $row['class_name'];?></td>
					<td width="10%" align="center"><?php echo $row['div_name'];?></td>
					<td width="10%" align="center"><?php echo $row['total_stud'];?></td>
                    <td width="23%" align="center"><?php echo $row['full_name'];?></td>
                    <td width="10%" align="center"><?php
					if($row['class_det_status'] == 0)
					{ 
						echo 'ACTIVE';
					}
					else
					{
						echo 'IN-ACTIVE';
					}
					?></td>
                              
<td width="10%" align="center">

<button type="submit" name="submit" class="btn btn-success btn-sm" style="border-radius: 7px; height:33px; width:60%; font-size:14px;" 

<?php 
if($row['sect_status'] == 1)
{
	echo "title='Section is In-Active so you can not update class details.'" ;
	echo 'disabled';
}
elseif($row['class_status'] == 1)
{
	echo "title='Class is In-Active so you can not update class details.'" ;
	echo 'disabled';
}
else
{
	echo "title='Select to Update'" ;
}
 ?>
>
<i class="fa fa-mouse-pointer"></i></button>

</td>

                </tr>
                 </form>
                 
                <?php endwhile;
                
                }
                
                else
				{
                ?>
                 <tr>
                    <td colspan="9" align="center">
<div style="padding-top:20px;">          
<div class="sufee-alert alert with-close alert-danger alert-dismissible fade show" style="width:50%;">
<span class="badge badge-danger">Message</span> No Record Found !
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
</div>                    
                    </td>
                 </tr>
                
              <?php }  ?>
            
            </table>
       </div>
                        </div>

                        </div>             
             
                        </div>
                        </div>
  
                                            </div>
                                            
                                        </div>	
                                    </div>
                                </div>
    <?php
			include('common/jscript.php');	
	?>
							
</body>
</html>