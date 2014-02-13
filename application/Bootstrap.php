<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');

		$router = Zend_Controller_Front::getInstance()->getRouter();

		$route = new Zend_Controller_Router_Route(
		    'search/:query',
		    array(
		        'controller' => 'search',
		        'action'     => 'index'
		    )
		);

		$router->addRoute('search', $route);
    }


	protected function _initResourceAutoloader()
	{
	     $autoloader = new Zend_Loader_Autoloader_Resource(array(
	        'namespace' => 'Application',
	        'basePath'  => APPLICATION_PATH
	     ));

	     $autoloader->addResourceType( 'model', 'models/', 'Model');
	     return $autoloader;
	}

}

