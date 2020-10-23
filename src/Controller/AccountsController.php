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
			$this->Nyuukins = TableRegistry::get('nyuukins');
			$this->Seikyuus = TableRegistry::get('seikyuus');
			$this->Zandakas = TableRegistry::get('zandakas');
			$this->Miseikyuus = TableRegistry::get('miseikyuus');
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
				 $arrCustomer[] = array($value->id=>$value->name.' '.$value->siten);
			 }
			 $this->set('arrCustomer',$arrCustomer);

     }

		 public function uriageformcustomerfurigana()
     {
			 $uriages = $this->Uriages->newEntity();
       $this->set('uriages',$uriages);

			 $Data = $this->request->query('s');
       $data = $Data['data'];
/*
			 echo "<pre>";
			 print_r($data);
			 echo "</pre>";
*/
			 $furigana = $data["nyuryokufurigana"];

			 $arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0', 'furigana like' => '%'.$furigana.'%']])->order(['furigana' => 'ASC']);
			 $arrCustomer = array();
			 foreach ($arrCustomers as $value) {
				 $arrCustomer[] = array($value->id=>$value->name.' '.$value->siten);
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
			 if(!empty($data["nyuryokufurigana"])){

				 return $this->redirect(['action' => 'uriageformcustomerfurigana',
				 's' => ['data' => $data]]);

			 }

			 $name = "";

			 if(!empty($data["name1"])){
				 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["name1"]]])->toArray();
				 $name = $Customer[0]->name;
				 $siten = $Customer[0]->siten;
				 $namehyouji = $name." ".$siten;
				 $this->set('namehyouji',$namehyouji);
				 $this->set('id',$data["name1"]);
			 }elseif(!empty($data["name2"])){
				 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["name2"]]])->toArray();
				 $name = $Customer[0]->name;
				 $siten = $Customer[0]->siten;
				 $namehyouji = $name." ".$siten;
				 $this->set('namehyouji',$namehyouji);
				 $this->set('id',$data["name2"]);
			 }else{
				 $name = "";
			 }

			 $yuubin = "";
			 $address = "";
			 $keisyou = "";
			 $furigana = "";
			 $customercheck = 1;


			 if(isset($data["id"]) || isset($Customer[0])){
				 if(isset($data["id"])){
					 $this->set('id',$data["id"]);
					 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
				 }
				 $yuubin = $Customer[0]->yuubin;
				 $address = $Customer[0]->address;
				 $keisyou = $Customer[0]->keisyou;
				 $furigana = $Customer[0]->furigana;
				 $name = $Customer[0]->name;
				 $siten = $Customer[0]->siten;
				 $namehyouji = $name." ".$siten;
				 if($keisyou == 1){
					 $keisyou = "様";
				 }elseif($keisyou == 2){
					 $keisyou = "御中";
				 }elseif($keisyou == 3){
					 $keisyou = "殿";
				 }else{
					 $keisyou = "";
				 }
			 }elseif(isset($data['next'])){
				 $customercheck = 2;
			 }

			 $this->set('namehyouji',$namehyouji);

			 $this->set('yuubin',$yuubin);
			 $this->set('address',$address);
			 $this->set('keisyou',$keisyou);
			 $this->set('furigana',$furigana);
			 $this->set('customercheck',$customercheck);


			 if(isset($data['tuika'])){

				 if($data['num'] >= 20){

					 $tuika = $data['num'] ;
	         $this->set('tuika',$tuika);

					 echo "<pre>";
					 print_r("20行以上の登録は同時にできません。２回に分けて登録してください。");
					 echo "</pre>";

				 }else{

					 $tuika = $data['num'] + 1;
					 $this->set('tuika',$tuika);

				 }

       }elseif(isset($data['confirm'])){

         $tuika = $data['num'];
         $this->set('tuika',$tuika);

         return $this->redirect(['action' => 'uriagesyuturyokukakunin',
         's' => ['data' => $data]]);//登録するデータを全部配列に入れておく

				}else{
					$tuika = 0;
					$this->set('tuika',$tuika);
				}

     }

		 public function uriagesyuturyokukakunin()
     {
			 $uriages = $this->Uriages->newEntity();
       $this->set('uriages',$uriages);

	//		 $data = $this->request->getData();

			 $Data = $this->request->query('s');
       $data = $Data['data'];

			 $this->set('id',$data["id"]);
			 $this->set('name',$data["name"]);
			 $this->set('furigana',$data["furigana"]);
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
			 print_r($Data['data']);
			 echo "</pre>";
*/
			 for($i=1; $i<=$data["num"]; $i++){

				 if(!empty($data["pro_".$i])){
					 $this->set('tuika',$i);

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

     }

		 public function uriagesyuturyoku()
     {
			 $uriages = $this->Uriages->newEntity();
       $this->set('uriages',$uriages);

			 $data = $this->request->getData();

			 $Uriages = $this->Uriages->find()->where(['delete_flag' => 0])->order(["denpyou_num"=>"desc"])->toArray();
			 if(isset($Uriages[0])){
				 $denpyou_num = $Uriages[0]->denpyou_num + 1;
			 }else{
				 $denpyou_num = 10000;
			 }

			 $tourokuArr = array();

			 $tourokuArr = array('denpyou_num' => $denpyou_num,'customerId' => $data["id"],'customer' => $data["name"],'furigana' => $data["furigana"],'yuubin' => $data["yuubin"],'address' => $data["address"],'keisyou' => $data["keisyou"]
			 												,'syutsuryokubi' => $data["datetouroku"],'delete_flag' => 0,'created_at' => date('Y-m-d H:i:s', strtotime('+9hour')));

			 $total_price = 0;
			 for($i=1; $i<=$data["tuika"]; $i++){

				 ${"arr_".$i} = array('pro_'.$i => $data["pro_".$i],'amount_'.$i => $data["amount_".$i],'tani_'.$i => $data["tani_".$i],'tanka_'.$i => $data["tanka_".$i],
															 'price_'.$i => $data["price_".$i],'bik_'.$i => $data["bik_".$i]);

				 $tourokuArr = array_merge($tourokuArr,${"arr_".$i});

				 $total_price = $total_price + $data["price_".$i];

			 }

			 $total_price = $total_price * 1.1;

			 $amari = $data["tuika"] % 8;
			 $syou = floor($data["tuika"]/8);

			 //エクセル出力
			 $filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\納品書.xlsx'; //読み込みたいファイルの指定
			 $reader = new XlsxReader();
			 $spreadsheet = $reader->load($filepath);
//			 $sheet = $spreadsheet->getActiveSheet();
				$sheet = $spreadsheet->getSheetByName("Sheet1");
				$sheet->setCellValue('A2', "〒 ".$data["yuubin"]);
				$sheet->setCellValue('A3', $data["address"]);
				$sheet->setCellValue('A4', $data["name"]);
				$sheet->setCellValue('E4', $data["keisyou"]);
				$sheet->setCellValue('G2', $data["dateexcl"]);
				$sheet->setCellValue('C8', $total_price);

				for($j=2; $j<2+$syou; $j++){

					$baseSheet = $spreadsheet->getSheet(0);
	 			 $newSheet = $baseSheet->copy();
	 			 $newSheet->setTitle( "Sheet".$j );
	 			 $spreadsheet->addSheet( $newSheet );

	 			 $writer = new XlsxWriter($spreadsheet);

				}

			 $pro_check = 0;

			 if($amari == 0){//以下余白がいらない場合

				 for($i=1; $i<=8; $i++){

					 if($i == $data["tuika"]+2){
						 break;
					 }

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

		 }else{//以下余白がいる場合

				 for($i=1; $i<=8; $i++){

					 if($i == $data["tuika"]+2){
						 break;
					 }

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

					 if($i < $data["tuika"]+1){

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

			 }

		 }

		 $writer = new XlsxWriter($spreadsheet);

		 for($j=2; $j<2+$syou; $j++){

			 $sheet = $spreadsheet->getSheetByName("Sheet".$j);
			 $sheet->setCellValue('A2', "〒 ".$data["yuubin"]);
			 $sheet->setCellValue('A3', $data["address"]);
			 $sheet->setCellValue('A4', $data["name"]);
			 $sheet->setCellValue('E4', $data["keisyou"]);
			 $sheet->setCellValue('G2', $data["dateexcl"]);

			 for($i=8*($j - 1)+1; $i<=8*$j; $i++){

				 if($i == $data["tuika"]+2){
					 break;
				 }

				 $num = 11 + $i - 8*($j - 1);
				 $num2 = 34 + $i - 8*($j - 1);
				 if(empty($data["pro_".$i]) && $pro_check == 0){
					 ${"pro_".$i} = "以下余白";
					 $pro_check = 1;
				 }else{
					 ${"pro_".$i} = $data["pro_".$i];
				 }

				 $sheet->setCellValue("A".$num, ${"pro_".$i});
				 $sheet->setCellValue("A".$num2, ${"pro_".$i});

				 if($i < $data["tuika"]+1){

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

			 }

		 }

			 $datetime = date('H時i分s秒出力', strtotime('+9hour'));
			 $year = date('Y', strtotime('+9hour'));
			 $month = date('m', strtotime('+9hour'));
			 $day = date('d', strtotime('+9hour'));

			 if(is_dir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/納品書/$year/$month/$day")){//ディレクトリが存在すればOK

				 $file_name = $data["name"]."_".$datetime.".xlsx";
				 $outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/納品書/$year/$month/$day/$file_name"; //出力したいファイルの指定

			 }else{//ディレクトリが存在しなければ作成する

				 mkdir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/納品書/$year/$month/$day", 0777, true);
				 $file_name = $data["name"]."_".$datetime.".xlsx";
				 $outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/納品書/$year/$month/$day/$file_name"; //出力したいファイルの指定

			 }

			 $mesxlsx = "「エクセル出力/納品書/".$year."/".$month."/".$day."」フォルダにエクセルシート「".$file_name."」が出力されました。";
			 $this->set('mesxlsx',$mesxlsx);

			 $writer->save($outfilepath);
/*
			 echo "<pre>";
			 print_r($tourokuArr);
			 echo "</pre>";
*/
 			//データベース登録
			 $uriage = $this->Uriages->patchEntity($uriages, $tourokuArr);
       $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Uriages->save($uriage)) {

					 $Miseikyuus = $this->Miseikyuus->find('all', ['conditions' => ['customerId' => $data["id"], 'delete_flag' => 0]])->toArray();
					 if(isset($Miseikyuus[0])){

						 $miseikyuugaku = $Miseikyuus[0]->miseikyuugaku + $total_price;

						 $this->Miseikyuus->updateAll(
							 ['miseikyuugaku' => $miseikyuugaku, 'kousinbi' => date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
							 ['id'  => $Miseikyuus[0]->id]
						 );

					 }else{

						 $arrMiseikyuu = array('customerId' => $data["id"], 'furigana' => $data["furigana"], 'miseikyuugaku' => $total_price, 'kousinbi' => date('Y-m-d', strtotime('+9hour')),
						'delete_flag' => 0, 'created_at' => date('Y-m-d H:i:s', strtotime('+9hour')));

						 $Miseikyuu = $this->Miseikyuus->patchEntity($this->Miseikyuus->newEntity(), $arrMiseikyuu);
						 $this->Miseikyuus->save($Miseikyuu);

					 }

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
			 $furigana = $data['furigana'];
			 $denpyou_num = $data['denpyou_num'];

       $date_fin = strtotime($date_fin);
       $date_fin = date('Y-m-d', strtotime('+1 day', $date_fin));

					 if(empty($data['denpyou_num'])){//denpyou_numの入力がないとき

						 if(empty($data['furigana'])){//furiganaの入力がないとき

							 if(empty($data['customer'])){//customerの入力がないとき

								 if(empty($data['proname'])){//pronameの入力がないとき

									 $Uriages = $this->Uriages->find()
									 ->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
									 $this->set('Uriages',$Uriages);

								 }else{//pronameの入力があるとき pronameと日にちで絞り込み

									 $Uriages = $this->Uriages->find()
									->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'delete_flag' => 0,
									'OR' => [['pro_1 like' => '%'.$proname.'%'], ['pro_2' => '%'.$proname.'%'], ['pro_3' => '%'.$proname.'%'], ['pro_4 like' => '%'.$proname.'%'],
									['pro_5 like' => '%'.$proname.'%'], ['pro_6 like' => '%'.$proname.'%'], ['pro_7 like' => '%'.$proname.'%'], ['pro_8 like' => '%'.$proname.'%'],
									['pro_9 like' => '%'.$proname.'%'], ['pro_10 like' => '%'.$proname.'%'], ['pro_11 like' => '%'.$proname.'%'], ['pro_12 like' => '%'.$proname.'%'],
									['pro_13 like' => '%'.$proname.'%'], ['pro_14 like' => '%'.$proname.'%'], ['pro_15 like' => '%'.$proname.'%'], ['pro_16 like' => '%'.$proname.'%'],
									['pro_17 like' => '%'.$proname.'%'], ['pro_18 like' => '%'.$proname.'%'], ['pro_19 like' => '%'.$proname.'%'], ['pro_20 like' => '%'.$proname.'%']]])
									->order(["syutsuryokubi"=>"ASC"])->toArray();
									$this->set('Uriages',$Uriages);

								}

							}else{//customerの入力があるとき

								if(empty($data['proname'])){//pronameの入力がないとき

									$Uriages = $this->Uriages->find()
									->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin,  'customer like' => '%'.$customer.'%', 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
									$this->set('Uriages',$Uriages);

								}else{//pronameの入力があるときpronameとcustomerと日にちで絞り込み

									$Uriages = $this->Uriages->find()
								 ->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'customer like' => '%'.$customer.'%', 'delete_flag' => 0,
								 'OR' => [['pro_1 like' => '%'.$proname.'%'], ['pro_2 like' => '%'.$proname.'%'], ['pro_3 like' => '%'.$proname.'%'], ['pro_4 like' => '%'.$proname.'%'],
								 ['pro_5 like' => '%'.$proname.'%'], ['pro_6 like' => '%'.$proname.'%'], ['pro_7 like' => '%'.$proname.'%'], ['pro_8 like' => '%'.$proname.'%'],
								 ['pro_9 like' => '%'.$proname.'%'], ['pro_10 like' => '%'.$proname.'%'], ['pro_11 like' => '%'.$proname.'%'], ['pro_12 like' => '%'.$proname.'%'],
								 ['pro_13 like' => '%'.$proname.'%'], ['pro_14 like' => '%'.$proname.'%'], ['pro_15 like' => '%'.$proname.'%'], ['pro_16 like' => '%'.$proname.'%'],
								 ['pro_17 like' => '%'.$proname.'%'], ['pro_18 like' => '%'.$proname.'%'], ['pro_19 like' => '%'.$proname.'%'], ['pro_20 like' => '%'.$proname.'%']]])
								 ->order(["syutsuryokubi"=>"ASC"])->toArray();
								 $this->set('Uriages',$Uriages);

							 }

						 }

					 }else{

						 if(empty($data['customer'])){//customerの入力がないとき

							 if(empty($data['proname'])){//pronameの入力がないとき

								 $Uriages = $this->Uriages->find()
								 ->where(['furigana like' => '%'.$furigana.'%', 'syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
								 $this->set('Uriages',$Uriages);

							 }else{//pronameの入力があるとき pronameと日にちで絞り込み

								 $Uriages = $this->Uriages->find()
								->where(['furigana like' => '%'.$furigana.'%', 'syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'delete_flag' => 0,
								'OR' => [['pro_1 like' => '%'.$proname.'%'], ['pro_2 like' => '%'.$proname.'%'], ['pro_3 like' => '%'.$proname.'%'], ['pro_4 like' => '%'.$proname.'%'],
								['pro_5 like' => '%'.$proname.'%'], ['pro_6 like' => '%'.$proname.'%'], ['pro_7 like' => '%'.$proname.'%'], ['pro_8 like' => '%'.$proname.'%'],
								['pro_9 like' => '%'.$proname.'%'], ['pro_10 like' => '%'.$proname.'%'], ['pro_11 like' => '%'.$proname.'%'], ['pro_12 like' => '%'.$proname.'%'],
								['pro_13 like' => '%'.$proname.'%'], ['pro_14 like' => '%'.$proname.'%'], ['pro_15 like' => '%'.$proname.'%'], ['pro_16 like' => '%'.$proname.'%'],
								['pro_17 like' => '%'.$proname.'%'], ['pro_18 like' => '%'.$proname.'%'], ['pro_19 like' => '%'.$proname.'%'], ['pro_20 like' => '%'.$proname.'%']]])
								->order(["syutsuryokubi"=>"ASC"])->toArray();
								$this->set('Uriages',$Uriages);

							}

						}else{//customerの入力があるとき

							if(empty($data['proname'])){//pronameの入力がないとき

								$Uriages = $this->Uriages->find()
								->where(['furigana like' => '%'.$furigana.'%', 'syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin,  'customer like' => '%'.$customer.'%', 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
								$this->set('Uriages',$Uriages);

							}else{//pronameの入力があるときpronameとcustomerと日にちで絞り込み

								$Uriages = $this->Uriages->find()
							 ->where(['furigana like' => '%'.$furigana.'%', 'syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'customer like' => '%'.$customer.'%', 'delete_flag' => 0,
							 'OR' => [['pro_1 like' => '%'.$proname.'%'], ['pro_2 like' => '%'.$proname.'%'], ['pro_3 like' => '%'.$proname.'%'], ['pro_4 like' => '%'.$proname.'%'],
							 ['pro_5 like' => '%'.$proname.'%'], ['pro_6 like' => '%'.$proname.'%'], ['pro_7 like' => '%'.$proname.'%'], ['pro_8 like' => '%'.$proname.'%'],
							 ['pro_9 like' => '%'.$proname.'%'], ['pro_10 like' => '%'.$proname.'%'], ['pro_11 like' => '%'.$proname.'%'], ['pro_12 like' => '%'.$proname.'%'],
							 ['pro_13 like' => '%'.$proname.'%'], ['pro_14 like' => '%'.$proname.'%'], ['pro_15 like' => '%'.$proname.'%'], ['pro_16 like' => '%'.$proname.'%'],
							 ['pro_17 like' => '%'.$proname.'%'], ['pro_18 like' => '%'.$proname.'%'], ['pro_19 like' => '%'.$proname.'%'], ['pro_20 like' => '%'.$proname.'%']]])
							 ->order(["syutsuryokubi"=>"ASC"])->toArray();
							 $this->set('Uriages',$Uriages);

						 }

					 }

				 }

			 }else{

						 if(empty($data['furigana'])){//furiganaの入力がないとき

							 if(empty($data['customer'])){//customerの入力がないとき

								 if(empty($data['proname'])){//pronameの入力がないとき

									 $Uriages = $this->Uriages->find()
									 ->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'denpyou_num' => $denpyou_num, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
									 $this->set('Uriages',$Uriages);

								 }else{//pronameの入力があるとき pronameと日にちで絞り込み

									 $Uriages = $this->Uriages->find()
									->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'denpyou_num' => $denpyou_num, 'delete_flag' => 0,
									'OR' => [['pro_1 like' => '%'.$proname.'%'], ['pro_2' => '%'.$proname.'%'], ['pro_3' => '%'.$proname.'%'], ['pro_4 like' => '%'.$proname.'%'],
									['pro_5 like' => '%'.$proname.'%'], ['pro_6 like' => '%'.$proname.'%'], ['pro_7 like' => '%'.$proname.'%'], ['pro_8 like' => '%'.$proname.'%'],
									['pro_9 like' => '%'.$proname.'%'], ['pro_10 like' => '%'.$proname.'%'], ['pro_11 like' => '%'.$proname.'%'], ['pro_12 like' => '%'.$proname.'%'],
									['pro_13 like' => '%'.$proname.'%'], ['pro_14 like' => '%'.$proname.'%'], ['pro_15 like' => '%'.$proname.'%'], ['pro_16 like' => '%'.$proname.'%'],
									['pro_17 like' => '%'.$proname.'%'], ['pro_18 like' => '%'.$proname.'%'], ['pro_19 like' => '%'.$proname.'%'], ['pro_20 like' => '%'.$proname.'%']]])
									->order(["syutsuryokubi"=>"ASC"])->toArray();
									$this->set('Uriages',$Uriages);

								}

							}else{//customerの入力があるとき

								if(empty($data['proname'])){//pronameの入力がないとき

									$Uriages = $this->Uriages->find()
									->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin,  'customer like' => '%'.$customer.'%', 'denpyou_num' => $denpyou_num, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
									$this->set('Uriages',$Uriages);

								}else{//pronameの入力があるときpronameとcustomerと日にちで絞り込み

									$Uriages = $this->Uriages->find()
								 ->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'customer like' => '%'.$customer.'%', 'denpyou_num' => $denpyou_num, 'delete_flag' => 0,
								 'OR' => [['pro_1 like' => '%'.$proname.'%'], ['pro_2 like' => '%'.$proname.'%'], ['pro_3 like' => '%'.$proname.'%'], ['pro_4 like' => '%'.$proname.'%'],
								 ['pro_5 like' => '%'.$proname.'%'], ['pro_6 like' => '%'.$proname.'%'], ['pro_7 like' => '%'.$proname.'%'], ['pro_8 like' => '%'.$proname.'%'],
								 ['pro_9 like' => '%'.$proname.'%'], ['pro_10 like' => '%'.$proname.'%'], ['pro_11 like' => '%'.$proname.'%'], ['pro_12 like' => '%'.$proname.'%'],
								 ['pro_13 like' => '%'.$proname.'%'], ['pro_14 like' => '%'.$proname.'%'], ['pro_15 like' => '%'.$proname.'%'], ['pro_16 like' => '%'.$proname.'%'],
								 ['pro_17 like' => '%'.$proname.'%'], ['pro_18 like' => '%'.$proname.'%'], ['pro_19 like' => '%'.$proname.'%'], ['pro_20 like' => '%'.$proname.'%']]])
								 ->order(["syutsuryokubi"=>"ASC"])->toArray();
								 $this->set('Uriages',$Uriages);

							 }

						 }

					 }else{

						 if(empty($data['customer'])){//customerの入力がないとき

							 if(empty($data['proname'])){//pronameの入力がないとき

								 $Uriages = $this->Uriages->find()
								 ->where(['furigana like' => '%'.$furigana.'%', 'syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'denpyou_num' => $denpyou_num, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
								 $this->set('Uriages',$Uriages);

							 }else{//pronameの入力があるとき pronameと日にちで絞り込み

								 $Uriages = $this->Uriages->find()
								->where(['furigana like' => '%'.$furigana.'%', 'syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'denpyou_num' => $denpyou_num, 'delete_flag' => 0,
								'OR' => [['pro_1 like' => '%'.$proname.'%'], ['pro_2 like' => '%'.$proname.'%'], ['pro_3 like' => '%'.$proname.'%'], ['pro_4 like' => '%'.$proname.'%'],
								['pro_5 like' => '%'.$proname.'%'], ['pro_6 like' => '%'.$proname.'%'], ['pro_7 like' => '%'.$proname.'%'], ['pro_8 like' => '%'.$proname.'%'],
								['pro_9 like' => '%'.$proname.'%'], ['pro_10 like' => '%'.$proname.'%'], ['pro_11 like' => '%'.$proname.'%'], ['pro_12 like' => '%'.$proname.'%'],
								['pro_13 like' => '%'.$proname.'%'], ['pro_14 like' => '%'.$proname.'%'], ['pro_15 like' => '%'.$proname.'%'], ['pro_16 like' => '%'.$proname.'%'],
								['pro_17 like' => '%'.$proname.'%'], ['pro_18 like' => '%'.$proname.'%'], ['pro_19 like' => '%'.$proname.'%'], ['pro_20 like' => '%'.$proname.'%']]])
								->order(["syutsuryokubi"=>"ASC"])->toArray();
								$this->set('Uriages',$Uriages);

							}

						}else{//customerの入力があるとき

							if(empty($data['proname'])){//pronameの入力がないとき

								$Uriages = $this->Uriages->find()
								->where(['furigana like' => '%'.$furigana.'%', 'syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin,  'customer like' => '%'.$customer.'%', 'denpyou_num' => $denpyou_num, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
								$this->set('Uriages',$Uriages);

							}else{//pronameの入力があるときpronameとcustomerと日にちで絞り込み

								$Uriages = $this->Uriages->find()
							 ->where(['furigana like' => '%'.$furigana.'%', 'syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'customer like' => '%'.$customer.'%', 'denpyou_num' => $denpyou_num, 'delete_flag' => 0,
							 'OR' => [['pro_1 like' => '%'.$proname.'%'], ['pro_2 like' => '%'.$proname.'%'], ['pro_3 like' => '%'.$proname.'%'], ['pro_4 like' => '%'.$proname.'%'],
							 ['pro_5 like' => '%'.$proname.'%'], ['pro_6 like' => '%'.$proname.'%'], ['pro_7 like' => '%'.$proname.'%'], ['pro_8 like' => '%'.$proname.'%'],
							 ['pro_9 like' => '%'.$proname.'%'], ['pro_10 like' => '%'.$proname.'%'], ['pro_11 like' => '%'.$proname.'%'], ['pro_12 like' => '%'.$proname.'%'],
							 ['pro_13 like' => '%'.$proname.'%'], ['pro_14 like' => '%'.$proname.'%'], ['pro_15 like' => '%'.$proname.'%'], ['pro_16 like' => '%'.$proname.'%'],
							 ['pro_17 like' => '%'.$proname.'%'], ['pro_18 like' => '%'.$proname.'%'], ['pro_19 like' => '%'.$proname.'%'], ['pro_20 like' => '%'.$proname.'%']]])
							 ->order(["syutsuryokubi"=>"ASC"])->toArray();
							 $this->set('Uriages',$Uriages);

						 }

					 }

				 }

			 }

			 $count = count($Uriages);
			 $Uriagetotalhyouji = 0;

			 for($j=0; $j<$count; $j++){

				 for($i=1; $i<=20; $i++){

					 if(!empty($Uriages[$j]->{"price_{$i}"})){

						 $Uriagetotalhyouji = $Uriagetotalhyouji + $Uriages[$j]->{"price_{$i}"};

					 }

				 }

			 }
			 $Uriagetotalhyouji = $Uriagetotalhyouji * 1.1;
			 $this->set('Uriagetotalhyouji',$Uriagetotalhyouji);
     }

		     public function uriagekensakusyousai()
		     {
					 $uriages = $this->Uriages->newEntity();
		       $this->set('uriages',$uriages);

		       $data = $this->request->getData();

		       $data = array_keys($data, '詳細');
					 $id = $data[0];
					 $this->set('id',$id);

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

					 $count = 0;
					 for($i=1; $i<=20; $i++){

						 if(!empty($Uriages[0]["pro_".$i])){

							 $count = $count + 1;
							 $this->set('count',$count);
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

		     }

				 public function uriagekensakuedit()
		     {
					 $uriages = $this->Uriages->newEntity();
		       $this->set('uriages',$uriages);

					 $data = $this->request->getData();
					 $id = $data["id"];
					 $this->set('id',$id);
					 /*
					 echo "<pre>";
		       print_r($data);
		       echo "</pre>";
*/
		       $Uriages = $this->Uriages->find()->where(['id' => $id])->toArray();

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

					 $count = 0;
					 for($i=1; $i<=20; $i++){

						 if(!empty($Uriages[0]["pro_".$i])){

							 $count = $count + 1;
							 $this->set('count',$count);
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

		     }

				 public function uriagekensakueditdo()
		     {
					 $uriages = $this->Uriages->newEntity();
		       $this->set('uriages',$uriages);

					 $data = $this->request->getData();
					 $id = $data["id"];
					 $this->set('id',$id);

					 if($data["delete_flag"] == 1){
		         $mess = "以下のデータを削除しました。";
		       }else{
		         $mess = "以下のように更新しました。";
		       }
		       $this->set('mess',$mess);

					 $total_price = 0;
					 for($i=1; $i<=20; $i++){

						 if(!empty($data["pro_".$i])){

							 $data["pro_".$i] = $data["pro_".$i];
							 $data["amount_".$i] = $data["amount_".$i];
							 $data["tani_".$i] = $data["tani_".$i];
							 $data["tanka_".$i] = $data["tanka_".$i];
							 $data["bik_".$i] = $data["bik_".$i];
							 ${"price_".$i} = $data["tanka_".$i]*$data["amount_".$i];

							 $total_price = $total_price + $data["tanka_".$i]*$data["amount_".$i];

						 }else{

							 $data["pro_".$i] = NULL;
							 $data["amount_".$i] = NULL;
							 $data["tani_".$i] = NULL;
							 $data["tanka_".$i] = NULL;
							 $data["bik_".$i] = NULL;
							 ${"price_".$i} = NULL;

						 }

					 }

					 $total_price = $total_price * 1.1;

					 $total_price_moto = 0;
					 $Uriages = $this->Uriages->find()->where(['id' => $id])->toArray();
					 $customerId = $Uriages[0]->customerId;

					 for($i=1; $i<=20; $i++){

						 if(!empty($Uriages[0]["pro_".$i])){

							 $total_price_moto = $total_price_moto + $Uriages[0]["price_".$i];

						 }

					 }
					 $total_price_moto = $total_price_moto * 1.1;

					 $uriage = $this->Uriages->patchEntity($uriages, $data);
		       $connection = ConnectionManager::get('default');//トランザクション1
		       // トランザクション開始2
		       $connection->begin();//トランザクション3
		       try {//トランザクション4
		         if ($this->Uriages->updateAll(
		           [
							 'pro_1' => $data['pro_1'], 'pro_2' => $data['pro_2'], 'pro_3' => $data['pro_3'], 'pro_4' => $data['pro_4'],
							 'pro_5' => $data['pro_5'], 'pro_6' => $data['pro_6'], 'pro_7' => $data['pro_7'], 'pro_8' => $data['pro_8'],
							 'pro_9' => $data['pro_9'], 'pro_10' => $data['pro_10'], 'pro_11' => $data['pro_11'], 'pro_12' => $data['pro_12'],
							 'pro_13' => $data['pro_13'], 'pro_14' => $data['pro_14'], 'pro_15' => $data['pro_15'], 'pro_16' => $data['pro_16'],
							 'pro_17' => $data['pro_17'], 'pro_18' => $data['pro_18'], 'pro_19' => $data['pro_19'], 'pro_20' => $data['pro_20'],
							 'amount_1' => $data['amount_1'], 'amount_2' => $data['amount_2'], 'amount_3' => $data['amount_3'], 'amount_4' => $data['amount_4'],
							 'amount_5' => $data['amount_5'], 'amount_6' => $data['amount_6'], 'amount_7' => $data['amount_7'], 'amount_8' => $data['amount_8'],
							 'amount_9' => $data['amount_9'], 'amount_10' => $data['amount_10'], 'amount_11' => $data['amount_11'], 'amount_12' => $data['amount_12'],
							 'amount_13' => $data['amount_13'], 'amount_14' => $data['amount_14'], 'amount_15' => $data['amount_15'], 'amount_16' => $data['amount_16'],
							 'amount_17' => $data['amount_17'], 'amount_18' => $data['amount_18'], 'amount_19' => $data['amount_19'], 'amount_20' => $data['amount_20'],
							 'tani_1' => $data['tani_1'], 'tani_2' => $data['tani_2'], 'tani_3' => $data['tani_3'], 'tani_4' => $data['tani_4'],
							 'tani_5' => $data['tani_5'], 'tani_6' => $data['tani_6'], 'tani_7' => $data['tani_7'], 'tani_8' => $data['tani_8'],
							 'tani_9' => $data['tani_9'], 'tani_10' => $data['tani_10'], 'tani_11' => $data['tani_11'], 'tani_12' => $data['tani_12'],
							 'tani_13' => $data['tani_13'], 'tani_14' => $data['tani_14'], 'tani_15' => $data['tani_15'], 'tani_16' => $data['tani_16'],
							 'tani_17' => $data['tani_17'], 'tani_18' => $data['tani_18'], 'tani_19' => $data['tani_19'], 'tani_20' => $data['tani_20'],
							 'tanka_1' => $data['tanka_1'], 'tanka_2' => $data['tanka_2'], 'tanka_3' => $data['tanka_3'], 'tanka_4' => $data['tanka_4'],
							 'tanka_5' => $data['tanka_5'], 'tanka_6' => $data['tanka_6'], 'tanka_7' => $data['tanka_7'], 'tanka_8' => $data['tanka_8'],
							 'tanka_9' => $data['tanka_9'], 'tanka_10' => $data['tanka_10'], 'tanka_11' => $data['tanka_11'], 'tanka_12' => $data['tanka_12'],
							 'tanka_13' => $data['tanka_13'], 'tanka_14' => $data['tanka_14'], 'tanka_15' => $data['tanka_15'], 'tanka_16' => $data['tanka_16'],
							 'tanka_17' => $data['tanka_17'], 'tanka_18' => $data['tanka_18'], 'tanka_19' => $data['tanka_19'], 'tanka_20' => $data['tanka_20'],
							 'price_1' => $price_1, 'price_2' => $price_2, 'price_3' => $price_3, 'price_4' => $price_4,
							 'price_5' => $price_5, 'price_6' => $price_6, 'price_7' => $price_7, 'price_8' => $price_8,
							 'price_9' => $price_9, 'price_10' => $price_10, 'price_11' => $price_11, 'price_12' => $price_12,
							 'price_13' => $price_13, 'price_14' => $price_14, 'price_15' => $price_15, 'price_16' => $price_16,
							 'price_17' => $price_17, 'price_18' => $price_18, 'price_19' => $price_19, 'price_20' => $price_20,
							 'bik_1' => $data['bik_1'], 'bik_2' => $data['bik_2'], 'bik_3' => $data['bik_3'], 'bik_4' => $data['bik_4'],
							 'bik_5' => $data['bik_5'], 'bik_6' => $data['bik_6'], 'bik_7' => $data['bik_7'], 'bik_8' => $data['bik_8'],
							 'bik_9' => $data['bik_9'], 'bik_10' => $data['bik_10'], 'bik_11' => $data['bik_11'], 'bik_12' => $data['bik_12'],
							 'bik_13' => $data['bik_13'], 'bik_14' => $data['bik_14'], 'bik_15' => $data['bik_15'], 'bik_16' => $data['bik_16'],
							 'bik_17' => $data['bik_17'], 'bik_18' => $data['bik_18'], 'bik_19' => $data['bik_19'], 'bik_20' => $data['bik_20'],
							 'delete_flag' => $data['delete_flag']],
		           ['id'  => $data['id']]
		         )){

							 $Miseikyuus = $this->Miseikyuus->find('all', ['conditions' => ['customerId' => $customerId, 'delete_flag' => 0]])->toArray();
							 if(isset($Miseikyuus[0])){

								 $miseikyuugaku = $Miseikyuus[0]->miseikyuugaku - $total_price_moto + $total_price;
/*
								 echo "<pre>";
								 print_r($Miseikyuus[0]->miseikyuugaku." - ".$total_price_moto." + ".$total_price." = ".$miseikyuugaku);
								 echo "</pre>";
*/
								 $this->Miseikyuus->updateAll(
									 ['miseikyuugaku' => $miseikyuugaku, 'kousinbi' => date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
									 ['id'  => $Miseikyuus[0]->id]
								 );

							 }

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

		       $Uriages = $this->Uriages->find()->where(['id' => $id])->toArray();

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

					 $count = 0;
					 for($i=1; $i<=20; $i++){

						 if(!empty($Uriages[0]["pro_".$i])){

							 $count = $count + 1;
							 $this->set('count',$count);
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

		     }

				 public function nyuukinformcustomer()
		     {
					 $uriages = $this->Uriages->newEntity();
		       $this->set('uriages',$uriages);

					 $arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0']])->order(['furigana' => 'ASC']);
					 $arrCustomer = array();
					 foreach ($arrCustomers as $value) {
						 $arrCustomer[] = array($value->id=>$value->name.' '.$value->siten);
					 }
					 $this->set('arrCustomer',$arrCustomer);

		     }

				 public function nyuukinformcustomerfurigana()
		     {
					 $uriages = $this->Uriages->newEntity();
		       $this->set('uriages',$uriages);

					 $Data = $this->request->query('s');
		       $data = $Data['data'];
		/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
		*/
					 $furigana = $data["nyuryokufurigana"];

					 $arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0', 'furigana like' => '%'.$furigana.'%']])->order(['furigana' => 'ASC']);
					 $arrCustomer = array();
					 foreach ($arrCustomers as $value) {
						 $arrCustomer[] = array($value->id=>$value->name.' '.$value->siten);
					 }
					 $this->set('arrCustomer',$arrCustomer);

		     }

				 public function nyuukinform()
		     {
					 $data = $this->request->getData();

					 if(!empty($data["nyuryokufurigana"])){

						 return $this->redirect(['action' => 'nyuukinformcustomerfurigana',
						 's' => ['data' => $data]]);

					 }

					 if(!empty($data["name1"])){
						 $id = $data["name1"];
						 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["name1"]]])->toArray();
						 $name = $Customer[0]->name;
						 $siten = $Customer[0]->siten;
						 $namehyouji = $name." ".$siten;
						 $this->set('namehyouji',$namehyouji);
						 $this->set('id',$data["name1"]);
						 $nyuukinyotei = $Customer[0]->nyuukinbi;
						 $this->set('nyuukinyotei',$nyuukinyotei);
					 }elseif(!empty($data["name2"])){
						 $id = $data["name2"];
						 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["name2"]]])->toArray();
						 $name = $Customer[0]->name;
						 $siten = $Customer[0]->siten;
						 $namehyouji = $name." ".$siten;
						 $this->set('namehyouji',$namehyouji);
						 $this->set('id',$data["name2"]);
						 $nyuukinyotei = $Customer[0]->nyuukinbi;
						 $this->set('nyuukinyotei',$nyuukinyotei);
				 }else{
						 $name = "";
					 }

		       $customers = $this->Customers->newEntity();
		       $this->set('customers',$customers);

		       $arrSyuukinfurikomi = [
		 				'集金' => '集金',
		         '振込' => '振込'
		 							];
		 			$this->set('arrSyuukinfurikomi',$arrSyuukinfurikomi);

		       $arrSyubetu = [
		 				'振込' => '振込',
						'相殺' => '相殺',
						'現金' => '現金',
						'小切手' => '小切手',
						'手形' => '手形',
						'調整' => '調整'
					];
		 			$this->set('arrSyubetu',$arrSyubetu);

					$Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['delete_flag' => '0', 'customerId' => $id]])->order(['created_at' => 'desc'])->toArray();
					if(isset($Seikyuus[0])){
						$date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y年m月d日');
						$this->set('date_seikyuu',$date_seikyuu);
						$touroku_date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
						$this->set('touroku_date_seikyuu',$touroku_date_seikyuu);
						$totalseikyuu = $Seikyuus[0]->total_price;
						$this->set('totalseikyuu',$totalseikyuu);
					}else{
						$date_seikyuu = "";
						$this->set('date_seikyuu',$date_seikyuu);
						$touroku_date_seikyuu = "";
						$this->set('touroku_date_seikyuu',$touroku_date_seikyuu);
						$totalseikyuu = "";
						$this->set('totalseikyuu',$totalseikyuu);

						echo "<pre>";
						print_r("請求書を発行していない顧客が選択されています。");
  					 echo "</pre>";
					}


		     }

				 public function nyuukinconfirm()
		     {
					 $data = $this->request->getData();
/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
*/
					 if(!empty($data['datenyuukinyotei']['year'])){
						 $datenyuukinyoteitouroku = $data['datenyuukinyotei']['year']."-".$data['datenyuukinyotei']['month']."-".$data['datenyuukinyotei']['day'];
						 $this->set('datenyuukinyoteitouroku',$datenyuukinyoteitouroku);
					 }else{
						 $datenyuukinyoteitouroku = "";
						 $this->set('datenyuukinyoteitouroku',$datenyuukinyoteitouroku);
					 }

					 if(!empty($data['dateseikyuu']['year'])){
						 $dateseikyuutouroku = $data['dateseikyuu']['year']."-".$data['dateseikyuu']['month']."-".$data['dateseikyuu']['day'];
						 $this->set('dateseikyuutouroku',$dateseikyuutouroku);
					 }else{
						 $dateseikyuutouroku = "";
						 $this->set('dateseikyuutouroku',$dateseikyuutouroku);
					 }

					 if(!empty($data['datenyuukin']['year'])){
						 $datenyuukintouroku = $data['datenyuukin']['year']."-".$data['datenyuukin']['month']."-".$data['datenyuukin']['day'];
						 $this->set('datenyuukintouroku',$datenyuukintouroku);
					 }else{
						 $datenyuukintouroku = "";
						 $this->set('datenyuukintouroku',$datenyuukintouroku);
					 }

					 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
					 $name = $Customer[0]->name;
					 $siten = $Customer[0]->siten;
					 $namehyouji = $name." ".$siten;
					 $this->set('namehyouji',$namehyouji);
					 $this->set('id',$data["id"]);
					 $nyuukinyotei = $Customer[0]->nyuukinbi;
					 $this->set('nyuukinyotei',$nyuukinyotei);

		       $customers = $this->Customers->newEntity();
		       $this->set('customers',$customers);

					 $Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['delete_flag' => '0', 'customerId' => $data["id"]]])->order(['created_at' => 'desc'])->toArray();
					 if(isset($Seikyuus[0])){
 						$date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y年m月d日');
 						$this->set('date_seikyuu',$date_seikyuu);
 						$touroku_date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
 						$this->set('touroku_date_seikyuu',$touroku_date_seikyuu);
 						$totalseikyuu = $Seikyuus[0]->total_price;
 						$this->set('totalseikyuu',$totalseikyuu);
 					}else{
 						$date_seikyuu = "";
 						$this->set('date_seikyuu',$date_seikyuu);
 						$touroku_date_seikyuu = "";
 						$this->set('touroku_date_seikyuu',$touroku_date_seikyuu);
 						$totalseikyuu = "";
 						$this->set('totalseikyuu',$totalseikyuu);

 						echo "<pre>";
						print_r("請求書を発行していない顧客が選択されています。");
   					 echo "</pre>";
 					}

		     }

				 public function nyuukindo()
		     {
					 $data = $this->request->getData();
/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
*/
					 $dateseikyuutouroku =$data['dateseikyuutouroku'];
					 $this->set('dateseikyuutouroku',$dateseikyuutouroku);

					 $datenyuukintouroku = $data['datenyuukintouroku'];
					 $this->set('datenyuukintouroku',$datenyuukintouroku);

					 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
					 $name = $Customer[0]->name;
					 $furigana = $Customer[0]->furigana;
					 $siten = $Customer[0]->siten;
					 $namehyouji = $name." ".$siten;
					 $this->set('namehyouji',$namehyouji);
					 $nyuukinyotei = $Customer[0]->nyuukinbi;
					 $this->set('nyuukinyotei',$nyuukinyotei);

					 $Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['delete_flag' => '0', 'customerId' => $data["id"]]])->order(['created_at' => 'desc'])->toArray();
					 if(isset($Seikyuus[0])){
 						$date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y年m月d日');
 						$this->set('date_seikyuu',$date_seikyuu);
 						$touroku_date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
 						$this->set('touroku_date_seikyuu',$touroku_date_seikyuu);
 						$totalseikyuu = $Seikyuus[0]->total_price;
 						$this->set('totalseikyuu',$totalseikyuu);
 					}else{
 						$date_seikyuu = "";
 						$this->set('date_seikyuu',$date_seikyuu);
 						$touroku_date_seikyuu = "";
 						$this->set('touroku_date_seikyuu',$touroku_date_seikyuu);
 						$totalseikyuu = "";
 						$this->set('totalseikyuu',$totalseikyuu);

 						echo "<pre>";
 						print_r("請求書を発行していない顧客が選択されています。");
   					 echo "</pre>";
 					}

					 $tourokuArr = array();

					 $tourokuArr = array('customerId' => $data["id"],'customer' => $namehyouji,'furigana' => $furigana,
					 'syuukinfurikomi' => $data["syuukinfurikomi"],'syubetu' => $data["syubetu"],'bik' => $data["bik"],
					 'nyuukinngaku' => $data["nyuukinngaku"],'seikyuu' => $totalseikyuu,
					 'dateseikyuu' => $touroku_date_seikyuu, 'datenyuukin' => $data["datenyuukintouroku"],
					 'delete_flag' => 0,'created_at' => date('Y-m-d H:i:s', strtotime('+9hour')));
/*
						echo "<pre>";
						print_r($tourokuArr);
						echo "</pre>";
*/
						$nyuukins = $this->Nyuukins->newEntity();
						$this->set('nyuukins',$nyuukins);

						$nyuukin = $this->Nyuukins->patchEntity($nyuukins, $tourokuArr);
		        $connection = ConnectionManager::get('default');//トランザクション1
		        // トランザクション開始2
		        $connection->begin();//トランザクション3
		        try {//トランザクション4
		          if ($this->Nyuukins->save($nyuukin)) {

								$Zandakas = $this->Zandakas->find('all', ['conditions' => ['customerId' => $data["id"], 'delete_flag' => 0]])->toArray();
								if(isset($Zandakas[0])){

									$zandaka = $Zandakas[0]->zandaka - $data["nyuukinngaku"];

									$this->Zandakas->updateAll(
										['zandaka' => $zandaka, 'koushinbi' =>  date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
										['id'  => $Zandakas[0]->id]
									);

								}

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

				 public function nyuukinsyoukaimenu()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);
				 }

				 public function nyuukinsyoukaiseikyuuform()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);
				 }

				 public function nyuukinsyoukaiseikyuuitiran()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);

					 $data = $this->request->getData();
/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
*/
					 $date_sta = $data['date_sta']['year']."-".$data['date_sta']['month']."-".$data['date_sta']['day'];
					 $date_fin = $data['date_fin']['year']."-".$data['date_fin']['month']."-".$data['date_fin']['day'];
					 $this->set('date_sta',$date_sta);
					 $this->set('date_fin',$date_fin);

					 $date_fin = strtotime($date_fin);

					 $Nyuukins = $this->Nyuukins->find()
					 ->where(['datenyuukin >=' => $date_sta, 'datenyuukin <=' => $date_fin, 'delete_flag' => 0])->order(["dateseikyuu"=>"ASC"])->toArray();
					 $this->set('Nyuukins',$Nyuukins);

					 $count = count($Nyuukins);

					 $totalkingaku = 0;
					 for ($k=0; $k<$count; $k++){
						 $totalkingaku = $totalkingaku + $Nyuukins[$k]->nyuukinngaku;
					 }
					 $this->set('totalkingaku',$totalkingaku);

				 }

				 public function nyuukinsyoukainyuukinngakuform()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);
				 }

				 public function nyuukinsyoukainyuukinngakuitiran()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);

					 $data = $this->request->getData();
