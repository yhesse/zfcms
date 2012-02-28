<?php
class Model_Bug extends Zend_Db_Table_Abstract
{
	protected $_name = 'bugs';
	
	public function createBug($name, $email, $date, $url, $description, $priority, $status) {
		$row = $this->createRow();
		$row->author 	  = $name;
		$row->email  	  = $email;
		$dateObject  	  = new Zend_date($date);
		$row->date    	  = $dateObject->get(Zend_Date::TIMESTAMP);
		$row->url    	  = $url;
		$row->description = $description;
		$row->priority    = $priority;
		$row->status	  = $status;
		
		$id = $row->save();
		
		return $id;
	}
	
	public function fetchBugs($filters = array(), $sortField = null, $limit = null, $page = 1) {
		$select = $this->select();
		
		if (count($filters) > 0) {
			foreach ($filters as $field => $filter) {
				$select->where($field.' = ?', $filter);
			}
		}
		
		if (null != $sortField) {
			$select->order($sortField);
		}
		
		return $this->fetchAll($select);
	}
	
	public function fetchPaginatorAdapter($filters = array(), $sortField = null) {
		$select = $this->select();
		
		if (count($filters) > 0) {
			foreach ($filters as $field => $filter) {
				$select->where($field.' = ?', $filter);
			}
		}
		
		if (null != $sortField) {
			$select->order($sortField);
		}
		
		$adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
		
		return $adapter;
	}
	
	public function updateBug($id, $name, $email, $date, $url, $description, $priority, $status) {
		$row = $this->find($id)->current();
		
		if ($row) {
			$row->author 	  = $name;
			$row->email  	  = $email;
			$dateObject  	  = new Zend_date($date);
			$row->date    	  = $dateObject->get(Zend_Date::TIMESTAMP);
			$row->url    	  = $url;
			$row->description = $description;
			$row->priority    = $priority;
			$row->status	  = $status;
			
			$row->save();
			return true;
		} else {
			throw new Zend_Exception("Update function failed; could not find row!");
		}
	}
	
	public function deleteBug($id) {
		$row = $this->find($id)->current();
		if ($row) {
			$row->delete();
			return true;
		} else {
			throw new Zend_Exception("Delete function failed; could not find row!");
		}
	}
}