<?php
class Model_Page extends Zend_Db_Table_Abstract
{
	protected $_name = 'pages';
	
	protected $_dependentTables = array('Model_ContentNode');
	
	protected $_referenceMap = array(
			'Page' => array(
					'columns' 		=> array('parent_id'),
					'refTableClass' => 'Model_Page',
					'refColumns' 	=> array('id'),
					'onDelete' 		=> self::CASCADE,
					'onUpdate' 		=> self::RESTRICT
					)
			);
	
	public function createPage($name, $namespace, $parentId = 0) {
		$row 				= $this->createRow();
		$row->name 			= $name;
		$row->namespace 	= $namespace;
		$row->parent_id 	= $parentId;
		$row->date_created 	= time();
		
		$id = $row->save();
		
		return $id;
	}
	
	public function updatePage($id, $data) {
		$row = $this->find($id)->current();
		if ($row) {
			$row->name 		= $data['name'];
			$row->parent_id = $data['parent_id'];
			$row->save();
			
			unset($data['id']);
			unset($data['name']);
			unset($data['parent_id']);
			
			if (count($data) > 0) {
				$mdlContentNode = new Model_ContentNode();
				foreach($data as $key => $value) {
					$mdlContentNode->setNode($id, $key, $value);
				}
			}
		} else {
			throw new Zend_Exception('Could not open page to update!');
		}
	}
	
	public function deletePage($id) {
		$row = $this->find($id)->current();
		if ($row) {
			$row->delete();
			return true;
		} else {
			throw new Zend_Exception('Delete funtion failed; could not find page!');
		}
	}
	
	public function getRecentPages($count = 10, $namespace = 'page') {
		$select = $this->select();
		$select->order = 'date_created DESC';
		$select->where('namespace = ?', $namespace);
		$select->limit($count);
		$results = $this->fetchAll($select);
		if ($results->count() > 0) {
			$pages = array();
			foreach($results as $result) {
				$pages[$result->id] = new CMS_Content_Item_Page($result->id);
			}
			return $pages;
 		} else {
 			return null;
 		}
	}
}