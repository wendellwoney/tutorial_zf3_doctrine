<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Funcionario;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractActionController
{  
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager; 

    public function __construct(EntityManager $entityManager) 
    {
        $this->entityManager = $entityManager;
    }
    
    public function indexAction()
    {
        
        $request = $this->getRequest();
        $result = array();
        if($request->isPost())
        {
            try{
                $nome = $request->getPost("nome");
                $cpf = $request->getPost("cpf");
                $salario = $request->getPost("salario");

                $funcionario = new Funcionario();
                $funcionario->setNome($nome);
                $funcionario->setCpf($cpf);
                $funcionario->setSalario($salario);
                
                //Adiciona no banco
                $this->entityManager->persist($funcionario);
                $this->entityManager->flush();

                $result["resp"] = $nome. ", enviado corretamente!";
            }  catch (Exception $e){

            }
        }

        return new ViewModel($result);
    }
    
    public function listarAction()
    {
        $lista = $this->entityManager->getRepository("Application\Model\Funcionario")->findAll();
        return new ViewModel(array('lista' => $lista));
    }
    
    public function editarAction()
    {
        $id = $this->params()->fromRoute("id", 0);
        $funcionario = $this->entityManager->find("Application\Model\Funcionario", $id);
        $request = $this->getRequest();
        if($request->isPost()){
            try {
                $nome = $request->getPost("nome");
                $cpf = $request->getPost("cpf");
                $salario = $request->getPost("salario");
                
                $funcionario->setNome($nome);
                $funcionario->setCpf($cpf);
                $funcionario->setSalario($salario);
                
                $this->entityManager->merge($funcionario);
                $this->entityManager->flush();
                
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
            return $this->redirect()->toRoute('application', array('controller' => 'index', 'action' => 'listar'));
        }
        return new ViewModel(array('f' => $funcionario));
    }
    
     public function excluirAction()
    {
        $id = $this->params()->fromRoute("id", 0);
        $funcionario = $this->entityManager->find("Application\Model\Funcionario", $id);
        $this->entityManager->remove($funcionario);
        $this->entityManager->flush();
        return $this->redirect()->toRoute('application',array('controller' => 'index', 'action' => 'listar'));
    }
}
