<?php

use Nette\Application\UI\Presenter,
    Nette\Utils\Strings,
    Nette\Utils\Html,
    Maite\Tabella;

class BasePresenter extends Presenter {

    public function renderDefault($id) {
	$this->setLayout(false);
	$content = file_get_contents($this->context->params['appDir'] . '/presenters/BasePresenter.php');

//	$upperId = Strings::upper($id);
//	$matches = Strings::match($content, sprintf('#//%s(.*?)//%s#sm', $upperId, $upperId));

//	$this->template->code = str_replace('\t', '	 ', $matches[1]);
	$this->template->name = 'Editor';
	$this->template->tabellaName = $id . 'Tabella';
    }

    protected function createComponentEditableTabella($name) {
	$model = $this->context->model; // in PHP 5.4 won't be necessary
	$l = $this->context->model->joomT->language();
	$f = $this->context->model->joomT->file();
//	dump ($l);exit;
	$ll = array();
	foreach ($l as $v) {
	    $ll[$v['lang']] = $v['lang'] ;
	}
	$ff = array();
	foreach ($f as $g) {
	    $ff[$g['file']] = $g['file'] ;
	}
	$grid = new Tabella(array(
		    'context' => $this->context,
		    'source' => $this->context->model->joomT->source(),
		    'limit' => 15,
		    'sorting' => 'desc',
		     'rowRenderer' => function($row) {
			return Html::el('tr')->class($row->lang);
		    },
		    'onSubmit' => function($post) use ($model) {

			$model->joomT->save($post);
		    },
		    'onDelete' => function($id) use ($model) {
			//$model->users->delete($id);
		    }
		));

	$grid->addColumn('Id', 'id', array(
	    'width' => 30
	));

	$grid->addColumn('Language', 'lang', array(
	    'filter' => $ll,
	    'filterHandler' => function($source, $value) {
		$source->where('lang = %s', $value);
	    },
	    'width' => 100,
	    'type' => Tabella::SELECT,
	));

	$grid->addColumn('File', 'file', array(
	    'width' => 150,
	    'filter' => $ff,
	    'filterHandler' => function($source, $value) {
		$source->where('file = %s', $value);
	    },
	));

	$grid->addColumn('Keyword', 'key', array(
	    'type' => Tabella::SELECT,
	    'width' => 100,
	));

	$grid->addColumn('Translation', 'translation', array(
	    'width' => 100,
	    'editable' => true
	));



	$grid->addColumn('+', Tabella::ADD, array(
	    'type' => Tabella::DELETE
	));

	$this->addComponent($grid, $name);
    }


}
