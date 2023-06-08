<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Auth\DefaultPasswordHasher;//

use App\myClass\Rolecheck\htmlRolecheck;

class ShinkiesController extends AppController {

  public function initialize()
  {
    parent::initialize();
    $this->Customers = TableRegistry::get('customers');
    $this->Suppliers = TableRegistry::get('suppliers');
    $this->Uriages = TableRegistry::get('uriages');
    $this->Nyuukins = TableRegistry::get('nyuukins');
    $this->Seikyuus = TableRegistry::get('seikyuus');
    $this->Zandakas = TableRegistry::get('zandakas');
    $this->Miseikyuus = TableRegistry::get('miseikyuus');
    $this->Uriagemasters = TableRegistry::get('uriagemasters');
    $this->Uriagesyousais = TableRegistry::get('uriagesyousais');
  }

  public function index()
  {


  }

  public function menu()
  {

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

  }

  public function csv1()
  {
    $fp = fopen("customer.csv", "r");//csvファイルはwebrootに入れる
    $fpcount = fopen("customer.csv", 'r' );
    for( $count = 0; fgets( $fpcount ); $count++ );
    $this->set('count',$count);

    $arrFp = array();//空の配列を作る
    $line = fgets($fp);//ファイル$fpの上の１行を取る（１行目）
    for ($k=1; $k<$count; $k++) {//行数分
      $line = fgets($fp);//ファイル$fpの上の１行を取る（２行目から）
      $sample = explode(',',$line);//$lineを","毎に配列に入れる

      $keys=array_keys($sample);
      $keys[array_search('0',$keys)]='id';//「A」は「10」に変換
      $keys[array_search('1',$keys)]='name';
      $keys[array_search('2',$keys)]='furigana';
      $keys[array_search('3',$keys)]='yuubin';
      $keys[array_search('4',$keys)]='address';
      $keys[array_search('5',$keys)]='tel';
      $keys[array_search('6',$keys)]='fax';
      $keys[array_search('7',$keys)]='simebi';
      $keys[array_search('8',$keys)]='hittyakubi';
      $keys[array_search('9',$keys)]='nyuukinbi';
      $keys[array_search('10',$keys)]='kaisyuu';
      $keys[array_search('11',$keys)]='keisyou';
      $keys[array_search('12',$keys)]='tantou';
      $keys[array_search('13',$keys)]='tantou_tel';
      $keys[array_search('14',$keys)]='seikyuusakicustomerId';

      $sample = array_combine( $keys, $sample );

      $sample = array_merge($sample,array('delete_flag'=>0));
      $sample = array_merge($sample,array('created_at'=>date("Y-m-d H:i:s", strtotime('+9hour'))));

      unset($sample['15']);
      unset($sample['16']);

      $arrFp[] = $sample;//配列に追加する
    }

    for($k=0; $k<count($arrFp); $k++){

      if($arrFp[$k]['seikyuusakicustomerId'] > 1){
        $this->Customers->updateAll(
          ['seikyuusakicustomerId' => $arrFp[$k]['seikyuusakicustomerId']],
          ['name'  => $arrFp[$k]['name']]
        );
      }

    }

  }

