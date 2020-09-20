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

			 $dateexcl = $data['date']['year']."年".$data['date']['month']."月".$data['date']['day']."日";
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

			 echo "<pre>";
			 print_r($tourokuArr);
			 echo "</pre>";

			 $filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\test_x.xlsx'; //読み込みたいファイルの指定
			 $reader = new XlsxReader();
			 $spreadsheet = $reader->load($filepath);
			 $sheet = $spreadsheet->getActiveSheet();
			 $sheet->setCellValue('A1', 1234);
			 $sheet->setCellValue('A2', '5432');

			 $writer = new XlsxWriter($spreadsheet);

			 $datetime = date('H時i分出力', strtotime('+9hour'));

			 $year = date('Y');
			 $month = date('m');
			 $day = date('d');

			 if(is_dir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/$year/$month/$day")){//ファイルがディレクトリかどうかを調べる
				 $i = 1;
			 }else{
				 mkdir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/$year/$month/$day", 0777, true);
			 }

			 $file_name = $data["name"]."_".$datetime.".xlsx";

			 $outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/$year/$month/$day/$file_name"; //出力したいファイルの指定

			 $writer->save($outfilepath);


/*
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
*/
     }

		 public function test()
     {
			 $tests = $this->Tests->newEntity();
       $this->set('tests',$tests);

				$filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\test_x.xlsx'; //読み込みたいファイルの指定
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
*/


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

     }

}
