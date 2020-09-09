<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 *
 * @method \App\Model\Entity\Customer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CustomersController extends AppController
{

      public function initialize()
      {
        parent::initialize();
        $this->Suppliers = TableRegistry::get('suppliers');
      }

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

    public function gaityumenu()
    {

    }

    public function gaityuform()
    {
      $suppliers = $this->Suppliers->newEntity();
      $this->set('suppliers',$suppliers);

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

    public function gaityuconfirm()
    {
      $suppliers = $this->Suppliers->newEntity();
      $this->set('suppliers',$suppliers);

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

    public function gaityudo()
    {
      $suppliers = $this->Suppliers->newEntity();
      $this->set('suppliers',$suppliers);

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

      $supplier = $this->Suppliers->patchEntity($suppliers, $data);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Suppliers->save($supplier)) {

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

    public function editichiran()
    {
      $Customer = $this->Customers->newEntity();
			$this->set('Customer', $Customer);

      $Customers = $this->Customers->find()->where(['delete_flag' => 0])->order(["furigana"=>"ASC"]);
      $this->set('Customers',$Customers);

    }

    public function editsyuusei()
    {
      $Customer = $this->Customers->newEntity();
			$this->set('Customer', $Customer);

      $data = $this->request->getData();

      $data = array_keys($data, '修正');
/*
      echo "<pre>";
      print_r($data[0]);
      echo "</pre>";
*/
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

      $id = $data[0];
      $this->set('id',$id);
      $Customers = $this->Customers->find()->where(['id' => $data[0]])->toArray();
      $name = $Customers[0]['name'];
      $this->set('name',$name);
      $furigana = $Customers[0]['furigana'];
      $this->set('furigana',$furigana);
      $siten = $Customers[0]['siten'];
      $this->set('siten',$siten);
      $tel = $Customers[0]['tel'];
      $address = $Customers[0]['address'];
      $this->set('address',$address);
      $this->set('tel',$tel);
      $fax = $Customers[0]['fax'];
      $this->set('fax',$fax);
      $yuubin = $Customers[0]['yuubin'];
      $this->set('yuubin',$yuubin);
      $simebi = $Customers[0]['simebi'];
      $this->set('simebi',$simebi);
      $hittyaku_flag = $Customers[0]['hittyaku_flag'];
      $this->set('hittyaku_flag',$hittyaku_flag);
      $hittyakubi = $Customers[0]['hittyakubi'];
      $this->set('hittyakubi',$hittyakubi);
      $nyuukin_flag = $Customers[0]['nyuukin_flag'];
      $this->set('nyuukin_flag',$nyuukin_flag);
      $nyuukinbi = $Customers[0]['nyuukinbi'];
      $this->set('nyuukinbi',$nyuukinbi);
      $nyuukin_flag = $Customers[0]['nyuukin_flag'];
      $this->set('nyuukin_flag',$nyuukin_flag);
      $nyuukinbi = $Customers[0]['nyuukinbi'];
      $this->set('nyuukinbi',$nyuukinbi);
      $keisyou = $Customers[0]['keisyou'];
      $this->set('keisyou',$keisyou);
      $kaisyuu = $Customers[0]['kaisyuu'];
      $this->set('kaisyuu',$kaisyuu);
      $tantou = $Customers[0]['tantou'];
      $this->set('tantou',$tantou);
      $tantou_tel = $Customers[0]['tantou_tel'];
      $this->set('tantou_tel',$tantou_tel);

    }

    public function editconfirm()
    {
      $Customer = $this->Customers->newEntity();
			$this->set('Customer', $Customer);

      $data = $this->request->getData();
/*
      echo "<pre>";
	    print_r($data);
	    echo "</pre>";
*/
      if($data["delete_flag"] == 1){
        $mess = "以下のデータを削除します。";
      }else{
        $mess = "顧客情報修正";
      }
      $this->set('mess',$mess);

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

    public function editdo()
    {
      $Customer = $this->Customers->newEntity();
			$this->set('Customer', $Customer);

      $data = $this->request->getData();
/*
      echo "<pre>";
	    print_r($data);
	    echo "</pre>";
*/
      if($data["delete_flag"] == 1){
        $mess = "以下のデータを削除しました。";
      }else{
        $mess = "以下のように更新しました。";
      }
      $this->set('mess',$mess);

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

      $customer = $this->Customers->patchEntity($Customer, $data);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Customers->updateAll(
          ['name' => $data['name'], 'furigana' => $data['furigana'], 'siten' => $data['siten'], 'address' => $data['address'], 'tel' => $data['tel']
          , 'fax' => $data['fax'], 'yuubin' => $data['yuubin'], 'simebi' => $data['simebi'], 'hittyaku_flag' => $data['hittyaku_flag'], 'hittyakubi' => $data['hittyakubi']
          , 'nyuukin_flag' => $data['nyuukin_flag'], 'nyuukinbi' => $data['nyuukinbi'], 'keisyou' => $data['keisyou'], 'kaisyuu' => $data['kaisyuu'], 'tantou' => $data['tantou']
          , 'tantou_tel' => $data['tantou_tel'], 'delete_flag' => $data['delete_flag'], 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
          ['id'  => $data['id']]
        )){

          $connection->commit();// コミット5

        } else {

          $mes = "※更新されませんでした";
          $this->set('mes',$mes);
          $this->Flash->error(__('This data could not be saved. Please, try again.'));
          throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

        }

      } catch (Exception $e) {//トランザクション7
      //ロールバック8
        $connection->rollback();//トランザクション9
      }//トランザクション10


    }

    public function gaityuedit()
    {

    }

    public function veiw()
    {
      $Customer = $this->Customers->newEntity();
			$this->set('Customer', $Customer);

      $Customers = $this->Customers->find()->where(['delete_flag' => 0])->order(["furigana"=>"ASC"]);
      $this->set('Customers',$Customers);
    }

    public function gaityuveiw()
    {

    }

    public function delete()
    {

    }

}
