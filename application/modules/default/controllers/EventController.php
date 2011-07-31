<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Default_EventController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->eventRepository = $this->_helper
                                      ->entityManager()
                                      ->getRepository('\Newsroom\Entity\Event');
        $this->commentRepository = $this->_helper
                                        ->entityManager()
                                        ->getRepository('\Newsroom\Entity\Comment');
    }

    public function indexAction()
    {
        $this->view->headTitle('Events');

        $this->view->events = $this->eventRepository->fetchEntities(9);
    }

    public function detailAction()
    {
        $eventUrl = $this->getRequest()->getParam('url', null);

        try
        {
            $event = $this->eventRepository->fetchEntityByUrl($eventUrl);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage(), 404);
        }

        $this->view->headTitle($event->headline);
        $this->view->headMeta()->setName(
                'description',
                $this->_helper->truncate($event->content, 255)
        );

        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $commentForm = new \Zend_Form($configForm->comment);

        if ($this->getRequest()->isPost())
        {
            if ($commentForm->isValid($_POST))
            {
                try
                {
                    $values = $commentForm->getValues();
                    unset($values['csrf']);
                    unset($values['firstname']); # SpamDetection
                    $values['event'] = $event;
                    $this->commentRepository->saveEntity($values);

                    $commentForm->reset();

                    #$this->_helper->systemMessages('notice', 'Kommentar erfolgreich gespeichert');
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    #$this->_helper->systemMessages('error', 'Kommentar konnte nicht gespeichert werden');
                }
            }
        }

        $commentForm->setAction('/event/' . $eventUrl);
        $this->view->form = $commentForm;

        $this->view->event = $event;
    }
}
