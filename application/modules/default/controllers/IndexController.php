<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Default_IndexController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->eventRepository = $this->_helper
                                      ->entityManager()
                                      ->getRepository('\Newsroom\Entity\Event');

        $this->newsRepository = $this->_helper
                                     ->entityManager()
                                     ->getRepository('\Newsroom\Entity\News');

        $this->announcementRepository = $this->_helper
                                             ->entityManager()
                                             ->getRepository('\Newsroom\Entity\Announcement');

        $this->serverUrl = 'http://' . $_SERVER['HTTP_HOST'];
    }

    public function indexAction()
    {
        $this->view->news = $this->newsRepository->fetchEntities(3);
        $this->view->events = $this->eventRepository->fetchEntities(1);
        $this->view->announcements = $this->announcementRepository->fetchEntities(3);
    }

    public function legalAction()
    {
        $this->view->headTitle('Impressum');
    }

    public function innovatorAction()
    {
        $this->view->headTitle('Die Erfinder - Das N-Team im Gespräch');
    }

    public function downloadAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $fileId = $this->getRequest()->getParam('id', null);
        try
        {
            $this->fileRepository = $this->_helper
                                         ->entityManager()
                                         ->getRepository('\Newsroom\Entity\File');

            $entity = $this->fileRepository->fetchEntity($fileId);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage(), 404);
        }

        $this->_response->setHeader('Content-Type', $entity->mimetype)
                        ->setHeader('Content-Disposition', 'attachment; filename="' . $entity->name . '"')
                        ->setHeader('Content-Length', $entity->size);

        print $entity->data;
    }

    public function rssAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $feed = $this->_createFeed();
        $feed->setFeedLink($this->serverUrl . '/rss', 'rss');

        $this->_response->setHeader('Content-Type', 'application/rss+xml');

        print $feed->export('rss');
    }

    public function atomAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $feed = $this->_createFeed();
        $feed->setFeedLink($this->serverUrl . '/atom', 'atom');

        $this->_response->setHeader('Content-Type', 'application/atom+xml');

        print $feed->export('atom');
    }

    protected function _createFeed()
    {
        $config = $this->getInvokeArg('bootstrap')->getOption('configuration');
        $markdown = new \Markdown\Adapter();

        $feed = new \Zend_Feed_Writer_Feed();
        $feed->setDateCreated();
        $feed->setTitle($config['feed']['title']);
        $feed->setDescription($config['feed']['description']);
        $feed->setLink($this->serverUrl);

        $items = array_merge(
            $this->newsRepository->fetchEntities(6),
            $this->eventRepository->fetchEntities(3)
        );

        foreach ($items as $item)
        {
            $entry = $feed->createEntry();
            $entry->setTitle($item->headline);

            if ($item instanceof \Newsroom\Entity\News)
            {
                $entry->setLink($this->serverUrl . '/news/' . $item->url);
            }
            else if ($item instanceof \Newsroom\Entity\Event)
            {
                $entry->setLink($this->serverUrl . '/event/' . $item->url);
            }

            $author  = isset ($item->user->title) ? $item->user->title . ' ' : '';
            $author .= $item->user->firstname;
            $author .= ' ' . $item->user->lastname;

            $entry->addAuthor($author);
            $entry->setContent($markdown->markdown($item->content));
            $entry->setDateCreated($item->getCreate('U'));
            $entry->setDateModified($item->getUpdate('U'));

            $feed->addEntry($entry);
        }

        return $feed;
    }
}
