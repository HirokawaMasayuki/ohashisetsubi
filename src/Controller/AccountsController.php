<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

class AccountsController extends AppController
{

			public function initialize()
			{
			parent::initialize();
			$this->Customers = TableRegistry::get('customers');
			$this->Uriages = TableRegistry::get('uriages');
		  }

		 public function index()
     {
     }

		 public function uriageformcustomer()
     {
			 $uriages = $this->Uriages->newEntity();
       $this->set('uriages',$uriages);

			 $arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0']])->order(['furigana' => 'ASC']);
			 $arrCustomer = array();
			 foreach ($arrCustomers as $value) {
				 $arrCustomer[] = array($value->name=>$value->name);
			 }
			 $this->set('arrCustomer',$arrCustomer);

     }

		 public function uriageformsyousai()
     {
			 $uriages = $this->Uriages->newEntity();
       $this->set('uriages',$uriages);

			 $data = $this->request->getData();
/*
			 echo "<pre>";
			 print_r($data);
			 echo "</pre>";
*/
			 if(!empty($data["name1"])){
				 $name = $data["name1"];
			 }elseif(!empty($data["name2"])){
				 $name = $data["name2"];
			 }else{
				 $name = "";
			 }

			 $Customer = $this->Customers->find('all', ['conditions' => ['name' => $name]])->toArray();
			 if(isset($Customer[0])){
				 $customercheck = 1;
				 $yuubin = $Customer[0]->yuubin;
				 $address = $Customer[0]->address;
				 $keisyou = $Customer[0]->keisyou;
				 if($keisyou == 1){
					 $keisyou = "様";
				 }elseif($keisyou == 2){
					 $keisyou = "御中";
				 }elseif($keisyou == 3){
					 $keisyou = "殿";
				 }else{
					 $keisyou = "";
				 }
			 }else{
				 $customercheck = 2;
			 }
			 $this->set('name',$name);
			 $this->set('yuubin',$yuubin);
			 $this->set('address',$address);
			 $this->set('keisyou',$keisyou);
			 $this->set('customercheck',$customercheck);
     }

		 public function uriagesyuturyokukakunin()
     {
			 $uriages = $this->Uriages->newEntity();
       $this->set('uriages',$uriages);

			 $data = $this->request->getData();

			 $this->set('name',$data["name"]);
			 $this->set('yuubin',$data["yuubin"]);
			 $this->set('address',$data["address"]);
			 $this->set('keisyou',$data["keisyou"]);

			 $month = (int)$data['date']['month'];
			 $day = (int)$data['date']['day'];
			 $dateexcl = $data['date']['year']."年".$month."月".$day."日";
			 $datetouroku = $data['date']['year']."-".$data['date']['month']."-".$data['date']['day'];
			 $this->set('dateexcl',$dateexcl);
			 $this->set('datetouroku',$datetouroku);
			 /*
			 echo "<pre>";
			 print_r($date);
			 echo "</pre>";
*/
			 for($i=1; $i<=8; $i++){

				 $this->set('pro_'.$i,$data["pro_".$i]);
				 $this->set('amount_'.$i,$data["amount_".$i]);
				 $this->set('tani_'.$i,$data["tani_".$i]);
				 $this->set('amount_'.$i,$data["amount_".$i]);
				 $this->set('tanka_'.$i,$data["tanka_".$i]);
				 $this->set('bik_'.$i,$data["bik_".$i]);

				 if((int)$data["tanka_".$i] > 0){
					 ${"price_".$i} = (int)$data["tanka_".$i] * (int)$data["amount_".$i];
				 }else{
					 ${"price_".$i} = "";
				 }
				 $this->set('price_'.$i,${"price_".$i});

			 }

     }

