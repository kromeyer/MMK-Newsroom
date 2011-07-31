<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Default_NewsController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->newsRepository = $this->_helper
                                     ->entityManager()
                                     ->getRepository('\Newsroom\Entity\News');
        $this->commentRepository = $this->_helper
                                        ->entityManager()
                                        ->getRepository('\Newsroom\Entity\Comment');
    }

    public function indexAction()
    {
        $this->view->headTitle('News');

        $this->view->news = $this->newsRepository->fetchEntities(9);
    }

    public function detailAction()
    {
        $newsUrl = $this->getRequest()->getParam('url', null);

        try
        {
            $news = $this->newsRepository->fetchEntityByUrl($newsUrl);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage(), 404);
        }

        $this->view->headTitle($news->headline);
        $this->view->headMeta()->setName(
                'description',
                $this->_helper->truncate($news->content, 255)
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
                    $values['news'] = $news;
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

        $commentForm->setAction('/news/' . $newsUrl);
        $this->view->form = $commentForm;

        $this->view->news = $news;
    }
}