/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
*/
					 $date_sta = $data['date_sta']['year']."-".$data['date_sta']['month']."-".$data['date_sta']['day'];
					 $date_fin = $data['date_fin']['year']."-".$data['date_fin']['month']."-".$data['date_fin']['day'];
					 $this->set('date_sta',$date_sta);
					 $this->set('date_fin',$date_fin);

					 $date_fin = strtotime($date_fin);
					 $date_fin = date('Y-m-d', strtotime('+1 day', $date_fin));

					 $Nyuukins = $this->Nyuukins->find()
					 ->where(['dateseikyuu >=' => $date_sta, 'dateseikyuu <=' => $date_fin, 'delete_flag' => 0])->order(["furigana"=>"ASC"])->toArray();
					 $this->set('Nyuukins',$Nyuukins);

					 $count = count($Nyuukins);

					 $totalkingaku = 0;
					 for ($k=0; $k<$count; $k++){
						 $totalkingaku = $totalkingaku + $Nyuukins[$k]->nyuukinngaku;
					 }
					 $this->set('totalkingaku',$totalkingaku);

				 }

				 public function nyuukinsyoukaisyousai()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);

					 $data = $this->request->getData();
					 $data = array_keys($data, '詳細');
					 $id = $data[0];
					 $this->set('id',$id);
