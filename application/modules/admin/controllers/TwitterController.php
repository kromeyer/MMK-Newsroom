<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Admin_TwitterController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->twitterRepository = $this->_helper
                                        ->entityManager()
                                        ->getRepository('\Newsroom\Entity\Twitter');
    }

    public function indexAction()
    {
        $session = new \Zend_Session_Namespace('twitter', true);

        $oauthConfig = array(
            'callbackUrl'    => 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'],
            'siteUrl'        => 'http://twitter.com/oauth'
        );

        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $twitterApiForm = new \Zend_Form($configForm->twitterApi);

        if ($this->getRequest()->isPost())
        {
            if ($twitterApiForm->isValid($_POST))
            {
                try
                {
                    $this->twitterRepository->saveEntity($twitterApiForm->getValues());

                    $oauthConfig['consumerKey'] = $twitterApiForm->getValue('consumerKey');
                    $oauthConfig['consumerSecret'] = $twitterApiForm->getValue('consumerSecret');

                    $consumer = new \Zend_Oauth_Consumer($oauthConfig);
                    $token = $consumer->getRequestToken();
                    $session->twitterRequestToken = serialize($token);
                    $consumer->redirect();
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
            try
            {
                $entity = $this->twitterRepository->fetchEntity();

                if ($entity)
                {
                    if (isset($session->twitterRequestToken))
                    {
                        $oauthConfig['consumerKey'] = $entity->consumerKey;
                        $oauthConfig['consumerSecret'] = $entity->consumerSecret;

                        $consumer = new \Zend_Oauth_Consumer($oauthConfig);
                        $token = $consumer->getAccessToken(
                            $_GET,
                            unserialize($session->twitterRequestToken)
                        );

                        $this->twitterRepository->saveEntity(
                            array('accessToken' => serialize($token))
                        );

                        unset($session->twitterRequestToken);

                        $this->_helper->systemMessages('notice', 'Einstellungen erfolgreich gespeichert');
                    }
                    $twitterApiForm->populate($entity->toArray());
                }
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

        $twitterApiForm->setAction('/admin/twitter');
        $this->view->form = $twitterApiForm;
    }

    public function deleteAction()
    {
        try
        {
            $this->twitterRepository->deleteEntity();

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

        $this->_redirect('/admin/twitter');
    }
}
