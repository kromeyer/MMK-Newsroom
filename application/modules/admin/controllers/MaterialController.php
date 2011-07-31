<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Admin_MaterialController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->materialRepository = $this->_helper
                                         ->entityManager()
                                         ->getRepository('\Newsroom\Entity\Material');
    }

    public function indexAction()
    {
        $this->view->materials = $this->materialRepository->fetchEntities();
    }

    public function addAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $materialForm = new Form_TagForm($configForm->material);

        if ($this->getRequest()->isPost())
        {
            if ($materialForm->isValid($_POST))
            {
                try
                {
                    $materialId = $this->materialRepository->saveEntity($materialForm->getValues());

                    $this->_helper->systemMessages('notice', 'Material erfolgreich gespeichert');

                    $this->_redirect('/admin/material/edit/' . $materialId);
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Material konnte nicht gespeichert werden');
                }
            }
        }

        $materialForm->setAction('/admin/material/add');
        $this->view->form = $materialForm;
    }

    public function editAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $materialForm = new Form_TagForm($configForm->material);

        $materialId = $this->getRequest()->getParam('id', null);
        try
        {
            $entity = $this->materialRepository->fetchEntity($materialId);
            $materialForm->file->setOrginValue($entity->file);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage(), 404);
        }

        if ($this->getRequest()->isPost())
        {
            if ($materialForm->isValid($_POST))
            {
                try
                {
                    $materialId = $this->materialRepository->saveEntity($materialForm->getValues());

                    $materialForm->file->setOrginValue($entity->file);

                    $this->_helper->systemMessages('notice', 'Material erfolgreich gespeichert');
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Material konnte nicht gespeichert werden');
                }
            }
        }
        else
        {
            $materialForm->populate($entity->toArray());
        }

        $materialForm->setAction('/admin/material/edit/' . $materialId);
        $this->view->form = $materialForm;
    }

    public function deleteAction()
    {
        $materialId = $this->getRequest()->getParam('id', null);

        try
        {
            $this->materialRepository->deleteEntity($materialId);

            $this->_helper->systemMessages('notice', 'Material erfolgreich gelöscht');
        }
        catch (\Exception $e)
        {
            $log = $this->getInvokeArg('bootstrap')->log;
            $log->log(
                    $e->getMessage(),
                    \Zend_Log::ERR,
                    array('trace' => $e->getTraceAsString())
            );

            $this->_helper->systemMessages('error', 'Material konnte nicht gelöscht werden');
        }

        $this->_redirect('/admin/material');
    }
}
