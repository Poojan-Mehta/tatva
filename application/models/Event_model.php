<?php

class Event_model extends CI_Model {

        public function getEvents()
        {
        	$this->db->select('*');
        	$this->db->from('events');
        	$query = $this->db->get();
        	$result = $query->result_array();
        	return $result;
        }

        public function eventExists($event_title){
        	$this->db->select('*');
        	$this->db->from('events');
        	$this->db->where('event_title',$event_title);
        	$query = $this->db->get();
        	$result = $query->num_rows();
        	return $result;
        }

        public function insertEvent($recordes){
        	$this->db->insert('events',$recordes);
        	return true;
        }

        public function getThisEvent($event_id){
        	$this->db->select('*');
        	$this->db->from('events');
        	$this->db->where('event_id',$event_id);
        	$query = $this->db->get();
        	$result = $query->row_array();
        	return $result;
        }

        public function deleteEvent($event_id){
        	$this->db->where('event_id', $event_id);
    		$this->db->delete('events');
    		return true;
        }

        public function updateEvent($event_id,$records){
        	$this->db->where('event_id',$event_id);
			$this->db->update('events',$records);

			return true;
        }

}
?>