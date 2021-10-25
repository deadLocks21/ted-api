<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['login', 'add']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity($this->request->getData());
        if(empty($user->login) || empty($user->password)) {
            $message = 'Error, empty password or login.';
        } else {
            if ($this->Users->save($user)) {
                $message = 'Saved';
            } else {
                $message = 'Error, when adding a new user.';
            }
        }

        $this->set([
            'message' => $message,
            'user' => $user,
        ]);
        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', ['user', 'message']);
    }

    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $message = "You are logged";
        }
        if ($this->request->is('post') && !$result->isValid()) {
            $message = "Your login or password is incorrect.";
        }

        $this->set([
            'message' => $message,
        ]);
        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', ['message']);
    }

    public function logout()
    {
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $this->Authentication->logout();
            
            $this->set([
                'message' => "Logged out",
            ]);
            $this->viewBuilder()
                ->setClassName('Json')
                ->setOption('serialize', ['message']);
        }
    }
}
