<?php

class SearchController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {

        $request = $this->getRequest();

        $query = $this->getParam('query');

        if (empty($query)) 
    	{
            // Redireccionar al index
            return $this->redirect()->toRoute('/');
        }

    	$viewData = array(
    		'title' => $query . " - TagTrack",
    		'copyright' => "TagTrack &copy;",
    		'searchPlugin' => true,
            'requestedQuery' => $query
        );

        $this->view->assign($viewData);


    }


}