/*
					 echo "<pre>";
					 print_r($id);
					 echo "</pre>";
*/
					 $Nyuukin = $this->Nyuukins->find('all', ['conditions' => ['id' => $id]])->toArray();
					 $customerId = $Nyuukin[0]->customerId;
					 $customer = $Nyuukin[0]->customer;
					 $this->set('customer',$customer);
					 $datenyuukin = $Nyuukin[0]->datenyuukin;
					 $this->set('datenyuukin',$datenyuukin);
					 $syuukinfurikomi = $Nyuukin[0]->syuukinfurikomi;
					 $this->set('syuukinfurikomi',$syuukinfurikomi);
					 $datenyuukinyotei = $Nyuukin[0]->datenyuukinyotei;
					 $this->set('datenyuukinyoteitouroku',$datenyuukinyotei);
					 if(!empty($datenyuukinyotei)){
						 $datenyuukinyotei = $Nyuukin[0]->datenyuukinyotei->format('Y年m月d日');
					 }
					 $this->set('datenyuukinyotei',$datenyuukinyotei);
					 $dateseikyuu = $Nyuukin[0]->dateseikyuu;
					 $this->set('dateseikyuutouroku',$dateseikyuu);
					 if(!empty($dateseikyuu)){
						 $dateseikyuu = $Nyuukin[0]->dateseikyuu->format('Y年m月d日');
					 }
					 $this->set('dateseikyuu',$dateseikyuu);
					 $kurikosi = $Nyuukin[0]->kurikosi;
					 $this->set('kurikosi',$kurikosi);
					 $seikyuu = $Nyuukin[0]->seikyuu;
					 $this->set('seikyuu',$seikyuu);
					 $datenyuukin = $Nyuukin[0]->datenyuukin;
					 $this->set('datenyuukintouroku',$datenyuukin);
					 if(!empty($datenyuukin)){
						 $datenyuukin = $Nyuukin[0]->datenyuukin->format('Y年m月d日');
					 }
					 $this->set('datenyuukin',$datenyuukin);
					 $syubetu = $Nyuukin[0]->syubetu;
					 $this->set('syubetu',$syubetu);
					 $nyuukinngaku = $Nyuukin[0]->nyuukinngaku;
					 $this->set('nyuukinngaku',$nyuukinngaku);
					 $bik = $Nyuukin[0]->bik;
					 $this->set('bik',$bik);

					 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $customerId]])->toArray();
					 $nyuukinyotei = $Customer[0]->nyuukinbi;
					 $this->set('nyuukinyotei',$nyuukinyotei);

					 $Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['delete_flag' => '0', 'customerId' => $customerId]])->order(['date_seikyuu' => 'desc'])->toArray();
 					$date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y年m月d日');
 					$this->set('date_seikyuu',$date_seikyuu);
 					$touroku_date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
 					$this->set('touroku_date_seikyuu',$touroku_date_seikyuu);
 					$totalseikyuu = $Seikyuus[0]->total_price;
 					$this->set('totalseikyuu',$totalseikyuu);

				 }

				 public function nyuukinsyoukaiedit()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);
					 $data = $this->request->getData();
