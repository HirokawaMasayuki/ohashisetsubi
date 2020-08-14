<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 *
 * @method \App\Model\Entity\Customer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CustomersController extends AppController
{

    public function index()
    {

    }

    public function menu()
    {

    }

    public function form()
    {
      $customers = $this->Customers->newEntity();
      $this->set('customers',$customers);
    }

    public function view()
    {

    }

    public function add()
    {

    }


    public function edit()
    {

    }

    public function delete()
    {

    }

}
