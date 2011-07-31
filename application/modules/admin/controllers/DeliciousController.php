<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Admin_DeliciousController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->deliciousRepository = $this->_helper
                                          ->entityManager()
                                          ->getRepository('\Newsroom\Entity\Delicious');
    }

    public function indexAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $deliciousApiForm = new \Zend_Form($configForm->deliciousApi);

        if ($this->getRequest()->isPost())
        {
            if ($deliciousApiForm->isValid($_POST))
            {
                try
                {
                    $this->deliciousRepository->saveEntity($deliciousApiForm->getValues());

                    $this->_helper->systemMessages('notice', 'Einstellungen erfolgreich gespeichert');
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Einstellungen konnte nicht gespeichert werden');
                }
            }
        }
        else
        {
            $entity = $this->deliciousRepository->fetchEntity();

            if ($entity)
            {
                $deliciousApiForm->populate($entity->toArray());
            }
        }

        $deliciousApiForm->setAction('/admin/delicious');
        $this->view->form = $deliciousApiForm;
    }

    public function deleteAction()
    {
        try
        {
            $this->deliciousRepository->deleteEntity();

            $this->_helper->systemMessages('notice', 'Einstellungen erfolgreich gelöscht');
        }
        catch (\Exception $e)
        {
            $log = $this->getInvokeArg('bootstrap')->log;
            $log->log(
                    $e->getMessage(),
                    \Zend_Log::ERR,
                    array('trace' => $e->getTraceAsString())
            );

            $this->_helper->systemMessages('error', 'Einstellungen konnten nicht gelöscht werden');
        }

        $this->_redirect('/admin/delicious');
    }
}
