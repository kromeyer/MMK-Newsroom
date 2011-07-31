<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Admin_NewsController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->newsRepository = $this->_helper
                                     ->entityManager()
                                     ->getRepository('\Newsroom\Entity\News');

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
        $this->view->news = $this->newsRepository->fetchEntities();
    }

    public function addAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $newsForm = new Form_TagForm($configForm->news);

        if ($this->getRequest()->isPost())
        {
            if ($newsForm->isValid($_POST))
            {
                try
                {
                    $values = $newsForm->getValues();
                    $values['user'] = $this->currentUserEntity;
                    $newsId = $this->newsRepository->saveEntity($values);

                    $this->_helper->systemMessages('notice', 'News erfolgreich gespeichert');

                    $this->_redirect('/admin/news/edit/' . $newsId);
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'News konnte nicht gespeichert werden');
                }
            }
        }

        $newsForm->setAction('/admin/news/add');
        $this->view->form = $newsForm;
    }

    public function editAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $newsForm = new Form_TagForm($configForm->news);

        $newsId = $this->getRequest()->getParam('id', null);
        try
        {
            $entity = $this->newsRepository->fetchEntity($newsId);
            $newsForm->image->setOrginValue($entity->image);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage(), 404);
        }

        if ($this->getRequest()->isPost())
        {
            if ($newsForm->isValid($_POST))
            {
                try
                {
                    $values = $newsForm->getValues();
                    $values['user'] = $this->currentUserEntity;
                    $newsId = $this->newsRepository->saveEntity($values);

                    $newsForm->image->setOrginValue($entity->image);

                    $this->_helper->systemMessages('notice', 'News erfolgreich gespeichert');
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'News konnte nicht gespeichert werden');
                }
            }
        }
        else
        {
            $newsForm->populate($entity->toArray());
        }

        $newsForm->setAction('/admin/news/edit/' . $newsId);
        $this->view->form = $newsForm;
    }

    public function deleteAction()
    {
        $newsId = $this->getRequest()->getParam('id', null);

        try
        {
            $this->newsRepository->deleteEntity($newsId);

            $this->_helper->systemMessages('notice', 'News erfolgreich gelöscht');
        }
        catch (\Exception $e)
        {
            $log = $this->getInvokeArg('bootstrap')->log;
            $log->log(
                    $e->getMessage(),
                    \Zend_Log::ERR,
                    array('trace' => $e->getTraceAsString())
            );

            $this->_helper->systemMessages('error', 'News konnte nicht gelöscht werden');
        }

        $this->_redirect('/admin/news');
    }

    public function commentAction()
    {
        $newsId = $this->getRequest()->getParam('id', null);

        try
        {
            $this->view->news = $this->newsRepository->fetchEntity($newsId);
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

        $this->_redirect('/admin/news/comment/' . $comment->news->id);
    }
}
