<?php

class Application_Form_Search extends Zend_Form
{

    public function init()
    {

		$this->clearDecorators();
        $this->addDecorator('FormElements')
			->addDecorator('HtmlTag', 
				array('tag' => '<ul>')
			)
			->addDecorator('Form');
    }


}

