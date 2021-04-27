<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
	Event Name: <b><?php echo $event['event_title'];?></b><br>
	Event Occurrences: <b><?php echo $event['recurrence'];?></b>
	<a href="<?php echo base_url().'event';?>" class="btn btn-primary">Event List</a>
<div class="col-md-9">
			<h2>Events list</h2>
			<h4>Total count: <?php echo $event_count; ?> </h4>
			<div class="innerbox">
			<div class="col-md-12" style="padding-top: 10px;">
			<table class="table table-striped" id="event_table">
				<tr>
					<th class="text-center">Dates</th>
					<th class="text-center">Days</th>
				</tr>

					<?php foreach ($event_dates as $key => $value) {?>
						<tr id="event_dates">
					<td class="text-center"><?php echo $value['dates']; ?></td>
					<td class="text-center"><?php echo $value['days']; ?></td>
					
						
				</tr>
				<?php }?>

			</table>

		</div>
		</div>
		</div>
</body>
</html>