/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
*/
					 $id = $data["id"];
					 $this->set('id',$id);

					 $arrSyuukinfurikomi = [
						'集金' => '集金',
						 '振込' => '振込'
									];
					$this->set('arrSyuukinfurikomi',$arrSyuukinfurikomi);

					 $arrSyubetu = [
						'振込' => '振込',
						'相殺' => '相殺',
						'現金' => '現金',
						'小切手' => '小切手',
						'手形' => '手形',
						'調整' => '調整'
					];
					$this->set('arrSyubetu',$arrSyubetu);

					$Nyuukin = $this->Nyuukins->find('all', ['conditions' => ['id' => $id]])->toArray();
					$customerId = $Nyuukin[0]->customerId;
					$syubetu = $Nyuukin[0]->syubetu;
					$this->set('syubetu',$syubetu);
					$nyuukinngaku = $Nyuukin[0]->nyuukinngaku;
					$this->set('nyuukinngaku',$nyuukinngaku);
					$bik = $Nyuukin[0]->bik;
					$this->set('bik',$bik);
					$datenyuukin = $Nyuukin[0]->datenyuukin;
					$this->set('datenyuukin',$datenyuukin);

					$Customer = $this->Customers->find('all', ['conditions' => ['id' => $customerId]])->toArray();
					$nyuukinyotei = $Customer[0]->nyuukinbi;
					$this->set('nyuukinyotei',$nyuukinyotei);

					$Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['delete_flag' => '0', 'customerId' => $customerId]])->order(['date_seikyuu' => 'desc'])->toArray();
				 $date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y年m月d日');
				 $this->set('date_seikyuu',$date_seikyuu);
				 $touroku_date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
				 $this->set('touroku_date_seikyuu',$touroku_date_seikyuu);
				 $totalseikyuu = $Seikyuus[0]->total_price;
				 $this->set('totalseikyuu',$totalseikyuu);

				 }

				 public function nyuukinsyoukaieditdo()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);
					 $data = $this->request->getData();

					 $id = $data["id"];
					 $this->set('id',$id);

					 $datenyuukin = $data['datenyuukin']['year']."-".$data['datenyuukin']['month']."-".$data['datenyuukin']['day'];
					 $this->set('datenyuukin',$datenyuukin);

					 $Nyuukin = $this->Nyuukins->find('all', ['conditions' => ['id' => $id]])->toArray();
					 $customerId = $Nyuukin[0]->customerId;
					 $nyuukinmoto = $Nyuukin[0]->nyuukinngaku;

					 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $customerId]])->toArray();
					 $nyuukinyotei = $Customer[0]->nyuukinbi;
					 $this->set('nyuukinyotei',$nyuukinyotei);

					 $Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['delete_flag' => '0', 'customerId' => $customerId]])->order(['date_seikyuu' => 'desc'])->toArray();
					 $date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y年m月d日');
 	 				 $this->set('date_seikyuu',$date_seikyuu);
 	 				 $touroku_date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
 	 				 $this->set('touroku_date_seikyuu',$touroku_date_seikyuu);
 	 				 $totalseikyuu = $Seikyuus[0]->total_price;
 	 				 $this->set('totalseikyuu',$totalseikyuu);

					 if($data["delete_flag"] == 1){
		         $mess = "以下のデータを削除しました。";
		       }else{
		         $mess = "以下のように更新しました。";
		       }
		       $this->set('mess',$mess);

					 $nyuukin = $this->Nyuukins->patchEntity($nyuukins, $data);
		       $connection = ConnectionManager::get('default');//トランザクション1
		       // トランザクション開始2
		       $connection->begin();//トランザクション3
		       try {//トランザクション4
		         if ($this->Nyuukins->updateAll(
		           [
							 'syuukinfurikomi' => $data['syuukinfurikomi'],  'datenyuukin' => $datenyuukin,
							 'syubetu' => $data['syubetu'], 'nyuukinngaku' => $data['nyuukinngaku'], 'bik' => $data['bik'],
							 'delete_flag' => $data['delete_flag']],
		           ['id'  => $data['id']]
		         )){

							 $Zandakas = $this->Zandakas->find('all', ['conditions' => ['customerId' => $customerId, 'delete_flag' => 0]])->toArray();
							 if(isset($Zandakas[0])){

								 $zandaka = $Zandakas[0]->zandaka + $nyuukinmoto - $data['nyuukinngaku'];

								 $this->Zandakas->updateAll(
									 ['zandaka' => $zandaka, 'koushinbi' =>  date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
									 ['id'  => $Zandakas[0]->id]
								 );

							 }

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

				 public function seikyuuformcustomer()
		     {
					 $uriages = $this->Uriages->newEntity();
		       $this->set('uriages',$uriages);

					 $arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0']])->order(['furigana' => 'ASC']);
					 $arrCustomer = array();
					 foreach ($arrCustomers as $value) {
						 $arrCustomer[] = array($value->id=>$value->name.' '.$value->siten);
					 }
					 $this->set('arrCustomer',$arrCustomer);

		     }

				 public function seikyuuformcustomerfurigana()
		     {
					 $uriages = $this->Uriages->newEntity();
		       $this->set('uriages',$uriages);

					 $Data = $this->request->query('s');
		       $data = $Data['data'];
		/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
		*/
					 $furigana = $data["nyuryokufurigana"];

					 $arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0', 'furigana like' => '%'.$furigana.'%']])->order(['furigana' => 'ASC']);
					 $arrCustomer = array();
					 foreach ($arrCustomers as $value) {
						 $arrCustomer[] = array($value->id=>$value->name.' '.$value->siten);
					 }
					 $this->set('arrCustomer',$arrCustomer);

		     }

				 public function seikyuuform()
		     {
					 $data = $this->request->getData();

					 if(!empty($data["nyuryokufurigana"])){

						 return $this->redirect(['action' => 'seikyuuformcustomerfurigana',
						 's' => ['data' => $data]]);

					 }

					 $dataId = array_keys($data, '請求処理へ');
/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
*/
					 if(!empty($data["name1"])){
						 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["name1"]]])->toArray();
						 $name = $Customer[0]->name;
						 $siten = $Customer[0]->siten;
						 $namehyouji = $name." ".$siten;
						 $this->set('namehyouji',$namehyouji);
						 $id = $data["name1"];
						 $this->set('id',$data["name1"]);
						 $simebi = $Customer[0]->simebi;
						 $this->set('simebi',$simebi);
					 }elseif(!empty($data["name2"])){
						 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["name2"]]])->toArray();
						 $name = $Customer[0]->name;
						 $siten = $Customer[0]->siten;
						 $namehyouji = $name." ".$siten;
						 $this->set('namehyouji',$namehyouji);
						 $id = $data["name2"];
						 $this->set('id',$data["name2"]);
						 $simebi = $Customer[0]->simebi;
						 $this->set('simebi',$simebi);
					 }elseif(!empty($dataId[0])){
						 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $dataId[0]]])->toArray();
						 $name = $Customer[0]->name;
						 $siten = $Customer[0]->siten;
						 $namehyouji = $name." ".$siten;
						 $this->set('namehyouji',$namehyouji);
						 $id = $dataId[0];
						 $this->set('id',$dataId[0]);
						 $simebi = $Customer[0]->simebi;
						 $this->set('simebi',$simebi);
					 }else{
						 $name = "";
					 }

					 $Uriage = $this->Uriages->find('all', ['conditions' => ['customerId' => $id, 'delete_flag' => 0, 'seikyuuId' => 0]])->order(['syutsuryokubi' => 'ASC'])->toArray();
					 $count = count($Uriage);
					 $this->set('count',$count);

					 $totalkingaku = 0;
					 $arrPro_1 = array();
					 $arrDenpyou = array();
					 $arrSyuturyoku = array();
					 $arrTotalprice = array();

					 if($count > 0){

						 $this->set('count',$count);

							 for ($k=0; $k<$count; $k++){

								 $arrPro_1[] = $Uriage[$k]->pro_1;
								 $arrDenpyou[] = $Uriage[$k]->denpyou_num;
								 $arrSyuturyoku[] = $Uriage[$k]->syutsuryokubi->format('m/d');

								 ${"Totalprice".$k} = 0;
								 for($i=1; $i<=20; $i++){

									 if(!empty($Uriage[$k]->{"pro_{$i}"})){

										 $totalkingaku = $totalkingaku + $Uriage[$k]->{"price_{$i}"};
										 ${"Totalprice".$k} = ${"Totalprice".$k} + $Uriage[$k]->{"price_{$i}"};

									 }

								 }
								 $this->set("Totalprice".$k,${"Totalprice".$k});

							 }

					 }

					 $this->set('totalkingaku',$totalkingaku);
					 $this->set('arrPro_1',$arrPro_1);
					 $this->set('arrDenpyou',$arrDenpyou);
					 $this->set('arrSyuturyoku',$arrSyuturyoku);

		       $customers = $this->Customers->newEntity();
		       $this->set('customers',$customers);

					 $Today = date('m')."/".date('d', strtotime('+9hour'));
					 $this->set('Today',$Today);
					 $monthSeikyuu = date('Y', strtotime('+9hour'))."年 ".date('m', strtotime('+9hour'))."月度";
					 $this->set('monthSeikyuu',$monthSeikyuu);

					 $Seikyuu = $this->Seikyuus->find('all', ['conditions' => ['customerId' => $id, 'delete_flag' => 0]])->order(['date_seikyuu' => 'desc'])->toArray();

					 $nyuukinntotal = 0;
					 $tyouseitotal = 0;
					 $sousaitotal = 0;

					 if(isset($Seikyuu[0])){
						 $Zenkai = $Seikyuu[0]->total_price;
						 $datezenkai = $Seikyuu[0]->date_seikyuu->format('Y-m-d');

						 $Nyuukins = $this->Nyuukins->find()
						 ->where(['datenyuukin >=' => $datezenkai, 'customerId' => $id, 'delete_flag' => 0,
						 'OR' => [['syubetu' => "振込"], ['syubetu' => "現金"], ['syubetu' => "小切手"], ['syubetu' => "手形"]]])
						 ->toArray();

						 $count = count($Nyuukins);
						 for ($k=0; $k<$count; $k++){
							 $nyuukinntotal = $nyuukinntotal + $Nyuukins[0]->nyuukinngaku;
						 }

						 $Nyuukinstyousei = $this->Nyuukins->find()
						 ->where(['datenyuukin >=' => $datezenkai, 'customerId' => $id, 'syubetu' => "調整", 'delete_flag' => 0])
						 ->toArray();

						 $count = count($Nyuukinstyousei);
						 for ($k=0; $k<$count; $k++){
							 $tyouseitotal = $tyouseitotal + $Nyuukinstyousei[0]->nyuukinngaku;
						 }

						 $Nyuukinssousai = $this->Nyuukins->find()
						 ->where(['datenyuukin >=' => $datezenkai, 'customerId' => $id, 'syubetu' => "相殺", 'delete_flag' => 0])
						 ->toArray();

						 $count = count($Nyuukinssousai);
						 for ($k=0; $k<$count; $k++){
							 $sousaitotal = $sousaitotal + $Nyuukinssousai[0]->nyuukinngaku;
						 }

					 }else{
						 $Zenkai = 0;
					 }
					 $this->set('Zenkai',$Zenkai);
					 $this->set('nyuukinntotal',$nyuukinntotal);
					 $this->set('tyouseitotal',$tyouseitotal);
					 $this->set('sousaitotal',$sousaitotal);

		     }

				 public function seikyuuconfirm()
		     {
					 $data = $this->request->getData();
/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
*/
					 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
					 $name = $Customer[0]->name;
					 $siten = $Customer[0]->siten;
					 $namehyouji = $name." ".$siten;
					 $this->set('namehyouji',$namehyouji);
					 $id = $data["id"];
					 $this->set('id',$data["id"]);
					 $simebi = $Customer[0]->simebi;
					 $this->set('simebi',$simebi);

					 $Uriage = $this->Uriages->find('all', ['conditions' => ['customerId' => $id, 'delete_flag' => 0, 'seikyuuId' => 0]])->order(['syutsuryokubi' => 'ASC'])->toArray();
					 $count = count($Uriage);
					 $this->set('count',$count);

					 $totalkingaku = 0;
					 $arrPro_1 = array();
					 $arrDenpyou = array();
					 $arrSyuturyoku = array();
					 $arrTotalprice = array();

					 if($count > 0){

						 $this->set('count',$count);

							 for ($k=0; $k<$count; $k++){

								 $arrPro_1[] = $Uriage[$k]->pro_1;
								 $arrDenpyou[] = $Uriage[$k]->denpyou_num;
								 $arrSyuturyoku[] = $Uriage[$k]->syutsuryokubi->format('m/d');

								 ${"Totalprice".$k} = 0;
								 for($i=1; $i<=20; $i++){

									 if(!empty($Uriage[$k]->{"pro_{$i}"})){

										 $totalkingaku = $totalkingaku + $Uriage[$k]->{"price_{$i}"};
										 ${"Totalprice".$k} = ${"Totalprice".$k} + $Uriage[$k]->{"price_{$i}"};

									 }

								 }
								 $this->set("Totalprice".$k,${"Totalprice".$k});

							 }

					 }

					 $this->set('totalkingaku',$totalkingaku);
					 $this->set('arrPro_1',$arrPro_1);
					 $this->set('arrDenpyou',$arrDenpyou);
					 $this->set('arrSyuturyoku',$arrSyuturyoku);

		       $customers = $this->Customers->newEntity();
		       $this->set('customers',$customers);

					 $Today = date('m')."/".date('d', strtotime('+9hour'));
					 $this->set('Today',$Today);
					 $monthSeikyuu = date('Y', strtotime('+9hour'))."年 ".date('m', strtotime('+9hour'))."月度";
					 $this->set('monthSeikyuu',$monthSeikyuu);

					 $kurikosi = $data["Zenkai"] - $data["nyuukingaku"] - $data["tyousei"] - $data["sousai"];
					 $this->set('kurikosi',$kurikosi);

					 $totalseikyuu = $totalkingaku*1.1 + $kurikosi;
					 $this->set('totalseikyuu',$totalseikyuu);
		     }

				 public function seikyuudo()
		     {
					 $data = $this->request->getData();

					 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
					 $name = $Customer[0]->name;
					 $furigana = $Customer[0]->furigana;
					 $siten = $Customer[0]->siten;
					 $namehyouji = $name." ".$siten;
					 $this->set('namehyouji',$namehyouji);
					 $id = $data["id"];
					 $this->set('id',$data["id"]);
					 $simebi = $Customer[0]->simebi;
					 $this->set('simebi',$simebi);
					 $yuubin = $Customer[0]->yuubin;
					 $address = $Customer[0]->address;
					 $keisyou = $Customer[0]->keisyou;

					 if($keisyou == 1){
						 $keisyou = '様';
					 }elseif($keisyou == 2){
						 $keisyou = '御中';
					 }else{
						 $keisyou = '殿';
					 }

					 $Uriage = $this->Uriages->find('all', ['conditions' => ['customerId' => $id, 'delete_flag' => 0, 'seikyuuId' => 0]])->order(['syutsuryokubi' => 'ASC'])->toArray();
					 $count = count($Uriage);

					 $totalkingaku = 0;
					 $arrPro_1 = array();
					 $arrDenpyou = array();
					 $arrSyuturyoku = array();
					 $arrTotalprice = array();

					 if($count > 0){

						 $this->set('count',$count);

							 for ($k=0; $k<$count; $k++){

								 $arrPro_1[] = $Uriage[$k]->pro_1;
								 $arrDenpyou[] = $Uriage[$k]->denpyou_num;
								 $arrSyuturyoku[] = $Uriage[$k]->syutsuryokubi->format('m/d');

								 ${"Totalprice".$k} = 0;
								 for($i=1; $i<=20; $i++){

									 if(!empty($Uriage[$k]->{"pro_{$i}"})){

										 $totalkingaku = $totalkingaku + $Uriage[$k]->{"price_{$i}"};
										 ${"Totalprice".$k} = ${"Totalprice".$k} + $Uriage[$k]->{"price_{$i}"};

									 }

								 }
								 $this->set("Totalprice".$k,${"Totalprice".$k});

							 }

					 }

					 $this->set('totalkingaku',$totalkingaku);
					 $this->set('arrPro_1',$arrPro_1);
					 $this->set('arrDenpyou',$arrDenpyou);
					 $this->set('arrSyuturyoku',$arrSyuturyoku);

		       $customers = $this->Customers->newEntity();
		       $this->set('customers',$customers);

					 $Today = date('m', strtotime('+9hour'))."/".date('d', strtotime('+9hour'));
					 $this->set('Today',$Today);
					 $monthSeikyuu = date('Y', strtotime('+9hour'))."年 ".date('m', strtotime('+9hour'))."月度";
					 $this->set('monthSeikyuu',$monthSeikyuu);

					 $tourokuArr = array();

					 $tourokuArr = array('customerId' => $data["id"],'furigana' => $furigana,
					 'date_seikyuu' => date('Y-m-d', strtotime('+9hour')),'nyuukingaku' => $data["nyuukingaku"],'tyousei' => $data["tyousei"],
					 'sousai' => $data["sousai"], 'total_price' => $data["totalseikyuu"],
					 'delete_flag' => 0,'created_at' => date('Y-m-d H:i:s', strtotime('+9hour')));
/*
						echo "<pre>";
						print_r($tourokuArr);
						echo "</pre>";
*/
						$seikyuus = $this->Seikyuus->newEntity();
						$this->set('seikyuus',$seikyuus);

						$Seikyuu = $this->Seikyuus->find('all')->order(['id' => 'desc'])->toArray();
						if(isset($Seikyuu[0])){
							$SeikyuuId = $Seikyuu[0]->id + 1;
						}else{
							$SeikyuuId = 1;
						}

						$month = (int)date('m', strtotime('+9hour'));
						$day = (int)date('d', strtotime('+9hour'));
						$dateexcl = date('Y', strtotime('+9hour'))."年".$month."月".$day."日";

						$arrPros = array();

						$Uriage = $this->Uriages->find('all', ['conditions' => ['customerId' => $id, 'delete_flag' => 0, 'seikyuuId' => 0]])->order(['syutsuryokubi' => 'ASC'])->toArray();
						$count = count($Uriage);

 					 if($count > 0){

 							 for ($k=0; $k<$count; $k++){

 								 for($i=1; $i<=20; $i++){

 									 if(!empty($Uriage[$k]->{"pro_{$i}"})){

										 $arrPros[] = array('pro' => $Uriage[$k]->{"pro_{$i}"}, 'amount' => $Uriage[$k]->{"amount_{$i}"},
										 'tani' => $Uriage[$k]->{"tani_{$i}"}, 'tanka' => $Uriage[$k]->{"tanka_{$i}"},
										 'price' => $Uriage[$k]->{"price_{$i}"}, 'bik' => $Uriage[$k]->{"bik_{$i}"});

 									 }

 								 }

 							 }

 					 }
/*
					 echo "<pre>";
					 print_r($arrPros);
					 echo "</pre>";
*/
						$amari = count($arrPros) % 20;
						$syou = floor(count($arrPros)/20);

						//エクセル出力
		 			 $filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\請求書.xlsx'; //読み込みたいファイルの指定
		 			 $reader = new XlsxReader();
		 			 $spreadsheet = $reader->load($filepath);

					 $sheet = $spreadsheet->getSheetByName("合計表");
					 $sheet->setCellValue('H1', "No.".$SeikyuuId);
					 $sheet->setCellValue('A2', "〒 ".$yuubin);
					 $sheet->setCellValue('A3', $address);
					 $sheet->setCellValue('A5', $namehyouji);
					 $sheet->setCellValue('E5', $keisyou);
					 $sheet->setCellValue('F2', $dateexcl);
					 $sheet->setCellValue('A12', $data["Zenkai"]);
					 $sheet->setCellValue('B12', $data["nyuukingaku"]);
					 $sheet->setCellValue('C12', $data["tyousei"]);
					 $sheet->setCellValue('D12', $data["sousai"]);
					 $sheet->setCellValue('E12', $data["kurikosi"]);
					 $sheet->setCellValue('F12', $totalkingaku);
					 $sheet->setCellValue('G12', $totalkingaku*0.1);
					 $sheet->setCellValue('I12', $data["totalseikyuu"]);

					 $writer = new XlsxWriter($spreadsheet);

					 $sheet = $spreadsheet->getSheetByName("Sheet1");
					 $sheet->setCellValue('H1', "No.".$SeikyuuId);
					 $sheet->setCellValue('A3', "〒 ".$yuubin);
					 $sheet->setCellValue('A4', $address);
					 $sheet->setCellValue('A6', $namehyouji);
					 $sheet->setCellValue('E6', $keisyou);
					 $sheet->setCellValue('G3', $dateexcl);
					 $sheet->setCellValue('D14', $totalkingaku*1.1);

		 				for($j=2; $j<2+$syou; $j++){

							$baseSheet = $spreadsheet->getSheet(1);
							$newSheet = $baseSheet->copy();
							$newSheet->setTitle( "Sheet".$j );
							$spreadsheet->addSheet( $newSheet );

		 	 			 $writer = new XlsxWriter($spreadsheet);

		 				}

		 			 $pro_check = 0;

		 			 if($amari == 0){//以下余白がいらない場合

		 				 for($i=0; $i<20; $i++){

		 					 if($i == count($arrPros)){
		 						 break;
		 					 }

		 					 $num = 17 + $i;

		 					 $sheet->setCellValue("A".$num, $arrPros[$i]["pro"]);
		 					 $sheet->setCellValue("E".$num, $arrPros[$i]["amount"]);
		 					 $sheet->setCellValue("F".$num, $arrPros[$i]["tani"]);
		 					 $sheet->setCellValue("G".$num, $arrPros[$i]["tanka"]);
		 					 $sheet->setCellValue("H".$num, $arrPros[$i]["price"]);
		 					 $sheet->setCellValue("I".$num, $arrPros[$i]["bik"]);

		 			 }

		 		 }else{//以下余白がいる場合

		 				 for($i=0; $i<20; $i++){

		 					 if($i == count($arrPros)+1){
		 						 break;
		 					 }

		 					 $num = 17 + $i;

		 					 if($i < count($arrPros)){

								 $sheet->setCellValue("A".$num, $arrPros[$i]["pro"]);
			 					 $sheet->setCellValue("E".$num, $arrPros[$i]["amount"]);
			 					 $sheet->setCellValue("F".$num, $arrPros[$i]["tani"]);
			 					 $sheet->setCellValue("G".$num, $arrPros[$i]["tanka"]);
			 					 $sheet->setCellValue("H".$num, $arrPros[$i]["price"]);
			 					 $sheet->setCellValue("I".$num, $arrPros[$i]["bik"]);

		 					 }else{

								 $sheet->setCellValue("A".$num, "以下余白");

							 }

		 			 }

		 		 }

		 		 $writer = new XlsxWriter($spreadsheet);

		 		 for($j=2; $j<2+$syou; $j++){

		 			 $sheet = $spreadsheet->getSheetByName("Sheet".$j);
					 $sheet->setCellValue('H1', "No.".$SeikyuuId);
					 $sheet->setCellValue('A3', "〒 ".$yuubin);
					 $sheet->setCellValue('A4', $address);
					 $sheet->setCellValue('A6', $namehyouji);
					 $sheet->setCellValue('E6', $keisyou);
					 $sheet->setCellValue('G3', $dateexcl);
					 $sheet->setCellValue('D14', $totalkingaku*1.1);

		 			 for($i=20*($j - 1); $i<20*$j; $i++){

		 				 if($i == count($arrPros)+1){
		 					 break;
		 				 }

		 				 $num = 17 + $i - 20*($j - 1);

						 if($i < count($arrPros)){

							 $sheet->setCellValue("A".$num, $arrPros[$i]["pro"]);
							 $sheet->setCellValue("E".$num, $arrPros[$i]["amount"]);
							 $sheet->setCellValue("F".$num, $arrPros[$i]["tani"]);
							 $sheet->setCellValue("G".$num, $arrPros[$i]["tanka"]);
							 $sheet->setCellValue("H".$num, $arrPros[$i]["price"]);
							 $sheet->setCellValue("I".$num, $arrPros[$i]["bik"]);

						 }else{

							 $sheet->setCellValue("A".$num, "以下余白");

						 }

		 			 }

		 		 }

				 $writer = new XlsxWriter($spreadsheet);

		 			 $datetime = date('H時i分s秒出力', strtotime('+9hour'));
		 			 $year = date('Y', strtotime('+9hour'));
		 			 $month = date('m', strtotime('+9hour'));
		 			 $day = date('d', strtotime('+9hour'));

		 			 if(is_dir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/請求書/$year/$month/$day")){//ディレクトリが存在すればOK

		 				 $file_name = $namehyouji."_".$datetime.".xlsx";
		 				 $outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/請求書/$year/$month/$day/$file_name"; //出力したいファイルの指定

		 			 }else{//ディレクトリが存在しなければ作成する

		 				 mkdir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/請求書/$year/$month/$day", 0777, true);
		 				 $file_name = $namehyouji."_".$datetime.".xlsx";
		 				 $outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/請求書/$year/$month/$day/$file_name"; //出力したいファイルの指定

		 			 }

		 			 $mesxlsx = "「エクセル出力/請求書/".$year."/".$month."/".$day."」フォルダにエクセルシート「".$file_name."」が出力されました。";
		 			 $this->set('mesxlsx',$mesxlsx);

		 			 $writer->save($outfilepath);

						$seikyuu = $this->Seikyuus->patchEntity($seikyuus, $tourokuArr);
						$connection = ConnectionManager::get('default');//トランザクション1
		        // トランザクション開始2
		        $connection->begin();//トランザクション3
		        try {//トランザクション4
		          if ($this->Seikyuus->save($seikyuu)) {

								$Uriage = $this->Uriages->find('all', ['conditions' => ['customerId' => $data["id"], 'delete_flag' => 0, 'seikyuuId' => 0]])->toArray();
								$count = count($Uriage);

								$SeikyuusId = $this->Seikyuus->find('all', ['conditions' => ['customerId' => $data["id"], 'delete_flag' => 0]])->order(['	id' => 'desc'])->toArray();

								for ($k=0; $k<$count; $k++){

									$this->Uriages->updateAll(
										['seikyuuId' => $SeikyuusId[0]->id],
										['id'  => $Uriage[$k]->id]
									);

								}

								$Zandakas = $this->Zandakas->find('all', ['conditions' => ['customerId' => $data["id"], 'delete_flag' => 0]])->toArray();
								if(isset($Zandakas[0])){

									$this->Zandakas->updateAll(
										['zandaka' => $data["totalseikyuu"], 'koushinbi' =>  date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
										['id'  => $Zandakas[0]->id]
									);

								}else{

									$arrZandaka = array('customerId' => $data["id"], 'furigana' => $furigana, 'zandaka' => $data["totalseikyuu"], 'koushinbi' => date('Y-m-d', strtotime('+9hour')),
			 					 'delete_flag' => 0,'created_at' => date('Y-m-d H:i:s', strtotime('+9hour')));

									$Zandaka = $this->Zandakas->patchEntity($this->Zandakas->newEntity(), $arrZandaka);
									$this->Zandakas->save($Zandaka);

								}


								$Miseikyuus = $this->Miseikyuus->find('all', ['conditions' => ['customerId' => $data["id"], 'delete_flag' => 0]])->toArray();
		 					 if(isset($Miseikyuus[0])){

		 						 $this->Miseikyuus->updateAll(
		 							 ['miseikyuugaku' => 0, 'kousinbi' => date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
		 							 ['id'  => $Miseikyuus[0]->id]
		 						 );

		 					 }

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

				 public function seikyuurirekimenu()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);
				 }

				 public function seikyuurirekiseikyuuzumiform()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);
				 }

				 public function seikyuurirekiseikyuuzumiitiran()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);

					 $data = $this->request->getData();
