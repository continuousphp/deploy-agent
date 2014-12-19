<?php

namespace CphpAgent\Controller;

use CphpAgent\Mapper\Exception;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use CphpAgent\Config\ConfigAwareInterface;
use Zend\View\Model\ViewModel;


class AdminController extends AbstractActionController implements ConfigAwareInterface, ServiceLocatorAwareInterface
{
    /** @var  Config */
    protected $config;

    public function indexAction()
    {
        return $this->redirect()->toRoute('zfcadmin/login');
    }

    public function loginAction()
    {
        $error = '';
        $authService = $this->getServiceLocator()->get('cphp-agent.service.auth');
        if ($authService->hasIdentity())
            return $this->redirect()->toRoute('zfcadmin/deployments');

        $data = $this->getRequest()->getPost();
        if ($this->getRequest()->isPost() && (!empty($data['password']) && !empty($data['username']))) {

            $adapter = $authService->getAdapter();
            $adapter->setIdentityValue($data['username']);
            $adapter->setCredentialValue($data['password']);
            $authResult = $authService->authenticate();

            if ($authResult->isValid()) {
                return $this->redirect()->toRoute('zfcadmin/deployments');
            } else {
                $error = 'Your authentication credentials are not valid';
            }
        }

        $form = $this->getServiceLocator()->get('cphp-agent.login.form');
        return new ViewModel(array(
            'form' => $form,
            'error' => $error,
        ));
    }

    public function logoutAction()
    {
        $authService = $this->getServiceLocator()->get('cphp-agent.service.auth');
        $authService->clearIdentity();
        return $this->redirect()->toRoute('zfcadmin/deployments');
    }

    public function deploymentsAction()
    {
        $buildMapper = $this->getServiceLocator()->get('cphp-agent.mapper.build');
        return new ViewModel([
            'deployments' => $buildMapper->findAll(),
        ]);
    }

    public function addUserAction()
    {
        $error='';
        $data = $this->getRequest()->getPost();
        try {
            if ($this->getRequest()->isPost() && (!empty($data['password']) && !empty($data['username']))) {

                $crypt = new Bcrypt();
                $data['password'] = $crypt->create($data['password']);
                $userService = $this->getServiceLocator()->get('cphp-agent.service.user');

                $user = new \CphpAgent\Entity\User();
                $user->exchangeArray($data->toArray());
                $user->setCreated();
                $user->setUpdated(new \DateTime('now'));
                $userService->persist($user);
            }else{
                $error = 'Fields cannot be empty';
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return new ViewModel(array(
            'error' => $error,
        ));
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }
}
