<!DOCTYPE html>
<html>
<head>
	<title>Tatva Event</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
	<div class="container">
		<div class="row">
		<div class="col-md-12">
			<?php
$success = $this->session->userdata('success');
if ($success != "") {?>
				<div class="alert alert-success"><?php echo $success; ?></div>
			<?php
}
?>
			<?php
$error = $this->session->userdata('error');
if ($error != "") {?>
				<div class="alert alert-danger"><?php echo $error; ?></div>
			<?php
}
?>
		</div>
	</div>
	</div>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<h2>Add Events </h2>
			<form name="createevent">
			  <div class="form-group">
			    <label for="event_title">Event Title:</label>
			    <input type="text" class="form-control" name="event_title" id="event_title">
			    <span id="event_title_error" class="alert alert-danger" style="display: none;"></span>
			  </div>
			  <div class="form-group">
			    <label for="start_date">Start Date:</label>
			    <input type="text" class="form-control" name="start_date" autocomplete="off" id="start_date">
			    <span id="start_date_error" class="alert alert-danger" style="display: none;"></span>
			  </div>
			  <div class="form-group">
			    <label for="end_date">End Date:</label>
			    <input type="text" class="form-control" name="end_date" autocomplete="off" id="end_date">
			    <span id="end_date_error" class="alert alert-danger" style="display: none;"></span>
			  </div>
			  <div class="form-group">
			  	<label>Recurrence:</label>
			  	<?php $firstdd = array('Every','Every other','Every third','Every fourth');
			  		  $seconddd = array('Day','Week','Month','Year');	
			  	?>
			  	<div class="col-md-6">
			  		<select class="form-control" name="firstdd" id="firstdd">
			  		<?php foreach ($firstdd as $key => $value) { ?>
			  			<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
			  		<?php } ?>
			  	</select>
			  	<select class="form-control" name="seconddd" id="seconddd">
			  		<?php foreach ($seconddd as $key => $value) { ?>
			  			<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
			  		<?php } ?>
			  	</select>
			  	</div>
			  	
			  </div>
			  <button type="button" class="btn btn-primary" onclick="checkValidation(event);">Submit</button>
			  <button type="button" class="btn btn-danger">Cancel</button>			  
			</form>
		</div>
		<div class="col-md-9">
			<h2>Events list</h2>
			<div class="innerbox">
			<div class="col-md-12" style="padding-top: 10px;">
			<table class="table table-striped" id="event_table">
				<tr>
					<th class="text-center">Titles</th>
					<th class="text-center">Dates</th>
					<th class="text-center">Occurrence</th>
					<th class="text-center">Actions</th>
				</tr>

					<?php foreach ($events as $key => $value) {?>
						<tr id="event_<?php echo $value['event_id']; ?>">
					<td class="text-center"><?php echo $value['event_title']; ?></td>
					<td class="text-center"><?php echo $value['start_date']; ?> To <?php echo $value['end_date']; ?></td>
					<td class="text-center"><?php echo $value['recurrence']; ?></td>
					<td class="text-center"><a href="<?php echo base_url() . 'event/view/' . $value['event_id'] ?>" class="btn btn-success">View</a>&nbsp;
						<a href="<?php echo base_url() . 'event/edit/' . $value['event_id'] ?>" class="btn btn-primary">Edit</a>&nbsp;
						<a href="<?php echo base_url() . 'event/delete/' . $value['event_id'] ?>" class="btn btn-danger">X</a>
					</td>
						
				</tr>
				<?php }?>

			</table>

		</div>
		</div>
		</div>
	</div>
</div>
</body>
</html>
<script type="text/javascript">
  $( function() {
    $("#start_date").datepicker({
        numberOfMonths: 2,
        minDate:0,
        dateFormat: 'dd-mm-yy',
        onSelect: function(selected) {
          $("#end_date").datepicker("option","minDate", selected)
        }
    });
    $("#end_date").datepicker({ 
        numberOfMonths: 2,
        minDate:0,
        dateFormat: 'dd-mm-yy',
        onSelect: function(selected) {
           $("#start_date").datepicker("option","maxDate", selected)
        }
    }); 
  } );

  function checkValidation(event){
  	var event_title = $('#event_title').val();
  	var start_date = $('#start_date').val();
  	var end_date = $('#end_date').val();
  	var recurrence = $('#firstdd').val() + ' ' + $('#seconddd').val();
  	
  	if(event_title == ''){
  		$('#event_title_error').css('display','block').html('Title is missing.');
  		event.preventDefault();
  	}else{
  		$('#event_title_error').css('display','none').html();
  		event.preventDefault();
  	}

  	if(start_date == ''){
  		$('#start_date_error').css('display','block').html('Start date is missing.');
  		event.preventDefault();
  	}else{
  		$('#start_date_error').css('display','none').html();
  	}

  	if(end_date == ''){
  		$('#end_date_error').css('display','block').html('End date is missing.');
  		event.preventDefault();
  	}else{
  		$('#end_date_error').css('display','none').html();
  	}

  	$.ajax({
        url: '<?php echo base_url().'event/create' ?>',
        type: 'post',
        dataType: "json",
        data: 'event_title='+event_title+'&start_date='+start_date+'&end_date='+end_date+'&recurrence='+recurrence ,
        
        success: function (response) {
            
            if(response.success == true){
            	location.reload();
            	
            }else{
            	alert(response.message);
            }
        },
        error: function () {
            alert("error");
        }
    }); 
  }
  </script>