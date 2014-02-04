<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       
        $config = new Zend_Config_Ini(APPLICATION_PATH
                . '/configs/forms.ini', 'global');

        $form = new Zend_Form($config->search);

        $params = $this->getRequest()->getParams();

        if (!empty($params[$form->getElement('searchInput')->getName()]))
        {
            if ($form->isValid($this->getRequest()->getQuery()))
            {
                echo "SI";
            }
            else {
                echo "NO";
            }
        }   

    	$viewData = array(
    		'title' => "TagTrack",
    		'copyright' => "TagTrack &copy;",
            'form' => $form
        );

        $this->view->assign($viewData);

    }


}

