<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Admin_TagController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->tagRepository = $this->_helper
                                    ->entityManager()
                                    ->getRepository('\Newsroom\Entity\Tag');
    }

    public function indexAction()
    {
        $this->view->tags = $this->tagRepository->fetchEntities();
    }

    public function addAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $tagForm = new \Zend_Form($configForm->tag);

        if ($this->getRequest()->isPost())
        {
            if ($tagForm->isValid($_POST))
            {
                try
                {
                    $tagId = $this->tagRepository->saveEntity($tagForm->getValues());

                    $this->_helper->systemMessages('notice', 'Tag erfolgreich gespeichert');

                    $this->_redirect('/admin/tag/edit/' . $tagId);
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Tag konnte nicht gespeichert werden');
                }
            }
        }

        $tagForm->setAction('/admin/tag/add');
        $this->view->form = $tagForm;
    }

    public function editAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $tagForm = new Zend_Form($configForm->tag);

        $tagId = $this->getRequest()->getParam('id', null);

        if ($this->getRequest()->isPost())
        {
            if ($tagForm->isValid($_POST))
            {
                try
                {
                    $tagId = $this->tagRepository->saveEntity($tagForm->getValues());

                    $this->_helper->systemMessages('notice', 'Tag erfolgreich gespeichert');
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Tag konnte nicht gespeichert werden');
                }
            }
        }
        else
        {
            try
            {
                $entity = $this->tagRepository->fetchEntity($tagId);
                $tagForm->populate($entity->toArray());
            }
            catch (\Exception $e)
            {
                throw new \Exception($e->getMessage(), 404);
            }
        }

        $tagForm->setAction('/admin/tag/edit/' . $tagId);
        $this->view->form = $tagForm;
    }

    public function deleteAction()
    {
        $tagId = $this->getRequest()->getParam('id', null);

        try
        {
            $this->tagRepository->deleteEntity($tagId);

            $this->_helper->systemMessages('notice', 'Tag erfolgreich gelöscht');
        }
        catch (\Exception $e)
        {
            $log = $this->getInvokeArg('bootstrap')->log;
            $log->log(
                    $e->getMessage(),
                    \Zend_Log::ERR,
                    array('trace' => $e->getTraceAsString())
            );

            $this->_helper->systemMessages('error', 'Tag konnte nicht gelöscht werden');
        }

        $this->_redirect('/admin/tag');
    }
}