		 public function uriagesyuturyoku()
     {
			 $uriages = $this->Uriages->newEntity();
       $this->set('uriages',$uriages);

			 $data = $this->request->getData();
/*
			 echo "<pre>";
			 print_r($data);
			 echo "</pre>";
*/
			 $tourokuArr = array();

			 $tourokuArr = array('customer' => $data["name"],'yuubin' => $data["yuubin"],'address' => $data["address"],'keisyou' => $data["keisyou"]
			 												,'syutsuryokubi' => $data["datetouroku"],'delete_flag' => 0,'created_at' => date('Y-m-d H:i:s', strtotime('+9hour')));

			 for($i=1; $i<=8; $i++){

				 ${"arr_".$i} = array('pro_'.$i => $data["pro_".$i],'amount_'.$i => $data["amount_".$i],'tani_'.$i => $data["tani_".$i],'tanka_'.$i => $data["tanka_".$i],
															 'price_'.$i => $data["price_".$i],'bik_'.$i => $data["bik_".$i]);

				 $tourokuArr = array_merge($tourokuArr,${"arr_".$i});

			 }
/*
			 echo "<pre>";
			 print_r($tourokuArr);
			 echo "</pre>";
*/
			 //エクセル出力
			 $filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\納品書.xlsx'; //読み込みたいファイルの指定
			 $reader = new XlsxReader();
			 $spreadsheet = $reader->load($filepath);
			 $sheet = $spreadsheet->getActiveSheet();
			 $sheet->setCellValue('A2', "〒 ".$data["yuubin"]);
			 $sheet->setCellValue('A3', $data["address"]);
			 $sheet->setCellValue('A4', $data["name"]);
			 $sheet->setCellValue('E4', $data["keisyou"]);
			 $sheet->setCellValue('G2', $data["dateexcl"]);

			 $pro_check = 0;
			 for($i=1; $i<=8; $i++){

				 $num = 11 + $i;
				 $num2 = 34 + $i;
				 if(empty($data["pro_".$i]) && $pro_check == 0){
					 ${"pro_".$i} = "以下余白";
					 $pro_check = 1;
				 }else{
					 ${"pro_".$i} = $data["pro_".$i];
				 }

				 $sheet->setCellValue("A".$num, ${"pro_".$i});
				 $sheet->setCellValue("A".$num2, ${"pro_".$i});
				 $sheet->setCellValue("E".$num, $data["amount_".$i]);
				 $sheet->setCellValue("E".$num2, $data["amount_".$i]);
				 $sheet->setCellValue("F".$num, $data["tani_".$i]);
				 $sheet->setCellValue("F".$num2, $data["tani_".$i]);
				 $sheet->setCellValue("G".$num, $data["tanka_".$i]);
				 $sheet->setCellValue("G".$num2, $data["tanka_".$i]);
				 $sheet->setCellValue("H".$num, $data["price_".$i]);
				 $sheet->setCellValue("H".$num2, $data["price_".$i]);
				 $sheet->setCellValue("I".$num, $data["bik_".$i]);
				 $sheet->setCellValue("I".$num2, $data["bik_".$i]);

		 }

			 $writer = new XlsxWriter($spreadsheet);

			 $datetime = date('H時i分出力', strtotime('+9hour'));
			 $year = date('Y');
			 $month = date('m');
			 $day = date('d');

			 if(is_dir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/$year/$month/$day")){//ディレクトリが存在すればOK

				 $file_name = $data["name"]."_".$datetime.".xlsx";
				 $outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/$year/$month/$day/$file_name"; //出力したいファイルの指定

			 }else{//ディレクトリが存在しなければ作成する

				 mkdir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/$year/$month/$day", 0777, true);
				 $file_name = $data["name"]."_".$datetime.".xlsx";
				 $outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/$year/$month/$day/$file_name"; //出力したいファイルの指定

			 }

			 $mesxlsx = "「エクセル出力/".$year."/".$month."/".$day."」フォルダにエクセルシート「".$file_name."」が出力されました。";
			 $this->set('mesxlsx',$mesxlsx);

			 $writer->save($outfilepath);

 			//データベース登録
			 $uriage = $this->Uriages->patchEntity($uriages, $tourokuArr);
       $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Uriages->save($uriage)) {

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

		 public function uriagekensakuform()//売上照会
     {
			 $uriages = $this->Uriages->newEntity();
       $this->set('uriages',$uriages);
     }

     public function uriagekensakuview()//売上照会
     {
			 $uriages = $this->Uriages->newEntity();
       $this->set('uriages',$uriages);
       $data = $this->request->getData();
/*
       echo "<pre>";
       print_r($data);
       echo "</pre>";
*/
       $date_sta = $data['date_sta']['year']."-".$data['date_sta']['month']."-".$data['date_sta']['day'];
       $date_fin = $data['date_fin']['year']."-".$data['date_fin']['month']."-".$data['date_fin']['day'];

       $customer = $data['customer'];
       $proname = $data['proname'];

       $date_fin = strtotime($date_fin);
       $date_fin = date('Y-m-d', strtotime('+1 day', $date_fin));

       if(empty($data['customer'])){//product_codeの入力がないとき
         $product_code = "no";
         if(empty($data['proname'])){//seikeikiの入力がないとき
           $seikeiki = "no";//日にちだけで絞り込み

           $Uriages = $this->Uriages->find()
           ->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"]);
					 $this->set('Uriages',$Uriages);

         }else{//pronameの入力があるとき pronameと日にちで絞り込み

           $Uriages = $this->Uriages->find()
          ->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'delete_flag' => 0,
					'OR' => [['pro_1' => $proname], ['pro_2' => $proname], ['pro_3' => $proname], ['pro_4' => $proname],
					['pro_5' => $proname], ['pro_6' => $proname], ['pro_7' => $proname], ['pro_8' => $proname]]])
					->order(["syutsuryokubi"=>"ASC"]);
					$this->set('Uriages',$Uriages);

         }
       }else{//customerの入力があるとき
         if(empty($data['proname'])){//pronameの入力がないとき

           $Uriages = $this->Uriages->find()
           ->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin,  'customer' => $customer, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"]);
					 $this->set('Uriages',$Uriages);

         }else{//pronameの入力があるときpronameとcustomerと日にちで絞り込み

					 $Uriages = $this->Uriages->find()
          ->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'customer' => $customer, 'delete_flag' => 0,
					'OR' => [['pro_1 like' => '%'.$proname.'%'], ['pro_2 like' => '%'.$proname.'%'], ['pro_3 like' => '%'.$proname.'%'], ['pro_4 like' => '%'.$proname.'%'],
					['pro_5 like' => '%'.$proname.'%'], ['pro_6 like' => '%'.$proname.'%'], ['pro_7 like' => '%'.$proname.'%'], ['pro_8 like' => '%'.$proname.'%']]])
					->order(["syutsuryokubi"=>"ASC"]);
					$this->set('Uriages',$Uriages);

         }

       }

     }

		     public function uriagekensakusyousai()
		     {
					 $uriages = $this->Uriages->newEntity();
		       $this->set('uriages',$uriages);

		       $data = $this->request->getData();

		       $data = array_keys($data, '詳細');

		       $Uriages = $this->Uriages->find()->where(['id' => $data[0]])->toArray();

		       $syutsuryokubi = $Uriages[0]["syutsuryokubi"]->format('Y年m月d日');
					 $this->set('syutsuryokubi',$syutsuryokubi);
					 $customer = $Uriages[0]["customer"];
		       $this->set('customer',$customer);
					 $yuubin = $Uriages[0]["yuubin"];
		       $this->set('yuubin',$yuubin);
					 $address = $Uriages[0]["address"];
		       $this->set('address',$address);
					 $keisyou = $Uriages[0]["keisyou"];
		       $this->set('keisyou',$keisyou);

					 for($i=1; $i<=8; $i++){

						 ${"pro_".$i} = $Uriages[0]["pro_".$i];
			       $this->set("pro_".$i,${"pro_".$i});
						 ${"amount_".$i} = $Uriages[0]["amount_".$i];
			       $this->set("amount_".$i,${"amount_".$i});
						 ${"tani_".$i} = $Uriages[0]["tani_".$i];
			       $this->set("tani_".$i,${"tani_".$i});
						 ${"tanka_".$i} = $Uriages[0]["tanka_".$i];
			       $this->set("tanka_".$i,${"tanka_".$i});
						 ${"price_".$i} = $Uriages[0]["price_".$i];
			       $this->set("price_".$i,${"price_".$i});
						 ${"bik_".$i} = $Uriages[0]["bik_".$i];
			       $this->set("bik_".$i,${"bik_".$i});

					 }


		     }

		 public function test()//エクセル複数作成のテスト
     {
			 $uriages = $this->Uriages->newEntity();
       $this->set('uriages',$uriages);

				$filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\test_x.xlsx'; //読み込みたいファイルの指定
				$filepath1 = 'C:\xampp\htdocs\CakePHPapp\webroot\test.txt'; //読み込みたいファイルの指定

				// ファイルが存在するかチェックする
				if (file_exists($filepath1)) {

				  // ファイルが存在したら、ファイル名を付けて存在していると表示
				  echo 'ファイルは存在します。';

				} else {

				  // ファイルが存在していなかったら、見つからないと表示
				  echo 'ファイルが見つかりません！';
				}

/*
//①エクセルシート自体を複数作成する

				for($i=1; $i<=3; $i++){

					$reader = new XlsxReader();
					$spreadsheet = $reader->load($filepath);
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', $i);
					$sheet->setCellValue('A2', 'yyy');

					$writer = new XlsxWriter($spreadsheet);

					$datetime = date('Ymd', strtotime('+9hour'));

					${"file_name".$i} = "test_".$datetime."_".$i.".xlsx";

					$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/${"file_name".$i}"; //出力したいファイルの指定

					$writer->save($outfilepath);
				}


//②エクセルシートは１つで、その中のシートを複数作成する

				$reader = new XlsxReader();
				$spreadsheet = $reader->load($filepath);

				for($i=2; $i<=4; $i++){//Sheet1はすでに存在するので、$i=2からスタート

					$baseSheet = $spreadsheet->getSheet(0);
					$newSheet = $baseSheet->copy();
					$newSheet->setTitle( "Sheet".$i );
					$spreadsheet->addSheet( $newSheet );

					$writer = new XlsxWriter($spreadsheet);

				}

				$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/copytest.xlsx"; //出力したいファイルの指定

				$writer->save($outfilepath);
*/

/*
				$reader = new XlsxReader();
				$spreadsheet = $reader->load($filepath);
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->setCellValue('A1', 1);
				$sheet->setCellValue('A2', 'yyy');

				$writer = new XlsxWriter($spreadsheet);

				$datetime = date('Ymd', strtotime('+9hour'));

				$file_name = "test_".$datetime."_.xlsx";

				$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/$file_name"; //出力したいファイルの指定

				$writer->save($outfilepath);
*/
     }

}