/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
*/
					 $date_sta = $data['date_sta']['year']."-".$data['date_sta']['month']."-".$data['date_sta']['day'];
					 $date_fin = $data['date_fin']['year']."-".$data['date_fin']['month']."-".$data['date_fin']['day'];
					 $this->set('date_sta',$date_sta);
					 $this->set('date_fin',$date_fin);

					 $date_fin = strtotime($date_fin);

					 $Seikyuus = $this->Seikyuus->find()
					 ->where(['date_seikyuu >=' => $date_sta, 'date_seikyuu <=' => $date_fin, 'delete_flag' => 0])->order(["date_seikyuu"=>"ASC"])->toArray();
					 $this->set('Seikyuus',$Seikyuus);

					 $count = count($Seikyuus);

					 $totalkingaku = 0;
					 for ($k=0; $k<$count; $k++){
						 $totalkingaku = $totalkingaku + $Seikyuus[$k]->total_price;
					 }
					 $this->set('totalkingaku',$totalkingaku);
				 }

				 public function seikyuurirekimiseikyuuform()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);
				 }

				 public function seikyuurirekimiseikyuuitiran()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);

					 $data = $this->request->getData();
/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
*/
					 $date_sta = $data['date_sta']['year']."-".$data['date_sta']['month']."-".$data['date_sta']['day'];
					 $date_fin = $data['date_fin']['year']."-".$data['date_fin']['month']."-".$data['date_fin']['day'];
					 $this->set('date_sta',$date_sta);
					 $this->set('date_fin',$date_fin);

					 $date_fin = strtotime($date_fin);

					 $Miseikyuus = $this->Miseikyuus->find()
					 ->where(['miseikyuugaku >' => 0, 'delete_flag' => 0])->order(["furigana"=>"ASC"])->toArray();
					 $this->set('Miseikyuus',$Miseikyuus);

					 $count = count($Miseikyuus);

					 $totalkingaku = 0;
					 for ($k=0; $k<$count; $k++){
						 $totalkingaku = $totalkingaku + $Miseikyuus[$k]->miseikyuugaku;
					 }
					 $this->set('totalkingaku',$totalkingaku);
				 }

				 public function nyuukinminyuukinform()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);
				 }

				 public function nyuukinminyuukinitiran()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);

					 $data = $this->request->getData();
