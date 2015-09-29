<?php
namespace FreePBX\modules;


class Customcontexts implements \BMO {
	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}
		$this->FreePBX = $freepbx;
		$this->db = $freepbx->Database;
	}
	public function install() {}
	public function uninstall() {}
	public function backup() {}
	public function restore($backup) {}
	public function doConfigPageInit($page) {}
	public function getActionBar($request) {
		$buttons = array();
		switch($request['display']) {
			case 'customcontexts':
				$buttons = array(
					'delete' => array(
						'name' => 'delete',
						'id' => 'delete',
						'value' => _('Delete')
					),
					'reset' => array(
						'name' => 'reset',
						'id' => 'reset',
						'value' => _('Reset')
					),
					'duplicate' => array(
						'name' => 'duplicate',
						'id' => 'duplicate',
						'value' => _('Duplicate')
					),
					'submit' => array(
						'name' => 'submit',
						'id' => 'submit',
						'value' => _('Submit')
					)
				);
				if (empty($request['extdisplay'])) {
					unset($buttons['delete']);
					unset($buttons['duplicate']);
				}
				if(!isset($request['view'])){
					$buttons = NULL;
				}
			break;
		}
		return $buttons;
	}
	public function ajaxRequest($req, &$setting) {
		switch ($req) {
			case 'getJSON':
				return true;
			break;
			default:
				return false;
			break;
		}
	}
	public function ajaxHandler(){
		switch ($_REQUEST['command']) {
			case 'getJSON':
				switch ($_REQUEST['jdata']) {
					case 'grid':
						$ret = array();
						/*code here to generate array*/
						return $ret;
					break;

					default:
						return false;
					break;
				}
			break;

			default:
				return false;
			break;
		}
	}
}
