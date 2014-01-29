<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$viewData = array(
    		'title' => "TagTrack",
    		'copyright' => "TagTrack &copy;"
		);
        $this->view->assign($viewData);
    }


}