/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
*/
					 $date_sta = $data['date_sta']['year']."-".$data['date_sta']['month']."-".$data['date_sta']['day'];
					 $date_fin = $data['date_fin']['year']."-".$data['date_fin']['month']."-".$data['date_fin']['day'];
					 $this->set('date_sta',$date_sta);
					 $this->set('date_fin',$date_fin);

					 $date_fin = strtotime($date_fin);

					 $Zandakas = $this->Zandakas->find()
					 ->where(['zandaka >' => 0, 'delete_flag' => 0])->order(["furigana"=>"ASC"])->toArray();
					 $this->set('Zandakas',$Zandakas);

					 $count = count($Zandakas);

					 $totalkingaku = 0;
					 for ($k=0; $k<$count; $k++){
						 $totalkingaku = $totalkingaku + $Zandakas[$k]->zandaka;
					 }
					 $this->set('totalkingaku',$totalkingaku);
				 }

				 public function seikyuusyuuseiview()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);

					 $data = $this->request->getData();
					 $data = array_keys($data, '詳細');
					 $id = $data[0];
					 $this->set('id',$id);

					 $Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['id' => $id, 'delete_flag' => 0]])->order(['id' => 'desc'])->toArray();
					 if(isset($Seikyuus[0])){
						 $customerId = $Seikyuus[0]->customerId;
						 $date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
						 $this->set('date_seikyuu',$date_seikyuu);
						 $nyuukingaku = $Seikyuus[0]->nyuukingaku;
						 $this->set('nyuukingaku',$nyuukingaku);
						 $tyousei = $Seikyuus[0]->tyousei;
						 $this->set('tyousei',$tyousei);
						 $sousai = $Seikyuus[0]->sousai;
						 $this->set('sousai',$sousai);
						 $total_price = $Seikyuus[0]->total_price;
						 $this->set('total_price',$total_price);

						 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $customerId]])->toArray();
						 $name = $Customer[0]->name;
						 $siten = $Customer[0]->siten;
						 $namehyouji = $name." ".$siten;
						 $this->set('namehyouji',$namehyouji);
					 }

				 }

				 public function seikyuusyuuseidelete()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);

					 $data = $this->request->getData();
					 $id = $data["id"];

					 $Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['id' => $id, 'delete_flag' => 0]])->order(['id' => 'desc'])->toArray();
					 if(isset($Seikyuus[0])){
						 $customerId = $Seikyuus[0]->customerId;
						 $date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
						 $this->set('date_seikyuu',$date_seikyuu);
						 $nyuukingaku = $Seikyuus[0]->nyuukingaku;
						 $this->set('nyuukingaku',$nyuukingaku);
						 $tyousei = $Seikyuus[0]->tyousei;
						 $this->set('tyousei',$tyousei);
						 $sousai = $Seikyuus[0]->sousai;
						 $this->set('sousai',$sousai);
						 $total_price = $Seikyuus[0]->total_price;
						 $this->set('total_price',$total_price);

						 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $customerId]])->toArray();
						 $name = $Customer[0]->name;
						 $siten = $Customer[0]->siten;
						 $namehyouji = $name." ".$siten;
						 $this->set('namehyouji',$namehyouji);

			       $connection = ConnectionManager::get('default');//トランザクション1
			       // トランザクション開始2
			       $connection->begin();//トランザクション3
			       try {//トランザクション4
			         if ($this->Seikyuus->updateAll(['delete_flag' => 1],['id'  => $id])) {

			           $connection->commit();// コミット5

			         } else {

			           $this->Flash->error(__('This data could not be saved. Please, try again.'));
			           throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

			         }

			       } catch (Exception $e) {//トランザクション7
			       //ロールバック8
			         $connection->rollback();//トランザクション9
			       }//トランザクション10

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

					$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/納品書/${"file_name".$i}"; //出力したいファイルの指定

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

				$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/納品書/copytest.xlsx"; //出力したいファイルの指定

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

				$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/納品書/$file_name"; //出力したいファイルの指定

				$writer->save($outfilepath);
*/
     }

}
