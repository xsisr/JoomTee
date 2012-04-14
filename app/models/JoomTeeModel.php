<?php

class JoomTeeModel extends BaseModel {

	public function source() {
	    
		return $this->db->select('SQL_CALC_FOUND_ROWS *')->from('joomT');
	}



	public function save($set) {
		if (@!$set['id'])
			$this->db->insert('joomT', $set)->execute();
		else
			$this->db->update('joomT', $set)->where('id = %i', $set['id'])->execute();
	}



	public function delete($id) {
		$this->db->delete('joomT')->where('id = %i', $id)->execute();
	}
	
	public function language() {
	    $l=$this->db->select('DISTINCT lang')->from('joomT')->fetchAll();
//	    dump ($l);exit;
		return $l;
	}
	public function file() {
	    
		return $this->db->select('DISTINCT file')->from('joomT')->fetchAll();
	}
	
}