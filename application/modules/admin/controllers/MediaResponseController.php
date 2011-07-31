<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Admin_MediaResponseController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->mediaResponseRepository = $this->_helper
                                              ->entityManager()
                                              ->getRepository('\Newsroom\Entity\MediaResponse');
    }

    public function indexAction()
    {
        $this->view->mediaResponses = $this->mediaResponseRepository->fetchEntities();
    }

    public function addAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $mediaResponseForm = new Form_TagForm($configForm->mediaResponse);

        if ($this->getRequest()->isPost())
        {
            if ($mediaResponseForm->isValid($_POST))
            {
                try
                {
                    $mediaResponseId = $this->mediaResponseRepository->saveEntity($mediaResponseForm->getValues());

                    $this->_helper->systemMessages('notice', 'Medienresonanz erfolgreich gespeichert');

                    $this->_redirect('/admin/media-response/edit/' . $mediaResponseId);
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Medienresonanz konnte nicht gespeichert werden');
                }
            }
        }

        $mediaResponseForm->setAction('/admin/media-response/add');
        $this->view->form = $mediaResponseForm;
    }

    public function editAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $mediaResponseForm = new Form_TagForm($configForm->mediaResponse);

        $mediaResponseId = $this->getRequest()->getParam('id', null);
        try
        {
            $entity = $this->mediaResponseRepository->fetchEntity($mediaResponseId);
            $mediaResponseForm->image->setOrginValue($entity->image);
            $mediaResponseForm->file->setOrginValue($entity->file);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage(), 404);
        }

        if ($this->getRequest()->isPost())
        {
            if ($mediaResponseForm->isValid($_POST))
            {
                try
                {
                    $mediaResponseId = $this->mediaResponseRepository->saveEntity($mediaResponseForm->getValues());

                    $mediaResponseForm->image->setOrginValue($entity->image);
                    $mediaResponseForm->file->setOrginValue($entity->file);

                    $this->_helper->systemMessages('notice', 'Medienresonanz erfolgreich gespeichert');
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Medienresonanz konnte nicht gespeichert werden');
                }
            }
        }
        else
        {
            $mediaResponseForm->populate($entity->toArray());
        }

        $mediaResponseForm->setAction('/admin/media-response/edit/' . $mediaResponseId);
        $this->view->form = $mediaResponseForm;
    }

    public function deleteAction()
    {
        $mediaResponseId = $this->getRequest()->getParam('id', null);

        try
        {
            $this->mediaResponseRepository->deleteEntity($mediaResponseId);

            $this->_helper->systemMessages('notice', 'Medienresonanz erfolgreich gelöscht');
        }
        catch (\Exception $e)
        {
            $log = $this->getInvokeArg('bootstrap')->log;
            $log->log(
                    $e->getMessage(),
                    \Zend_Log::ERR,
                    array('trace' => $e->getTraceAsString())
            );

            $this->_helper->systemMessages('error', 'Medienresonanz konnte nicht gelöscht werden');
        }

        $this->_redirect('/admin/media-response');
    }
}
