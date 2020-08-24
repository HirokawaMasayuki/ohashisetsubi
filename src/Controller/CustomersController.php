<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

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

      $arrKeisyou = [
				'1' => '様',
        '2' => '御中'
							];
			$this->set('arrKeisyou',$arrKeisyou);

      $arrMonth = [
				'1' => '当月',
        '2' => '翌月',
        '3' => '翌々月'
							];
			$this->set('arrMonth',$arrMonth);

      $arrKaisyuu = [
				'1' => '振込',
        '2' => '集金'
							];
			$this->set('arrKaisyuu',$arrKaisyuu);
    }

    public function confirm()
    {
      $customers = $this->Customers->newEntity();
      $this->set('customers',$customers);

      $data = $this->request->getData();
  //    echo "<pre>";
	//    print_r($data);
	//    echo "</pre>";

      if($data["keisyou"] == 1){
        $keisyou = "様";
      }elseif($data["keisyou"] == 2){
        $keisyou = "御中";
      }else{
        $keisyou = "";
      }
      $this->set('keisyou',$keisyou);

      if($data["hittyaku_flag"] == 1){
        $hittyaku_flag = "当月";
      }elseif($data["hittyaku_flag"] == 2){
        $hittyaku_flag = "翌月";
      }elseif($data["hittyaku_flag"] == 3){
        $hittyaku_flag = "翌々月";
      }else{
        $hittyaku_flag = "";
      }
      $this->set('hittyaku_flag',$hittyaku_flag);

      if($data["nyuukin_flag"] == 1){
        $nyuukin_flag = "当月";
      }elseif($data["nyuukin_flag"] == 2){
        $nyuukin_flag = "翌月";
      }elseif($data["nyuukin_flag"] == 3){
        $nyuukin_flag = "翌々月";
      }else{
        $nyuukin_flag = "";
      }
      $this->set('nyuukin_flag',$nyuukin_flag);

      if($data["kaisyuu"] == 1){
        $kaisyuu = "振込";
      }elseif($data["kaisyuu"] == 2){
        $kaisyuu = "集金";
      }else{
        $kaisyuu = "";
      }
      $this->set('kaisyuu',$kaisyuu);
    }

    public function do()
    {
      $customers = $this->Customers->newEntity();
      $this->set('customers',$customers);

      $data = $this->request->getData();
  //    echo "<pre>";
	//    print_r($data);
	 //   echo "</pre>";

      if($data["keisyou"] == 1){
        $keisyou = "様";
      }elseif($data["keisyou"] == 2){
        $keisyou = "御中";
      }else{
        $keisyou = "";
      }
      $this->set('keisyou',$keisyou);

      if($data["hittyaku_flag"] == 1){
        $hittyaku_flag = "当月";
      }elseif($data["hittyaku_flag"] == 2){
        $hittyaku_flag = "翌月";
      }elseif($data["hittyaku_flag"] == 3){
        $hittyaku_flag = "翌々月";
      }else{
        $hittyaku_flag = "";
      }
      $this->set('hittyaku_flag',$hittyaku_flag);

      if($data["nyuukin_flag"] == 1){
        $nyuukin_flag = "当月";
      }elseif($data["nyuukin_flag"] == 2){
        $nyuukin_flag = "翌月";
      }elseif($data["nyuukin_flag"] == 3){
        $nyuukin_flag = "翌々月";
      }else{
        $nyuukin_flag = "";
      }
      $this->set('nyuukin_flag',$nyuukin_flag);

      if($data["kaisyuu"] == 1){
        $kaisyuu = "振込";
      }elseif($data["kaisyuu"] == 2){
        $kaisyuu = "集金";
      }else{
        $kaisyuu = "";
      }
      $this->set('kaisyuu',$kaisyuu);

      $customer = $this->Customers->patchEntity($customers, $data);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Customers->save($customer)) {

          $mes = "※下記のように登録されました";
          $this->set('mes',$mes);
          $connection->commit();// コミット5

        } else {

          $mes = "※登録されませんでした";
          $this->set('mes',$mes);
          $this->Flash->error(__('This data could not be saved. Please, try again.'));
          throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

        }

      } catch (Exception $e) {//トランザクション7
      //ロールバック8
        $connection->rollback();//トランザクション9
      }//トランザクション10

    }


    public function edit()
    {

    }

    public function delete()
    {

    }

}
