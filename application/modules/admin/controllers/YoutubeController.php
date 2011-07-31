<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Admin_YoutubeController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->youtubeRepository = $this->_helper
                                        ->entityManager()
                                        ->getRepository('\Newsroom\Entity\Youtube');
    }

    public function indexAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $youtubeApiForm = new \Zend_Form($configForm->youtubeApi);

        if ($this->getRequest()->isPost())
        {
            if ($youtubeApiForm->isValid($_POST))
            {
                try
                {
                    $this->youtubeRepository->saveEntity($youtubeApiForm->getValues());

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
            $entity = $this->youtubeRepository->fetchEntity();

            if ($entity)
            {
                $youtubeApiForm->populate($entity->toArray());
            }
        }

        $youtubeApiForm->setAction('/admin/youtube');
        $this->view->form = $youtubeApiForm;
    }

    public function deleteAction()
    {
        try
        {
            $this->youtubeRepository->deleteEntity();

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

        $this->_redirect('/admin/youtube');
    }
}
