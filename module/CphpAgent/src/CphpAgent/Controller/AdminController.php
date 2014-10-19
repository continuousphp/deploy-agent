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

    public function __construct()
    {
//        $authService = $this->getServiceLocator()->get('cphp-agent.service.auth');
//        if ($authService->hasIdentity())
//            $this->redirect()->toRoute('zfcadmin/deployments');
    }

    public function indexAction()
    {
    }

    public function loginAction()
    {
        $error = '';
        $authService = $this->getServiceLocator()->get('cphp-agent.service.auth');
        if ($authService->hasIdentity())
            return $this->redirect()->toRoute('zfcadmin/deployments');

        $data = $this->getRequest()->getPost();
        if ($this->getRequest()->isPost()) {
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

        return new ViewModel(array(
            'error' => $error,
        ));
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
            if ($this->getRequest()->isPost() && !empty($data)) {
                $data['created'] = date('Y/m/d H:i:s');
                $data['updated'] = date('Y/m/d H:i:s');

//                $bcrypt = new Bcrypt();
//                $data['password'] = $bcrypt->create($data['password']);
                var_dump($data->toArray());

                $userService = $this->getServiceLocator()->get('cphp-agent.service.user');
                $user = new \CphpAgent\Entity\User();
                $user->exchangeArray($data->toArray());
                $userService->store($user);
                var_dump($userService->findAll());
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
