<?php
/**
 * Error controller
 */
class ErrorController Extends Zend_Controller_Action
{
    public function init() {
    }

    public function errorAction() {
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = $this->view->translate('Page not found');
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = $this->view->translate('Application error');
                break;
        }

        $this->view->exception = $errors->exception;
        $this->view->request   = $errors->request;
    }
}