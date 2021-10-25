<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;

/**
 * Tasks Controller
 *
 * @property \App\Model\Table\TasksTable $Tasks
 * @method \App\Model\Entity\Task[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TasksController extends AppController
{
    public function isMyTodo($id): bool
    {
        $Todolists = TableRegistry::getTableLocator()->get('Todolists');
        $todo = $Todolists->get($id);

        return $todo->user == $this->Authentication->getIdentity()->getIdentifier();
    }
    /**
     * listByTodolist method
     *
     * @param string|null $id Task id.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function listByTodolist($id = null)
    {
        $tasks = [];

        if ($this->isMyTodo($id)) {
            $tasks = $this->Tasks->find('all')->where('todolist = ' . $id);
            $message = "Successfully recovered";
        } else {
            $message = "Sorry, this todolist does not belong to you ";
        }
        $this->set([
            'message' => $message,
            'tasks' => $tasks,
        ]);
        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', ['tasks', 'message']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $message = "Error, I need a POST request.";
        $task = $this->Tasks->newEmptyEntity();
        if ($this->request->is('post')) {
            if ($this->isMyTodo($id)) {
                $task = $this->Tasks->patchEntity($task, $this->request->getData());
                $task->todolist = $id;
                $task->completed = false;
                if ($this->Tasks->save($task)) {
                    $message = 'Created';
                } else {
                    $message = 'Error when adding the task.';
                }
            } else {
                $message = "Sorry, this todolist does not belong to you ";
            }
        }
        $this->set([
            'message' => $message,
            'task' => $task,
        ]);
        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', ['task', 'message']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Task id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $message = "Error, I need a POST, PATCH or PUT request.";
        $task = $this->Tasks->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $task = $this->Tasks->patchEntity($task, $this->request->getData());
            $task->completed = $this->request->getData()['completed'] == "true";
            if ($this->isMyTodo($task->todolist)) {
                if ($this->Tasks->save($task)) {
                    $message = 'Updated';
                } else {
                    $message = 'Error when editting the task.';
                }
            } else {
                $message = "Sorry, this todolist does not belong to you ";
            }
        }
        $this->set([
            'message' => $message,
            'task' => $task,
        ]);
        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', ['task', 'message']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Task id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $task = $this->Tasks->get($id);
        if ($this->isMyTodo($task->todolist)) {
            if ($this->Tasks->delete($task)) {
                $message = 'Deleted';
            } else {
                $message = 'Error when adding the task.';
            }
        } else {
            $message = "Sorry, this todolist does not belong to you ";
        }

        $this->set([
            'message' => $message,
        ]);
        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', ['message']);
    }
}
