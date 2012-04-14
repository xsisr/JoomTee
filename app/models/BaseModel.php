<?php

class BaseModel extends Nette\Object {

	protected $context;

	protected $db;



	public function __construct($context) {
		$this->context = $context;
		$this->db = $context->dibi;
	}
}