  public function bunrui()//http://localhost/CakePHPapp/shinkies/bunrui
  {
    $fp = fopen("bunrui2107156.csv", "r");//csvファイルはwebrootに入れる
    $fpcount = fopen("bunrui2107156.csv", 'r' );
    for( $count = 0; fgets( $fpcount ); $count++ );
    $this->set('count',$count);

    $arrFp = array();//空の配列を作る
    $line = fgets($fp);//ファイル$fpの上の１行を取る（１行目）
    for ($k=1; $k<$count; $k++) {//行数分
      $line = fgets($fp);//ファイル$fpの上の１行を取る（２行目から）
      $sample = explode(',',$line);//$lineを","毎に配列に入れる

      $keys=array_keys($sample);
      $keys[array_search('0',$keys)]='denpyou_num';//「A」は「10」に変換
      $keys[array_search('5',$keys)]='bunrui';

      $sample = array_combine( $keys, $sample );

      unset($sample['1']);
      unset($sample['2']);
      unset($sample['3']);
      unset($sample['4']);
      unset($sample['6']);
      unset($sample['7']);
      unset($sample['8']);
      unset($sample['9']);
      unset($sample['10']);
      unset($sample['11']);
      unset($sample['12']);
      unset($sample['13']);
      unset($sample['14']);
      unset($sample['15']);
      unset($sample['16']);
      unset($sample['17']);
      unset($sample['18']);

      $arrFp[] = $sample;//配列に追加する

      $Uriagemasters = $this->Uriagemasters->find('all', ['conditions' => ['denpyou_num' => $arrFp[$k-1]['denpyou_num']]])->toArray();
      if(isset($Uriagemasters[0])){

        $this->Uriagemasters->updateAll(
          ['bunrui' => $arrFp[$k-1]['bunrui'], 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
          ['denpyou_num'  => $arrFp[$k-1]['denpyou_num']]);

        }else{
          echo "<pre>";
          print_r($arrFp);
          echo "</pre>";
        }

      }

    }

    public function syousaicsv()//http://localhost/CakePHPapp/shinkies/syousaicsv
    {

      //     $fp = fopen("uriage1-10000.csv", "r");//csvファイルはwebrootに入れる
      //     $fpcount = fopen("uriage1-10000.csv", 'r' );

      $fp = fopen("syousai220120.csv", "r");//csvファイルはwebrootに入れる
      $fpcount = fopen("syousai220120.csv", 'r' );

      for( $count = 0; fgets( $fpcount ); $count++ );
      $this->set('count',$count);

      $arrFp = array();//空の配列を作る
      $line = fgets($fp);//ファイル$fpの上の１行を取る（１行目）
      for ($k=1; $k<$count; $k++) {//行数分
        $line = fgets($fp);//ファイル$fpの上の１行を取る（２行目から）
        $sample = explode(',',$line);//$lineを","毎に配列に入れる

        $keys=array_keys($sample);
        $keys[array_search('0',$keys)]='uriagemasterId';
        $keys[array_search('1',$keys)]='uriagebi';
        $keys[array_search('2',$keys)]='num';
        $keys[array_search('3',$keys)]='pro';
        $keys[array_search('4',$keys)]='tani';
        $keys[array_search('5',$keys)]='amount';
        $keys[array_search('6',$keys)]='tanka';
        $keys[array_search('7',$keys)]='price';
        $keys[array_search('8',$keys)]='bik';
        $keys[array_search('9',$keys)]='customerId';

        $sample = array_combine( $keys, $sample );

        if($sample['uriagemasterId'] > 1){

        }else{
          echo "<pre>";
          print_r($k);
          echo "</pre>";
        }

        $sample = array_merge($sample,array('delete_flag'=>0));
        $sample = array_merge($sample,array('created_at'=>date("Y-m-d 23:00:00")));

        $Uriagemasters = $this->Uriagemasters->find('all', ['conditions' => ['id' => $sample['uriagemasterId']]])->toArray();
        if(!isset($Uriagemasters[0])){

          $Uriagemasters2 = $this->Uriagemasters->find('all',
          ['conditions' => ['uriagebi' => $sample['uriagebi'], 'customerId' => (int)$sample['customerId']]])->toArray();
          if(!isset($Uriagemasters2[0])){

            unset($sample['9']);
            unset($sample['10']);

            $arrFp[] = $sample;//配列に追加する

          }

        }

      }

      foreach($arrFp as $key => $value)
      {
        $sort_keys[$key] = $value['uriagemasterId'];
      }
      array_multisort($sort_keys, SORT_ASC, $arrFp);

      $tourokutest = array();
      for($i=0; $i<20; $i++){
        $tourokutest[] = $arrFp[$i];
      }

      echo "<pre>";
      print_r($tourokutest);
      echo "</pre>";

      $Uriagesyousais = $this->Uriagesyousais->patchEntities($this->Uriagesyousais->newEntity(), $tourokutest);
      $this->Uriagesyousais->saveMany($Uriagesyousais);

    }

    public function mastercsv()//http://localhost/CakePHPapp/shinkies/mastercsv
    {
      $fp = fopen("master220115.csv", "r");//csvファイルはwebrootに入れる
      $fpcount = fopen("master220115.csv", 'r' );
      for( $count = 0; fgets( $fpcount ); $count++ );
      $this->set('count',$count);

      $arrFp = array();//空の配列を作る
      $line = fgets($fp);//ファイル$fpの上の１行を取る（１行目）
      for ($k=1; $k<$count; $k++) {//行数分
        $line = fgets($fp);//ファイル$fpの上の１行を取る（２行目から）
        $sample = explode(',',$line);//$lineを","毎に配列に入れる

        $keys=array_keys($sample);
        $keys[array_search('0',$keys)]='uriagebi';
        $keys[array_search('1',$keys)]='denpyou_num';
        $keys[array_search('2',$keys)]='customerId';
        $keys[array_search('3',$keys)]='bunrui';

        $sample = array_combine( $keys, $sample );

        unset($sample['3']);
        unset($sample['4']);

        if($sample['denpyou_num'] > 1){

        }else{
          echo "<pre>";
          print_r($k);
          echo "</pre>";
        }

        $Customer = $this->Customers->find('all', ['conditions' => ['id' => $sample['customerId']]])->toArray();
        if(isset($Customer[0])){
          $sample = array_merge($sample,array('customer'=>$Customer[0]['name']));
          $sample = array_merge($sample,array('furigana'=>$Customer[0]['furigana']));
          $sample = array_merge($sample,array('yuubin'=>$Customer[0]['yuubin']));
          $sample = array_merge($sample,array('address'=>$Customer[0]['address']));
        }else{

          echo "<pre>";
          print_r($sample['customerId']);
          echo "</pre>";

        }
        $sample = array_merge($sample,array('id'=>$sample['denpyou_num']));
        $sample = array_merge($sample,array('seikyuuId'=>5));//220115=5
        $sample = array_merge($sample,array('delete_flag'=>0));
        $sample = array_merge($sample,array('created_at'=>date("Y-m-d 23:00:00")));

        $Uriagemasters = $this->Uriagemasters->find('all', ['conditions' => ['id' => $sample['denpyou_num']]])->toArray();
        if(!isset($Uriagemasters[0])){

          $Uriagemasters2 = $this->Uriagemasters->find('all',
          ['conditions' => ['uriagebi' => $sample['uriagebi'], 'customerId' => (int)$sample['customerId']]])->toArray();
          if(!isset($Uriagemasters2[0])){

            $arrFp[] = $sample;//配列に追加する

          }

        }

      }

      foreach($arrFp as $key => $value)
      {
        $sort_keys[$key] = $value['denpyou_num'];
      }
      array_multisort($sort_keys, SORT_ASC, $arrFp);

      echo "<pre>";
      print_r(count($arrFp));
      echo "<pre>";
      print_r($arrFp);
      echo "</pre>";

      //     $Uriagemasters = $this->Uriagemasters->patchEntities($this->Uriagemasters->newEntity(), $arrFp);
      //     $this->Uriagemasters->saveMany($Uriagemasters);

    }

    public function memoyou()
    {

      $Uriagemasterscustomer = $this->Uriagemasters->find()->where(['id' => $data['id']])->toArray();

      $Zandakas = $this->Zandakas->find('all', ['conditions' => ['customerId' => $Uriagemasterscustomer[0]["customerId"], 'delete_flag' => 0]])->toArray();
      if(isset($Zandakas[0])){

        $Uriagesyousais = $this->Uriagesyousais->find()
        ->where([ 'uriagemasterId' => $UriagemastersId, 'delete_flag' => 0])->toArray();

        $Uriagetotalmaster = 0;

        for($i=0; $i<count($Uriagesyousais); $i++){

          if(!empty($Uriagesyousais[$i]->price)){

            $Uriagetotalmaster = $Uriagetotalmaster + $Uriagesyousais[$i]->price;

          }

        }
        $total_price = $Uriagetotalmaster * 1.1;

        $zandaka = $Zandakas[0]->zandaka - $total_price;

        $this->Zandakas->updateAll(
          ['zandaka' => $zandaka, 'koushinbi' =>  date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
          ['id'  => $Zandakas[0]->id]
        );

      }

    }

  }
