<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('Event_model');

	}

	public function index()
	{
		// get country data.....
		$events = $this->Event_model->getEvents();		

		$this->form_validation->set_rules('fname','Name','required');
		$this->form_validation->set_rules('email','Email','required|valid_email');
		$this->form_validation->set_rules('mobile','Mobile','required|regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('password','Password','required');

		if($this->form_validation->run() == false){
			$data = array('events' => $events);

			$this->load->view('event/index',$data);
		}else{

		}
	}

	public function create(){
		$data = array();
		$error = 0;

		if(!$this->input->post('event_title')){
			$data['message'] = 'Event title is missing';
			$error = 1;
		}else{
			$event_title = $this->input->post('event_title');
		}

		if(!$this->input->post('start_date')){
			$data['message'] = 'Event start date is missing';
			$error = 1;
		}else{
			$start_date = $this->input->post('start_date');
		}

		if(!$this->input->post('end_date')){
			$data['message'] = 'Event end date is missing';
			$error = 1;
		}else{
			$end_date = $this->input->post('end_date');
		}

		if(!$this->input->post('recurrence')){
			$data['message'] = 'Recurrence is missing';
			$error = 1;
		}else{
			$recurrence = $this->input->post('recurrence');
		}

		if($error == 1){
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}

		// check event is exists or not
		$is_event_exists = $this->Event_model->eventExists($event_title);
		if($is_event_exists > 0){
			$data['success'] = false;
			$data['message'] = 'Event already exists';
			echo json_encode($data);
			exit;
		}else{
			$records = array('event_title'=>$event_title,
								'start_date'=>$start_date,
								'end_date'=>$end_date,
								'recurrence'=>$recurrence,
								'created_date'=>date('Y-m-d H:i:s'));

			// insert into events table
			$is_inserted = $this->Event_model->insertEvent($records);
			if($is_inserted){
				$data['success'] = true;
				$data['message'] = 'Event inserted succssfully';

				echo json_encode($data);
				exit;
			}else{
				$data['success'] = false;
				$data['message'] = 'Something went wrong';

				echo json_encode($data);
				exit;
			}

		}

	}

	public function view($event_id){

		// get this event data
		$getEvent = $this->Event_model->getThisEvent($event_id);
		
		if(!empty($getEvent)){
			$start_date = $getEvent['start_date'];
			$end_date = $getEvent['end_date'];
			if($getEvent['recurrence'] == 'Every Day'){
				$dates = $this->every($start_date, $end_date,'Day');
			}

			if($getEvent['recurrence'] == 'Every Week'){
				$dates = $this->every($start_date, $end_date,'Week');
				
			}

			if($getEvent['recurrence'] == 'Every Month'){
				$dates = $this->every($start_date, $end_date,'Month');
				
			}

			if($getEvent['recurrence'] == 'Every Year'){
				$dates = $this->every($start_date, $end_date,'Year');
				
			}

			if($getEvent['recurrence'] == 'Every other Day'){
				$dates = $this->everyOther($start_date, $end_date,'Day');
			}

			if($getEvent['recurrence'] == 'Every other Week'){
				$dates = $this->everyOther($start_date, $end_date,'Week');				
			}

			if($getEvent['recurrence'] == 'Every other Month'){
				$dates = $this->everyOther($start_date, $end_date,'Month');
			}


			if($getEvent['recurrence'] == 'Every other Year'){
				$dates = $this->everyOther($start_date, $end_date,'Year');
			}

			if($getEvent['recurrence'] == 'Every third Day'){
				$dates = $this->everyThird($start_date, $end_date,'Day');
			}

			if($getEvent['recurrence'] == 'Every third Week'){
				$dates = $this->everyThird($start_date, $end_date,'Week');
			}

			if($getEvent['recurrence'] == 'Every third Month'){
				$dates = $this->everyThird($start_date, $end_date,'Month');
			}

			if($getEvent['recurrence'] == 'Every third Year'){
				$dates = $this->everyThird($start_date, $end_date,'Year');
			}

			if($getEvent['recurrence'] == 'Every fourth Day'){
				$dates = $this->everyFourth($start_date, $end_date,'Day');
			}

			if($getEvent['recurrence'] == 'Every fourth Week'){
				$dates = $this->everyFourth($start_date, $end_date,'Week');
			}

			if($getEvent['recurrence'] == 'Every fourth Month'){
				$dates = $this->everyFourth($start_date, $end_date,'Month');
			}

			if($getEvent['recurrence'] == 'Every fourth Year'){
				$dates = $this->everyFourth($start_date, $end_date,'Year');
			}

			$event_count = count($dates);
			
			$data = array('event_dates'=>$dates,'event'=>$getEvent,'event_count'=>$event_count);
			$this->load->view('Event/view',$data);			
			
		}
	}

	public function delete($event_id){
		// we can check id is available or not..
		if(!empty($event_id)){
			$delete_event = $this->Event_model->deleteEvent($event_id);
			$this->session->set_flashdata('success','Event Deleted succssfully');
			redirect(base_url('event'));
		}
	}

	public function edit($event_id){
		// we can check event id is available or not here if not exist than we can give an error and stop edit that page.

		// get all data of current event
		if(!empty($event_id)){
			$getEvent = $this->Event_model->getThisEvent($event_id);
			$recurrence = $getEvent['recurrence'];
			$first = explode(' ',$recurrence);
			
			if(count($first) == 3){
				$firstdrop = $first[0].' '.$first[1];
				$seconddrop = $first[2];
			}else{
				$firstdrop = $first[0];
				$seconddrop = $first[1];
			}
			
			$data = array('event'=>$getEvent,'firstdrop'=>$firstdrop,'seconddrop'=>$seconddrop);
			$this->load->view('event/edit',$data);

			if($this->input->post()){
				;
				$records = array('event_title'=>$this->input->post('event_title'),
								'start_date'=>$this->input->post('start_date'),
								'end_date'=>$this->input->post('end_date'),
								'recurrence'=>$this->input->post('recurrence')); 

				$update = $this->Event_model->updateEvent($event_id,$records);
				if($update){
					$this->session->set_flashdata('success','Event updated succssfully');
					
					if($update){
						$data['success'] = true;
						$data['message'] = 'Event inserted succssfully';

						echo json_encode($data);
						exit;
					}else{
						$data['success'] = false;
						$data['message'] = 'Something went wrong';

						echo json_encode($data);
						exit;
					}
				}
			}
			
		}
	}

   function every($date1, $date2, $part ) {
		$start = new DateTime($date1);
		$end = new DateTime($date2);
		if($part == 'Day'){
			$interval = DateInterval::createFromDateString('+1 day');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}
		if($part == 'Month'){
			$interval = DateInterval::createFromDateString('1 month');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}

		if($part = 'Week'){
			
		    $interval = DateInterval::createFromDateString('+7 day');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}

		if($part = 'Year'){
			
		    $interval = DateInterval::createFromDateString('1 year + 1 day');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}
		
   }

   function everyOther($date1, $date2, $part){
   		$start = new DateTime($date1);
		$end = new DateTime($date2);
		if($part == 'Day'){
			$interval = DateInterval::createFromDateString('2 day');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}
		if($part == 'Month'){
			$interval = new DateInterval('P3M');
			$interval = DateInterval::createFromDateString('2 month');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}

		if($part = 'Week'){
			
			$interval = new DateInterval('P2W');
		    $interval = DateInterval::createFromDateString('2 weeks');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}

			return $dates;
		}

		if($part = 'Year'){
			
			$interval = new DateInterval('P2Y');
		    $interval = DateInterval::createFromDateString('2 years');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}
   }

   function everyThird($date1, $date2, $part){
   		$start = new DateTime($date1);
		$end = new DateTime($date2);
		if($part == 'Day'){
			$interval = DateInterval::createFromDateString('+3 day');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}
		if($part == 'Month'){
			$interval = DateInterval::createFromDateString('3 month');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}

		if($part = 'Week'){
			
		    $interval = DateInterval::createFromDateString('+21 day');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}

		if($part = 'Year'){
			
		    $interval = DateInterval::createFromDateString('3 year');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}
   }

   function everyFourth($date1, $date2, $part){
   		$start = new DateTime($date1);
		$end = new DateTime($date2);
		if($part == 'Day'){
			$interval = DateInterval::createFromDateString('+4 day');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}
		if($part == 'Month'){
			$interval = DateInterval::createFromDateString('4 month');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}

		if($part = 'Week'){
			
		    $interval = DateInterval::createFromDateString('+28 day');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}

		if($part = 'Year'){
			
		    $interval = DateInterval::createFromDateString('4 year');
			$period   = new DatePeriod($start, $interval, $end);

			$dates = array();
			foreach ($period as $key=> $dt) {
			    $dates[$key]['dates'] = $dt->format("d-m-Y");
			    $dates[$key]['days'] = $dt->format("l");

			}
			return $dates;
		}
   }

}
