<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Admin_UserController extends \Zend_Controller_Action
{
    public function init()
    {
        $this->userRepository = $this->_helper
                                     ->entityManager()
                                     ->getRepository('\Newsroom\Entity\User');
    }

    public function indexAction()
    {
        $this->view->users = $this->userRepository->fetchEntities();
    }

    public function addAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $userForm = new \Zend_Form($configForm->user);

        if ($this->getRequest()->isPost())
        {
            if ($userForm->isValid($_POST))
            {
                try
                {
                    $values = $userForm->getValues();
                    unset($values['password_repeat']);

                    $userId = $this->userRepository->saveEntity($values);

                    $this->_helper->systemMessages('notice', 'Nutzer erfolgreich gespeichert');

                    $this->_redirect('/admin/user/edit/' . $userId);
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Nutzer konnte nicht gespeichert werden');
                }
            }
        }

        $userForm->setAction('/admin/user/add');
        $this->view->form = $userForm;
    }

    public function editAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $userForm = new Zend_Form($configForm->user);

        $userId = $this->getRequest()->getParam('id', null);

        if ($this->getRequest()->isPost())
        {
            if ($userForm->isValid($_POST))
            {
                try
                {
                    $values = $userForm->getValues();
                    unset($values['password_repeat']);

                    $userId = $this->userRepository->saveEntity($values);

                    $this->_helper->systemMessages('notice', 'Nutzer erfolgreich gespeichert');
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Nutzer konnte nicht gespeichert werden');
                }
            }
        }
        else
        {
            try
            {
                $entity = $this->userRepository->fetchEntity($userId);
                $userForm->populate($entity->toArray());
            }
            catch (\Exception $e)
            {
                throw new \Exception($e->getMessage(), 404);
            }
        }

        $userForm->setAction('/admin/user/edit/' . $userId);
        $this->view->form = $userForm;
    }

    public function deleteAction()
    {
        $userId = $this->getRequest()->getParam('id', null);

        try
        {
            $this->userRepository->deleteEntity($userId);

            $this->_helper->systemMessages('notice', 'Nutzer erfolgreich gelöscht');
        }
        catch (\Exception $e)
        {
            $log = $this->getInvokeArg('bootstrap')->log;
            $log->log(
                    $e->getMessage(),
                    \Zend_Log::ERR,
                    array('trace' => $e->getTraceAsString())
            );

            $this->_helper->systemMessages('error', 'Nutzer konnte nicht gelöscht werden');
        }

        $this->_redirect('/admin/user');
    }
}
