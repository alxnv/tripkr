<?php
/// примерный класс контроллера для наследования


class My_A7ExController extends Zend_Controller_Action
{

    /*public function __construct(Zend_Controller_Request_Abstract $request,
                                Zend_Controller_Response_Abstract $response,
                                array $invokeArgs = array()) { // арг.как у Zend_Controller_Action_Interface
        //die('tt');
        
    }*/
    // This error occurs when you define a constructor in your Controller_Action class.
    // You should use the method preDispatch instead.

    public function init()
    {
        /* Initialize action controller here */
        //if (my7::notadmin()) $this->_redirect('tools/helpers/noframe.htm',array('prependBase'=>1));

    }

    public function indexAction()
    {
        // action body
        //$this->view->bodyclass='bodyleft';
    }


}

