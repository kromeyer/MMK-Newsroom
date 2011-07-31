<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Admin_FlickrController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->flickrRepository = $this->_helper
                                       ->entityManager()
                                       ->getRepository('\Newsroom\Entity\Flickr');
    }

    public function indexAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $flickrApiForm = new \Zend_Form($configForm->flickrApi);

        if ($this->getRequest()->isPost())
        {
            if ($flickrApiForm->isValid($_POST))
            {
                try
                {
                    $this->flickrRepository->saveEntity($flickrApiForm->getValues());

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
            $entity = $this->flickrRepository->fetchEntity();

            if ($entity)
            {
                $flickrApiForm->populate($entity->toArray());
            }
        }

        $flickrApiForm->setAction('/admin/flickr');
        $this->view->form = $flickrApiForm;
    }

    public function deleteAction()
    {
        try
        {
            $this->flickrRepository->deleteEntity();

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

        $this->_redirect('/admin/flickr');
    }
}
