<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Admin_AnnouncementController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->announcementRepository = $this->_helper
                                             ->entityManager()
                                             ->getRepository('\Newsroom\Entity\Announcement');
    }

    public function indexAction()
    {
        $this->view->announcements = $this->announcementRepository->fetchEntities();
    }

    public function addAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $announcementForm = new Form_TagForm($configForm->announcement);

        if ($this->getRequest()->isPost())
        {
            if ($announcementForm->isValid($_POST))
            {
                try
                {
                    $announcementId = $this->announcementRepository->saveEntity($announcementForm->getValues());

                    $this->_helper->systemMessages('notice', 'Pressemitteilung erfolgreich gespeichert');

                    $this->_redirect('/admin/announcement/edit/' . $announcementId);
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Pressemitteilung konnte nicht gespeichert werden');
                }
            }
        }

        $announcementForm->setAction('/admin/announcement/add');
        $this->view->form = $announcementForm;
    }

    public function editAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $announcementForm = new Form_TagForm($configForm->announcement);

        $announcementId = $this->getRequest()->getParam('id', null);
        try
        {
            $entity = $this->announcementRepository->fetchEntity($announcementId);
            $announcementForm->file->setOrginValue($entity->file);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage(), 404);
        }

        if ($this->getRequest()->isPost())
        {
            if ($announcementForm->isValid($_POST))
            {
                try
                {
                    $announcementId = $this->announcementRepository->saveEntity($announcementForm->getValues());

                    $announcementForm->file->setOrginValue($entity->file);

                    $this->_helper->systemMessages('notice', 'Pressemitteilung erfolgreich gespeichert');
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Pressemitteilung konnte nicht gespeichert werden');
                }
            }
        }
        else
        {
            $announcementForm->populate($entity->toArray());
        }

        $announcementForm->setAction('/admin/announcement/edit/' . $announcementId);
        $this->view->form = $announcementForm;
    }

    public function deleteAction()
    {
        $announcementId = $this->getRequest()->getParam('id', null);

        try
        {
            $this->announcementRepository->deleteEntity($announcementId);

            $this->_helper->systemMessages('notice', 'Pressemitteilung erfolgreich gelöscht');
        }
        catch (\Exception $e)
        {
            $log = $this->getInvokeArg('bootstrap')->log;
            $log->log(
                    $e->getMessage(),
                    \Zend_Log::ERR,
                    array('trace' => $e->getTraceAsString())
            );

            $this->_helper->systemMessages('error', 'Pressemitteilung konnte nicht gelöscht werden');
        }

        $this->_redirect('/admin/announcement');
    }
}
