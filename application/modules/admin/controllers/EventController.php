<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Admin_EventController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->eventRepository = $this->_helper
                                      ->entityManager()
                                      ->getRepository('\Newsroom\Entity\Event');

        $this->commentRepository = $this->_helper
                                        ->entityManager()
                                        ->getRepository('\Newsroom\Entity\Comment');

        $this->currentUserEntity = $this->_helper->entityManager()->find(
            '\Newsroom\Entity\User',
            \Zend_Auth::getInstance()->getIdentity()->id
        );
    }

    public function indexAction()
    {
        $this->view->events = $this->eventRepository->fetchEntities();
    }

    public function addAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $eventForm = new Form_TagForm($configForm->event);

        if ($this->getRequest()->isPost())
        {
            if ($eventForm->isValid($_POST))
            {
                try
                {
                    $values = $eventForm->getValues();
                    $values['user'] = $this->currentUserEntity;
                    $eventId = $this->eventRepository->saveEntity($values);

                    $this->_helper->systemMessages('notice', 'Event erfolgreich gespeichert');

                    $this->_redirect('/admin/event/edit/' . $eventId);
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Event konnte nicht gespeichert werden');
                }
            }
        }

        $eventForm->setAction('/admin/event/add');
        $this->view->form = $eventForm;
    }

    public function editAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $eventForm = new Form_TagForm($configForm->event);

        $eventId = $this->getRequest()->getParam('id', null);
        try
        {
            $entity = $this->eventRepository->fetchEntity($eventId);
            $eventForm->image->setOrginValue($entity->image);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage(), 404);
        }

        if ($this->getRequest()->isPost())
        {
            if ($eventForm->isValid($_POST))
            {
                try
                {
                    $values = $eventForm->getValues();
                    $values['user'] = $this->currentUserEntity;
                    $eventId = $this->eventRepository->saveEntity($values);

                    $eventForm->image->setOrginValue($entity->image);

                    $this->_helper->systemMessages('notice', 'Event erfolgreich gespeichert');
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Event konnte nicht gespeichert werden');
                }
            }
        }
        else
        {
            $eventForm->populate($entity->toArray());
        }

        $eventForm->setAction('/admin/event/edit/' . $eventId);
        $this->view->form = $eventForm;
    }

    public function deleteAction()
    {
        $eventId = $this->getRequest()->getParam('id', null);

        try
        {
            $this->eventRepository->deleteEntity($eventId);

            $this->_helper->systemMessages('notice', 'Event erfolgreich gelöscht');
        }
        catch (\Exception $e)
        {
            $log = $this->getInvokeArg('bootstrap')->log;
            $log->log(
                    $e->getMessage(),
                    \Zend_Log::ERR,
                    array('trace' => $e->getTraceAsString())
            );

            $this->_helper->systemMessages('error', 'Event konnte nicht gelöscht werden');
        }

        $this->_redirect('/admin/event');
    }

    public function commentAction()
    {
        $eventId = $this->getRequest()->getParam('id', null);

        try
        {
            $this->view->event = $this->eventRepository->fetchEntity($eventId);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage(), 404);
        }
    }

    public function commentDeleteAction()
    {
        $commentId = $this->getRequest()->getParam('id', null);

        try
        {
            $comment = $this->commentRepository->fetchEntity($commentId);
            $this->commentRepository->deleteEntity($commentId);

            $this->_helper->systemMessages('notice', 'Kommentar erfolgreich gelöscht');
        }
        catch (\Exception $e)
        {
            $log = $this->getInvokeArg('bootstrap')->log;
            $log->log(
                    $e->getMessage(),
                    \Zend_Log::ERR,
                    array('trace' => $e->getTraceAsString())
            );

            $this->_helper->systemMessages('error', 'Kommentar konnte nicht gelöscht werden');
        }

        $this->_redirect('/admin/event/comment/' . $comment->event->id);
    }
}
