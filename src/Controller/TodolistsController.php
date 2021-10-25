<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Todolists Controller
 *
 * @property \App\Model\Table\TodolistsTable $Todolists
 * @method \App\Model\Entity\Todolist[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TodolistsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $todolists = $this->Todolists->find('all')->where('user = ' . $this->Authentication->getIdentity()->getIdentifier());
        $this->set('todolists', $todolists);
        $this->viewBuilder()->setClassName('Json')->setOption('serialize', ['todolists']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $message = "Error, I need a POST request.";
        $todolist = $this->Todolists->newEmptyEntity();
        if ($this->request->is('post')) {
            $todolist = $this->Todolists->patchEntity($todolist, $this->request->getData());
            $todolist->user = $this->Authentication->getIdentity()->getIdentifier();
            if ($this->Todolists->save($todolist)) {
                $message = 'Created';
            } else {
                $message = 'Error when adding the todolist.';
            }
        }
        $this->set([
            'message' => $message,
            'todolist' => $todolist,
        ]);
        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', ['todolist', 'message']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Todolist id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $message = "Error, I need a POST, PATCH or PUT request.";
        $todolist = $this->Todolists->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $todolist = $this->Todolists->patchEntity($todolist, $this->request->getData());
            if ($todolist->user == $this->Authentication->getIdentity()->getIdentifier()) {
                if ($this->Todolists->save($todolist)) {
                    $message = 'Updated';
                } else {
                    $message = 'Error when adding the todolist.';
                }
            } else {
                $message = "You can't edit someone else's todolist.";
            }
        }
        $this->set([
            'message' => $message,
            'todolist' => $todolist,
        ]);
        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', ['todolist', 'message']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Todolist id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $todolist = $this->Todolists->get($id);
        if ($todolist->user == $this->Authentication->getIdentity()->getIdentifier()) {
            if ($this->Todolists->delete($todolist)) {
                $message = 'Deleted';
            } else {
                $message = 'Error when adding the todolist.';
            }
        } else {
            $message = "You can't delete someone else's todolist.";
        }

        $this->set([
            'message' => $message,
        ]);
        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', ['message']);
    }
}
