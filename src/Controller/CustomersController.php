<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

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
        '2' => '御中',
        '3' => '殿'
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
      }elseif($data["keisyou"] == 3){
        $keisyou = "殿";
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
      }elseif($data["keisyou"] == 3){
        $keisyou = "殿";
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
        '2' => '御中',
        '3' => '殿'
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
      }elseif($data["keisyou"] == 3){
        $keisyou = "殿";
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
      }elseif($data["keisyou"] == 3){
        $keisyou = "殿";
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
        '2' => '御中',
        '3' => '殿'
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
      }elseif($data["keisyou"] == 3){
        $keisyou = "殿";
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
      }elseif($data["keisyou"] == 3){
        $keisyou = "殿";
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

    public function gaityueditichiran()
    {
      $suppliers = $this->Suppliers->newEntity();
      $this->set('suppliers',$suppliers);

      $Suppliers = $this->Suppliers->find()->where(['delete_flag' => 0])->order(["furigana"=>"ASC"]);
      $this->set('Suppliers',$Suppliers);

    }

    public function gaityueditsyuusei()
    {
      $suppliers = $this->Suppliers->newEntity();
      $this->set('suppliers',$suppliers);

      $data = $this->request->getData();

      $data = array_keys($data, '修正');
/*
      echo "<pre>";
      print_r($data[0]);
      echo "</pre>";
*/
      $arrKeisyou = [
        '1' => '様',
        '2' => '御中',
        '3' => '殿'
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
      $Suppliers = $this->Suppliers->find()->where(['id' => $data[0]])->toArray();
      $name = $Suppliers[0]['name'];
      $this->set('name',$name);
      $furigana = $Suppliers[0]['furigana'];
      $this->set('furigana',$furigana);
      $siten = $Suppliers[0]['siten'];
      $this->set('siten',$siten);
      $tel = $Suppliers[0]['tel'];
      $address = $Suppliers[0]['address'];
      $this->set('address',$address);
      $this->set('tel',$tel);
      $fax = $Suppliers[0]['fax'];
      $this->set('fax',$fax);
      $yuubin = $Suppliers[0]['yuubin'];
      $this->set('yuubin',$yuubin);
      $simebi = $Suppliers[0]['simebi'];
      $this->set('simebi',$simebi);
      $hittyaku_flag = $Suppliers[0]['hittyaku_flag'];
      $this->set('hittyaku_flag',$hittyaku_flag);
      $hittyakubi = $Suppliers[0]['hittyakubi'];
      $this->set('hittyakubi',$hittyakubi);
      $nyuukin_flag = $Suppliers[0]['nyuukin_flag'];
      $this->set('nyuukin_flag',$nyuukin_flag);
      $nyuukinbi = $Suppliers[0]['nyuukinbi'];
      $this->set('nyuukinbi',$nyuukinbi);
      $nyuukin_flag = $Suppliers[0]['nyuukin_flag'];
      $this->set('nyuukin_flag',$nyuukin_flag);
      $nyuukinbi = $Suppliers[0]['nyuukinbi'];
      $this->set('nyuukinbi',$nyuukinbi);
      $keisyou = $Suppliers[0]['keisyou'];
      $this->set('keisyou',$keisyou);
      $kaisyuu = $Suppliers[0]['kaisyuu'];
      $this->set('kaisyuu',$kaisyuu);
      $tantou = $Suppliers[0]['tantou'];
      $this->set('tantou',$tantou);
      $tantou_tel = $Suppliers[0]['tantou_tel'];
      $this->set('tantou_tel',$tantou_tel);

    }

    public function gaityueditconfirm()
    {
      $suppliers = $this->Suppliers->newEntity();
      $this->set('suppliers',$suppliers);

      $data = $this->request->getData();
/*
      echo "<pre>";
	    print_r($data);
	    echo "</pre>";
*/
      if($data["delete_flag"] == 1){
        $mess = "以下のデータを削除します。";
      }else{
        $mess = "外注先情報修正";
      }
      $this->set('mess',$mess);

      if($data["keisyou"] == 1){
        $keisyou = "様";
      }elseif($data["keisyou"] == 2){
        $keisyou = "御中";
      }elseif($data["keisyou"] == 3){
        $keisyou = "殿";
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

    public function gaityueditdo()
    {
      $suppliers = $this->Suppliers->newEntity();
      $this->set('suppliers',$suppliers);

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
      }elseif($data["keisyou"] == 3){
        $keisyou = "殿";
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
        if ($this->Suppliers->updateAll(
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

    public function veiw()
    {
      $Customer = $this->Customers->newEntity();
			$this->set('Customer', $Customer);

      $Customers = $this->Customers->find()->where(['delete_flag' => 0])->order(["furigana"=>"ASC"]);
      $this->set('Customers',$Customers);
    }

    public function gaityuveiw()
    {
      $suppliers = $this->Suppliers->newEntity();
      $this->set('suppliers',$suppliers);

      $Suppliers = $this->Suppliers->find()->where(['delete_flag' => 0])->order(["furigana"=>"ASC"]);
      $this->set('Suppliers',$Suppliers);
    }

    public function printmennu()
    {
      $Customer = $this->Customers->newEntity();
      $this->set('Customer', $Customer);

      $Customers = $this->Customers->find()->where(['delete_flag' => 0])->order(["furigana"=>"ASC"]);
      $this->set('Customers',$Customers);
    }

    public function printcustomer()
    {
      $Customer = $this->Customers->newEntity();
      $this->set('Customer', $Customer);

      $Customers = $this->Customers->find()->where(['delete_flag' => 0])->order(["id"=>"ASC"])->toArray();
      $this->set('Customers',$Customers);
      $Suppliers = $this->Suppliers->find()->where(['delete_flag' => 0])->order(["id"=>"ASC"])->toArray();

      $Customers = array_merge($Customers, $Suppliers);

      $data = $this->request->getData();

      $mess = "登録済み取引先データをエクセルに出力します。";
      if(isset($data["confirm"])){

        $filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\取引先データ一覧.xlsx'; //読み込みたいファイルの指定
        $reader = new XlsxReader();
        $spreadsheet = $reader->load($filepath);
        $sheet = $spreadsheet->getSheetByName("Sheet1");

        $syou = floor(count($Customers)/24);

        for($j=2; $j<2+$syou; $j++){

          $baseSheet = $spreadsheet->getSheet(0);
          $newSheet = $baseSheet->copy();
          $newSheet->setTitle( "Sheet".$j );
          $spreadsheet->addSheet( $newSheet );

          $writer = new XlsxWriter($spreadsheet);

        }

          for($j=1; $j<26; $j++){

            $num = 2*$j;
            $numodd = 2*$j+1;
            $No = $j;

              $sheet->setCellValue("A".$num, $No);
              $sheet->setCellValue("B".$num, $Customers[$j-1]["id"]);
              $sheet->setCellValue("C".$num, $Customers[$j-1]["name"]);
              $sheet->setCellValue("C".$numodd, $Customers[$j-1]["furigana"]);
              $sheet->setCellValue("D".$num, $Customers[$j-1]["yuubin"]);
              $sheet->setCellValue("E".$num, $Customers[$j-1]["address"]);

          }

          $writer = new XlsxWriter($spreadsheet);

          for($j=2; $j<2+$syou; $j++){

            if($j < $syou + 1){

              for($i=1; $i<26; $i++){

                $num = 2*$i;
                $No = $i + 25*($j - 1);

                $sheet = $spreadsheet->getSheetByName("Sheet".$j);
                $sheet->setCellValue("A".$num, $No);
                $sheet->setCellValue("B".$num, $Customers[$No-1]["id"]);
                $sheet->setCellValue("C".$num, $Customers[$No-1]["name"]);
                $sheet->setCellValue("C".$numodd, $Customers[$No-1]["furigana"]);
                $sheet->setCellValue("D".$num, $Customers[$No-1]["yuubin"]);
                $sheet->setCellValue("E".$num, $Customers[$No-1]["address"]);

                }

            }else{//最後のシートの時

              $last = count($Customers) - $syou * 25;
              for($i=1; $i<=$last; $i++){

                $num = 2*$i;
                $No = $i + 25*($j - 1);

                $sheet = $spreadsheet->getSheetByName("Sheet".$j);
                $sheet->setCellValue("A".$num, $No);
                $sheet->setCellValue("B".$num, $Customers[$No-1]["id"]);
                $sheet->setCellValue("C".$num, $Customers[$No-1]["name"]);
                $sheet->setCellValue("C".$numodd, $Customers[$No-1]["furigana"]);
                $sheet->setCellValue("D".$num, $Customers[$No-1]["yuubin"]);
                $sheet->setCellValue("E".$num, $Customers[$No-1]["address"]);

                }

            }

            }

          $datetime = date('H時i分s秒出力', strtotime('+9hour'));
          $year = date('Y', strtotime('+9hour'));
          $month = date('m', strtotime('+9hour'));
          $day = date('d', strtotime('+9hour'));
          $date_m = date('m', strtotime('+9hour'));
          $date_y = date('Y', strtotime('+9hour'));

        if(is_dir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/取引先データ一覧/$year/$month/$day")){//ディレクトリが存在すればOK

          $file_name = "取引先データ一覧_".$year."-".$month."-".$day."-".$datetime.".xlsx";
          $outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/取引先データ一覧/$year/$month/$day/$file_name"; //出力したいファイルの指定

        }else{//ディレクトリが存在しなければ作成する

          mkdir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/取引先データ一覧/$year/$month/$day", 0777, true);
          $file_name = "取引先データ一覧_".$year."-".$month."-".$day."-".$datetime.".xlsx";
          $outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/取引先データ一覧/$year/$month/$day/$file_name"; //出力したいファイルの指定

        }

        $writer->save($outfilepath);

        $mess = "「エクセル出力/取引先データ一覧/".$year."/".$month."/".$day."」フォルダにエクセルシート「".$file_name."」が出力されました。";

      }

      $this->set('mess',$mess);
/*
      echo "<pre>";
      print_r(count($Customers));
      echo "</pre>";
      echo "<pre>";
      print_r($Customers[0]);
      echo "</pre>";
      echo "<pre>";
      print_r($Customers[count($Customers)-1]);
      echo "</pre>";
*/
    }

}
