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
			$this->Uriagemasters = TableRegistry::get('uriagemasters');
			$this->Uriagesyousais = TableRegistry::get('uriagesyousais');
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

				 if($data['num'] >= 2000){

					 $tuika = $data['num'] ;
	         $this->set('tuika',$tuika);

					 echo "<pre>";
					 print_r("2000行以上の登録は同時にできません。２回に分けて登録してください。");
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

					$i = 1;
					${"pro_".$i} = "";
					$this->set('pro_'.$i,${"pro_".$i});
					${"amount_".$i} = "";
					$this->set('amount_'.$i,${"amount_".$i});
					${"tani_".$i} = "";
					$this->set('tani_'.$i,${"tani_".$i});
					${"tanka_".$i} = "";
					$this->set('tanka_'.$i,${"tanka_".$i});
					${"bik_".$i} = "";
					$this->set('bik_'.$i,${"bik_".$i});

				}

				$zenkaicheck = 0;
				$this->set('zenkaicheck',$zenkaicheck);

				if(isset($data['zenkai'])){
					$zenkaicheck = 1;
					$this->set('zenkaicheck',$zenkaicheck);

 				 $Uriagemasters = $this->Uriagemasters->find()->where(['customerId' => $data["id"], 'delete_flag' => 0])->order(["created_at"=>"desc"])->toArray();
 				 if(isset($Uriagemasters[0])){
 					 $UriagemasterId = $Uriagemasters[0]->id;

 					 $Uriagesyousais = $this->Uriagesyousais->find()->where(['uriagemasterId' => $UriagemasterId, 'delete_flag' => 0])->order(["num"=>"asc"])->toArray();

 					 $tuika = count($Uriagesyousais);
 					 $this->set('tuika',$tuika);

 					 for($i=1; $i<=$tuika; $i++){

 						 ${"pro_".$i} = $Uriagesyousais[$i - 1]->pro;
 						 $this->set('pro_'.$i,${"pro_".$i});
						 ${"amount_".$i} = $Uriagesyousais[$i - 1]->amount;
	 					$this->set('amount_'.$i,${"amount_".$i});
	 					${"tani_".$i} = $Uriagesyousais[$i - 1]->tani;
	 					$this->set('tani_'.$i,${"tani_".$i});
	 					${"tanka_".$i} = $Uriagesyousais[$i - 1]->tanka;
	 					$this->set('tanka_'.$i,${"tanka_".$i});
	 					${"bik_".$i} = $Uriagesyousais[$i - 1]->bik;
	 					$this->set('bik_'.$i,${"bik_".$i});

 					 }

 				 }else{

 				 }

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
       $numpro = 0;

			 for($i=1; $i<=$data["num"]; $i++){

				 if(!empty($data["pro_".$i])){

					 $numpro = $numpro + 1;

					 $this->set('tuika',$numpro);

					 $this->set('pro_'.$numpro,$data["pro_".$i]);
					 $this->set('amount_'.$numpro,$data["amount_".$i]);
					 $this->set('tani_'.$numpro,$data["tani_".$i]);
					 $this->set('amount_'.$numpro,$data["amount_".$i]);
					 $this->set('tanka_'.$numpro,$data["tanka_".$i]);
					 $this->set('bik_'.$numpro,$data["bik_".$i]);

					 if((int)$data["tanka_".$i] > 0){
						 ${"price_".$numpro} = (int)$data["tanka_".$i] * (int)$data["amount_".$i];
					 }else{
						 ${"price_".$numpro} = "";
					 }
					 $this->set('price_'.$numpro,${"price_".$numpro});

				 }

			 }

     }

		 public function uriagesyuturyoku()
     {
			 $uriages = $this->Uriages->newEntity();
       $this->set('uriages',$uriages);

			 $data = $this->request->getData();

			 $Uriages = $this->Uriagemasters->find()->where(['delete_flag' => 0])->order(["denpyou_num"=>"desc"])->toArray();
			 if(isset($Uriages[0])){
				 $denpyou_num = $Uriages[0]->denpyou_num + 1;
			 }else{
				 $denpyou_num = 10000;
			 }

			 $tourokuArr = array();
			 $tourokusyousaiArr = array();

			 $tourokuArr = array('denpyou_num' => $denpyou_num,'customerId' => $data["id"],'customer' => $data["name"],'furigana' => $data["furigana"],'yuubin' => $data["yuubin"],'address' => $data["address"],'keisyou' => $data["keisyou"]
			 												,'uriagebi' => $data["datetouroku"],'delete_flag' => 0,'created_at' => date('Y-m-d H:i:s', strtotime('+9hour')));

			 $total_price = 0;
			 for($i=1; $i<=$data["tuika"]; $i++){
				 ${"tourokusyousaiArr_".$i} = array();

				 ${"arr_".$i} = array('pro' => $data["pro_".$i],'amount' => $data["amount_".$i],'tani' => $data["tani_".$i],'tanka' => $data["tanka_".$i],
															 'price' => $data["price_".$i],'bik' => $data["bik_".$i]);

										//					 ${"arr_".$i} = array('pro_'.$i => $data["pro_".$i],'amount_'.$i => $data["amount_".$i],'tani_'.$i => $data["tani_".$i],'tanka_'.$i => $data["tanka_".$i],
										//																 'price_'.$i => $data["price_".$i],'bik_'.$i => $data["bik_".$i]);

				 ${"tourokusyousaiArr_".$i} = array_merge(${"tourokusyousaiArr_".$i},${"arr_".$i});

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
			 for($i=1; $i<=$data["tuika"]; $i++){

				 echo "<pre>";
				 print_r(${"tourokusyousaiArr_".$i});
				 echo "</pre>";

			 }
*/
			 //データベース登録
 			 $uriagemaster = $this->Uriagemasters->patchEntity($uriages, $tourokuArr);
        $connection = ConnectionManager::get('default');//トランザクション1
        // トランザクション開始2
        $connection->begin();//トランザクション3
        try {//トランザクション4
          if ($this->Uriagemasters->save($uriagemaster)) {

						$Uriagemasters = $this->Uriagemasters->find('all', ['conditions' => ['denpyou_num' => $denpyou_num, 'delete_flag' => 0]])
						->order(["id"=>"desc"])->toArray();
						$uriagemasterId = $Uriagemasters[0]->id;

						for($i=1; $i<=$data["tuika"]; $i++){

							${"tuikatouroku".$i} = array('num' => $i, 'uriagemasterId' => $uriagemasterId, 'uriagebi' => $data["datetouroku"], 'delete_flag' => 0, 'created_at' => date('Y-m-d H:i:s', strtotime('+9hour')));

							${"tourokusyousaiArr_".$i} = array_merge(${"tourokusyousaiArr_".$i},${"tuikatouroku".$i});
							/*

							echo "<pre>";
							print_r(${"tourokusyousaiArr_".$i});
							echo "</pre>";
							*/
							$uriagesyousai = $this->Uriagesyousais->patchEntity($this->Uriagesyousais->newEntity(), ${"tourokusyousaiArr_".$i});
							if ($this->Uriagesyousais->save($uriagesyousai)) {

							}else{
		            $this->Flash->error(__('This data could not be saved. Please, try again.'));
		            throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
							}

		 			 }

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

/*
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
*/
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

			 $pronamecheck = 0;
  //     $date_fin = date('Y-m-d', strtotime('+1 day', $date_fin));

					 if(empty($data['denpyou_num'])){//denpyou_numの入力がないとき

						 if(empty($data['furigana'])){//furiganaの入力がないとき

							 if(empty($data['customer'])){//customerの入力がないとき

								 if(empty($data['proname'])){//pronameの入力がないとき

									 $Uriages = $this->Uriagemasters->find()
									 ->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'delete_flag' => 0])->order(["uriagebi"=>"ASC"])->toArray();
									 $this->set('Uriages',$Uriages);
/*
									 echo "<pre>";
						       print_r($Uriages[0]->id);
						       echo "</pre>";
*/
								 }else{//pronameの入力があるとき pronameと日にちで絞り込み

									 $pronamecheck = 1;
/*
									 $Uriages = $this->Uriages->find()
									->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'delete_flag' => 0,
									'OR' => [['pro_1 like' => '%'.$proname.'%'], ['pro_2' => '%'.$proname.'%'], ['pro_3' => '%'.$proname.'%'], ['pro_4 like' => '%'.$proname.'%'],
									['pro_5 like' => '%'.$proname.'%'], ['pro_6 like' => '%'.$proname.'%'], ['pro_7 like' => '%'.$proname.'%'], ['pro_8 like' => '%'.$proname.'%'],
									['pro_9 like' => '%'.$proname.'%'], ['pro_10 like' => '%'.$proname.'%'], ['pro_11 like' => '%'.$proname.'%'], ['pro_12 like' => '%'.$proname.'%'],
									['pro_13 like' => '%'.$proname.'%'], ['pro_14 like' => '%'.$proname.'%'], ['pro_15 like' => '%'.$proname.'%'], ['pro_16 like' => '%'.$proname.'%'],
									['pro_17 like' => '%'.$proname.'%'], ['pro_18 like' => '%'.$proname.'%'], ['pro_19 like' => '%'.$proname.'%'], ['pro_20 like' => '%'.$proname.'%']]])
									->order(["syutsuryokubi"=>"ASC"])->toArray();
									$this->set('Uriages',$Uriages);
*/
									$Uriages = $this->Uriagesyousais->find()
									->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'delete_flag' => 0,'pro like' => '%'.$proname.'%'])
									->order(["uriagebi"=>"ASC"])->toArray();
									$this->set('Uriages',$Uriages);
									$pronamecheck = 1;

								}

							}else{//customerの入力があるとき

								if(empty($data['proname'])){//pronameの入力がないとき

						//			$Uriages = $this->Uriages->find()
					//				->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin,  'customer like' => '%'.$customer.'%', 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
						//			$this->set('Uriages',$Uriages);

									$Uriages = $this->Uriagemasters->find()
									->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin,  'customer like' => '%'.$customer.'%',  'delete_flag' => 0])->order(["uriagebi"=>"ASC"])->toArray();
									$this->set('Uriages',$Uriages);

								}else{//pronameの入力があるときpronameとcustomerと日にちで絞り込み

									$Uriages = $this->Uriagesyousais->find()
									->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'delete_flag' => 0,'pro like' => '%'.$proname.'%'])
									->order(["uriagebi"=>"ASC"])->toArray();
									$this->set('Uriages',$Uriages);
									$pronamecheck = 1;

							 }

						 }

					 }else{

						 if(empty($data['customer'])){//customerの入力がないとき

							 if(empty($data['proname'])){//pronameの入力がないとき

					//			 $Uriages = $this->Uriages->find()
					//			 ->where(['furigana like' => '%'.$furigana.'%', 'syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
					//			 $this->set('Uriages',$Uriages);

								 $Uriages = $this->Uriagemasters->find()
								 ->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin,  'furigana like' => '%'.$furigana.'%',  'delete_flag' => 0])->order(["uriagebi"=>"ASC"])->toArray();
								 $this->set('Uriages',$Uriages);

							 }else{//pronameの入力があるとき pronameと日にちで絞り込み

								 $Uriages = $this->Uriagesyousais->find()
								 ->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'delete_flag' => 0,'pro like' => '%'.$proname.'%'])
								 ->order(["uriagebi"=>"ASC"])->toArray();
								 $this->set('Uriages',$Uriages);
								 $pronamecheck = 1;

							}

						}else{//customerの入力があるとき

							if(empty($data['proname'])){//pronameの入力がないとき

				//				$Uriages = $this->Uriages->find()
				//				->where(['furigana like' => '%'.$furigana.'%', 'syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin,  'customer like' => '%'.$customer.'%', 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
				//				$this->set('Uriages',$Uriages);

								$Uriages = $this->Uriagemasters->find()
								->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin,  'furigana like' => '%'.$furigana.'%',  'customer like' => '%'.$customer.'%',  'delete_flag' => 0])->order(["uriagebi"=>"ASC"])->toArray();
								$this->set('Uriages',$Uriages);

							}else{//pronameの入力があるときpronameとcustomerと日にちで絞り込み

								$Uriages = $this->Uriagesyousais->find()
								->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'delete_flag' => 0,'pro like' => '%'.$proname.'%'])
								->order(["uriagebi"=>"ASC"])->toArray();
								$this->set('Uriages',$Uriages);
								$pronamecheck = 1;

						 }

					 }

				 }

			 }else{

						 if(empty($data['furigana'])){//furiganaの入力がないとき

							 if(empty($data['customer'])){//customerの入力がないとき

								 if(empty($data['proname'])){//pronameの入力がないとき

						//			 $Uriages = $this->Uriages->find()
						//			 ->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'denpyou_num' => $denpyou_num, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
						//			 $this->set('Uriages',$Uriages);

									 $Uriages = $this->Uriagemasters->find()
									 ->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'denpyou_num' => $denpyou_num,  'delete_flag' => 0])->order(["uriagebi"=>"ASC"])->toArray();
									 $this->set('Uriages',$Uriages);

								 }else{//pronameの入力があるとき pronameと日にちで絞り込み

									 $Uriages = $this->Uriagesyousais->find()
 									->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'delete_flag' => 0,'pro like' => '%'.$proname.'%'])
 									->order(["uriagebi"=>"ASC"])->toArray();
 									$this->set('Uriages',$Uriages);
 									$pronamecheck = 1;

								}

							}else{//customerの入力があるとき

								if(empty($data['proname'])){//pronameの入力がないとき

					//				$Uriages = $this->Uriages->find()
					//				->where(['syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin,  'customer like' => '%'.$customer.'%', 'denpyou_num' => $denpyou_num, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
					//				$this->set('Uriages',$Uriages);

									$Uriages = $this->Uriagemasters->find()
									->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin ,  'customer like' => '%'.$customer.'%', 'denpyou_num' => $denpyou_num,  'delete_flag' => 0])->order(["uriagebi"=>"ASC"])->toArray();
									$this->set('Uriages',$Uriages);

								}else{//pronameの入力があるときpronameとcustomerと日にちで絞り込み

									$Uriages = $this->Uriagesyousais->find()
									->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'delete_flag' => 0,'pro like' => '%'.$proname.'%'])
									->order(["uriagebi"=>"ASC"])->toArray();
									$this->set('Uriages',$Uriages);
									$pronamecheck = 1;

							 }

						 }

					 }else{

						 if(empty($data['customer'])){//customerの入力がないとき

							 if(empty($data['proname'])){//pronameの入力がないとき

						//		 $Uriages = $this->Uriages->find()
						//		 ->where(['furigana like' => '%'.$furigana.'%', 'syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin, 'denpyou_num' => $denpyou_num, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
						//		 $this->set('Uriages',$Uriages);

								 $Uriages = $this->Uriagemasters->find()
								 ->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin,  'furigana like' => '%'.$furigana.'%', 'denpyou_num' => $denpyou_num,  'delete_flag' => 0])->order(["uriagebi"=>"ASC"])->toArray();
								 $this->set('Uriages',$Uriages);

							 }else{//pronameの入力があるとき pronameと日にちで絞り込み

								 $Uriages = $this->Uriagesyousais->find()
								 ->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'delete_flag' => 0,'pro like' => '%'.$proname.'%'])
								 ->order(["uriagebi"=>"ASC"])->toArray();
								 $this->set('Uriages',$Uriages);
								 $pronamecheck = 1;

							}

						}else{//customerの入力があるとき

							if(empty($data['proname'])){//pronameの入力がないとき

					//			$Uriages = $this->Uriages->find()
					//			->where(['furigana like' => '%'.$furigana.'%', 'syutsuryokubi >=' => $date_sta, 'syutsuryokubi <=' => $date_fin,  'customer like' => '%'.$customer.'%', 'denpyou_num' => $denpyou_num, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
					//			$this->set('Uriages',$Uriages);

								$Uriages = $this->Uriagemasters->find()
								->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin,  'furigana like' => '%'.$furigana.'%', 'denpyou_num' => $denpyou_num,  'customer like' => '%'.$customer.'%',  'delete_flag' => 0])->order(["uriagebi"=>"ASC"])->toArray();
								$this->set('Uriages',$Uriages);

							}else{//pronameの入力があるときpronameとcustomerと日にちで絞り込み

								$Uriages = $this->Uriagesyousais->find()
								->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'delete_flag' => 0,'pro like' => '%'.$proname.'%'])
								->order(["uriagebi"=>"ASC"])->toArray();
								$this->set('Uriages',$Uriages);
								$pronamecheck = 1;

						 }

					 }

				 }

			 }

			 $this->set('pronamecheck',$pronamecheck);

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
/*
		     public function uriagekensakusyousai()
		     {
					 $uriages = $this->Uriages->newEntity();
		       $this->set('uriages',$uriages);

		       $data = $this->request->getData();
					 echo "<pre>";
		       print_r($data);
		       echo "</pre>";

		       $data = array_keys($data, '修正');
					 $id = $data[0];
					 $this->set('id',$id);

					 echo "<pre>";
		       print_r($id);
		       echo "</pre>";

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
*/
				 public function uriagekensakuedit()
		     {
					 $uriages = $this->Uriages->newEntity();
		       $this->set('uriages',$uriages);

					 $data = $this->request->getData();

		       $data = array_keys($data, '修正');
					 $UriagemastersId = $data[0];
					 $this->set('id',$UriagemastersId);
					 echo "<pre>";
		       print_r($UriagemastersId);
		       echo "</pre>";

		       $Uriagesyousais = $this->Uriagesyousais->find()->where(['uriagemasterId' => $UriagemastersId, 'delete_flag' => 0])->order(["num"=>"ASC"])->toArray();
					 $this->set('Uriagesyousais',$Uriagesyousais);

					 $syutsuryokubisyousai = $Uriagesyousais[0]["uriagebi"]->format('Y-m-d');
					 $this->set('syutsuryokubisyousai',$syutsuryokubisyousai);
/*
					 echo "<pre>";
		       print_r($UriagemastersId);
		       echo "</pre>";
*/
					 $Uriagemasters = $this->Uriagemasters->find()->where(['id' => $UriagemastersId])->toArray();

		       $syutsuryokubi = $Uriagemasters[0]["uriagebi"]->format('Y年m月d日');
					 $this->set('syutsuryokubi',$syutsuryokubi);

					 $customer = $Uriagemasters[0]["customer"];
		       $this->set('customer',$customer);
					 $yuubin = $Uriagemasters[0]["yuubin"];
		       $this->set('yuubin',$yuubin);
					 $address = $Uriagemasters[0]["address"];
		       $this->set('address',$address);
					 $keisyou = $Uriagemasters[0]["keisyou"];
		       $this->set('keisyou',$keisyou);

					 $count = 1;
					 $this->set('count',$count);



		     }

				 public function uriagekensakueditdo()
		     {
					 $uriages = $this->Uriages->newEntity();
		       $this->set('uriages',$uriages);

					 $data = $this->request->getData();
					 $UriagemastersId = $data["id"];
					 $this->set('id',$UriagemastersId);
/*
					 echo "<pre>";
		       print_r($data);
		       echo "</pre>";
*/
					 $total_price_moto = $data["Uriagetotalmoto"];

					 $seikyuubi = $data['syutsuryokubi']['year']."-".$data['syutsuryokubi']['month']."-".$data['syutsuryokubi']['day'];

					 if($data["delete_flag_all"] == 1){//alldeleteの場合
		         $mess = "以下のデータを削除しました。";
						 $this->set('mess',$mess);

						 $Uriagemasters = $this->Uriagemasters->patchEntity($this->Uriagemasters->newEntity(), $data);
			       $connection = ConnectionManager::get('default');//トランザクション1
			       // トランザクション開始2
			       $connection->begin();//トランザクション3
			       try {//トランザクション4
			         if ($this->Uriagemasters->updateAll(
			           ['delete_flag' => 1],
			           ['id'  => $data['id']]
			         )){

	//seikyuuId=0の場合はmiseikyuuの金額もupdate
  									$Uriagemasters = $this->Uriagemasters->find()->where(['id' => $data['id'], 'seikyuuId' => 0])->toArray();

										if(isset($Uriagemasters[0])){
/*
											echo "<pre>";
											print_r($Uriagemasters[0]);
											echo "</pre>";
*/
											$Miseikyuus = $this->Miseikyuus->find('all', ['conditions' => ['customerId' => $Uriagemasters[0]->customerId, 'delete_flag' => 0]])->toArray();
											if(isset($Miseikyuus[0])){

												$Uriagesyousais = $this->Uriagesyousais->find()
												->where([ 'uriagemasterId' => $UriagemastersId, 'delete_flag' => 0])->toArray();

						            $Uriagetotalmaster = 0;

						            for($i=0; $i<count($Uriagesyousais); $i++){

						              if(!empty($Uriagesyousais[$i]->price)){

						                $Uriagetotalmaster = $Uriagetotalmaster + $Uriagesyousais[$i]->price;

						              }

						            }
						            $total_price = $Uriagetotalmaster * 1.1;

 		 									 $miseikyuugaku = $Miseikyuus[0]->miseikyuugaku - $total_price;
/*
 		 									 echo "<pre>";
 		 									 print_r($Miseikyuus[0]->miseikyuugaku." - ".$total_price." = ".$miseikyuugaku);
 		 									 echo "</pre>";
*/
 		 									 $this->Miseikyuus->updateAll(
 		 										 ['miseikyuugaku' => $miseikyuugaku, 'kousinbi' => date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
 		 										 ['id'  => $Miseikyuus[0]->id]
 		 									 );

 		 								 }

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

		       }else{//alldeleteではない場合

		         $mess = "以下のように更新しました。";
						 $this->set('mess',$mess);

						 $Uriagemasters = $this->Uriagemasters->patchEntity($this->Uriagemasters->newEntity(), $data);
			       $connection = ConnectionManager::get('default');//トランザクション1
			       // トランザクション開始2
			       $connection->begin();//トランザクション3
			       try {//トランザクション4
			         if ($this->Uriagemasters->updateAll(
			           ['uriagebi' => $seikyuubi, 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
			           ['id'  => $data['id']]
			         )){
/*
								 echo "<pre>";
								 print_r("Uriagemasters");
								 echo "</pre>";
*/
								 for($i=0; $i<=$data['num']; $i++){
/*
									 echo "<pre>";
									 print_r($i." ".$data['delete_flag'.$i]." ".$data['uriagesyousaiId'.$i]);
									 echo "</pre>";
*/
									 $this->Uriagesyousais->updateAll(
									 	['pro' =>  $data['pro_'.$i], 'amount' =>  $data['amount_'.$i], 'tani' =>  $data['tani_'.$i],
										'tanka' =>  $data['tanka_'.$i], 'price' =>  $data['tanka_'.$i]*$data['amount_'.$i], 'bik' =>  $data['bik_'.$i], 'delete_flag' =>  $data['delete_flag'.$i]],
									 	['id'  => $data['uriagesyousaiId'.$i]]
									);

								 }

	//seikyuuId=0の場合はmiseikyuuの金額もupdate
  									$Uriagemasters = $this->Uriagemasters->find()->where(['id' => $data['id'], 'seikyuuId' => 0])->toArray();

										if(isset($Uriagemasters[0])){

											$Miseikyuus = $this->Miseikyuus->find('all', ['conditions' => ['customerId' => $Uriagemasters[0]->customerId, 'delete_flag' => 0]])->toArray();
											if(isset($Miseikyuus[0])){

												$Uriagesyousais = $this->Uriagesyousais->find()
						            ->where([ 'uriagemasterId' => $UriagemastersId, 'delete_flag' => 0])->toArray();

						            $Uriagetotalmaster = 0;

						            for($i=0; $i<count($Uriagesyousais); $i++){

						              if(!empty($Uriagesyousais[$i]->price)){

						                $Uriagetotalmaster = $Uriagetotalmaster + $Uriagesyousais[$i]->price;

						              }

						            }
						            $Uriagetotal = $Uriagetotalmaster * 1.1;

 		 									 $miseikyuugaku = $Miseikyuus[0]->miseikyuugaku - $data['Uriagetotalmoto'] + $Uriagetotal;
/*
 		 									 echo "<pre>";
 		 									 print_r($Miseikyuus[0]->miseikyuugaku." - ".$data['Uriagetotalmoto']." + ".$Uriagetotal." = ".$miseikyuugaku);
 		 									 echo "</pre>";
*/
 		 									 $this->Miseikyuus->updateAll(
 		 										 ['miseikyuugaku' => $miseikyuugaku, 'kousinbi' => date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
 		 										 ['id'  => $Miseikyuus[0]->id]
 		 									 );

 		 								 }

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
/*
					 $uriage = $this->Uriagesyousais->patchEntity($uriages, $data);
		       $connection = ConnectionManager::get('default');//トランザクション1
		       // トランザクション開始2
		       $connection->begin();//トランザクション3
		       try {//トランザクション4
		         if ($this->Uriagesyousais->updateAll(
		           [
							 'pro' => $data['pro_0'], 'tani' => $data['tani_0'], 'amount' => $data['amount_0'], 'tanka' => $data['tanka_0'],
							 'price' => $data['amount_0']*$data['tanka_0'], 'bik' => $data['bik_0'], 'uriagebi' => $seikyuubi, 'delete_flag' => $data['delete_flag']],
		           ['id'  => $data['id']]
		         )){

//seikyuuId=0の場合はmiseikyuuの金額もupdate
/*
							 $Miseikyuus = $this->Miseikyuus->find('all', ['conditions' => ['customerId' => $customerId, 'delete_flag' => 0]])->toArray();
							 if(isset($Miseikyuus[0])){

								 $miseikyuugaku = $Miseikyuus[0]->miseikyuugaku - $total_price_moto + $total_price;

					//			 echo "<pre>";
					//			 print_r($Miseikyuus[0]->miseikyuugaku." - ".$total_price_moto." + ".$total_price." = ".$miseikyuugaku);
					//			 echo "</pre>";

								 $this->Miseikyuus->updateAll(
									 ['miseikyuugaku' => $miseikyuugaku, 'kousinbi' => date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
									 ['id'  => $Miseikyuus[0]->id]
								 );

							 }
*//*
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
*/

					 $Uriagemasters = $this->Uriagemasters->find()->where(['id' => $UriagemastersId])->toArray();
					 $syutsuryokubi = $Uriagemasters[0]["uriagebi"]->format('Y年m月d日');
					 $this->set('syutsuryokubi',$syutsuryokubi);

					 $customer = $Uriagemasters[0]["customer"];
		       $this->set('customer',$customer);
					 $yuubin = $Uriagemasters[0]["yuubin"];
		       $this->set('yuubin',$yuubin);
					 $address = $Uriagemasters[0]["address"];
		       $this->set('address',$address);
					 $keisyou = $Uriagemasters[0]["keisyou"];
		       $this->set('keisyou',$keisyou);

					 $Uriagesyousais = $this->Uriagesyousais->find()
					 ->where([ 'uriagemasterId' => $UriagemastersId, 'delete_flag' => 0])->order(["num"=>"ASC"])->toArray();
					 $this->set('Uriagesyousais',$Uriagesyousais);

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

				 public function urikakeitiranform()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);

					 $year = date('Y');
					 $this->set('year',$year);
					 for($n=2010; $n<=$year; $n++){

						 $arrYear[$n] = $n;

		        }
					 $this->set('arrYear',$arrYear);


					for($n=1; $n<=12; $n++){

						$arrMonth[$n] = $n;

					 }
					 $this->set('arrMonth',$arrMonth);

				 }

				 public function urikakeitiran()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);

					 $data = $this->request->getData();

					 if($data['date_sta_m'] == 12){
						 $date_fin_m = 1;
						 $date_fin_y = $data['date_sta_y'] + 1;
					 }else{
						 $date_fin_m = $data['date_sta_m'] + 1;
						 $date_fin_y = $data['date_sta_y'];
					 }

					 $date_sta = $data['date_sta_y']."-".$data['date_sta_m']."-1";
					 $date_fin = $date_fin_y."-".$date_fin_m."-1";
					 $this->set('date_y',$data['date_sta_y']);
					 $this->set('date_m',$data['date_sta_m']);

					 $date_sta = strtotime($date_sta);
					 $date_fin = strtotime($date_fin);

					 $Seikyuus = $this->Seikyuus->find()
					 ->where(['date_seikyuu >=' => $date_sta, 'date_seikyuu <=' => $date_fin, 'delete_flag' => 0])->order(["date_seikyuu"=>"ASC"])->toArray();
					 $this->set('Seikyuus',$Seikyuus);

					 $count = count($Seikyuus);

					 $arrSeikyuu = array();
					 $totalkingaku = 0;
					 $kurikosi = 0;
					 $totalkurikosi = 0;
					 $totalnyuukin = 0;
					 $totalzandaka = 0;

					 for ($k=0; $k<$count; $k++){

						 $Customer = $this->Customers->find('all', ['conditions' => ['id' => $Seikyuus[$k]->customerId]])->toArray();
						 $customername = $Customer[0]->name." ".$Customer[0]->siten;

						 $bik = $Seikyuus[$k]->bik;

						 //seikyuuId=$Seikyuus[$k]->idの売上のトータルを請求額とする

						 $Uriagemasters = $this->Uriagemasters->find()->where(['seikyuuId' => $Seikyuus[$k]->id])->toArray();

						 if(isset($Uriagemasters[0])){
							 $Uriagesyousais = $this->Uriagesyousais->find()
							 ->where([ 'uriagemasterId' => $Uriagemasters[0]->id, 'delete_flag' => 0])->toArray();

							 $Uriagetotalmaster = 0;

							 for($i=0; $i<count($Uriagesyousais); $i++){

								 if(!empty($Uriagesyousais[$i]->price)){

									 $Uriagetotalmaster = $Uriagetotalmaster + $Uriagesyousais[$i]->price;

								 }

							 }
							 $uriagetotal = $Uriagetotalmaster * 1.1;

						 }

						 //$Seikyuus[$k]->total_price - 売上のトータル = 繰越金額
						 $kurikosi = round($Seikyuus[$k]->total_price - $uriagetotal);
						 if($kurikosi < 1){
							 $kurikosi = 0;
						 }
						 echo "<pre>";
						 print_r($Seikyuus[$k]->id."  ".$Uriagemasters[0]->id."  ".$kurikosi." = ".$Seikyuus[$k]->total_price." - ".$uriagetotal);
						 echo "</pre>";

						 $nyuukin_flag = $Customer[0]->nyuukin_flag	;
						 if($nyuukin_flag == 1 && $Customer[0]->nyuukinbi > 0){
							 $nyuukinmonth = "当月";
							 $customernyuukinbi = $nyuukinmonth.$Customer[0]->nyuukinbi."日";
						 }elseif($nyuukin_flag == 2 && $Customer[0]->nyuukinbi > 0){
							 $nyuukinmonth = "翌月";
							 $customernyuukinbi = $nyuukinmonth.$Customer[0]->nyuukinbi."日";
						 }elseif($nyuukin_flag == 3 && $Customer[0]->nyuukinbi > 0){
							 $nyuukinmonth = "翌々月";
							 $customernyuukinbi = $nyuukinmonth.$Customer[0]->nyuukinbi."日";
						 }else{
							 $customernyuukinbi = "";
						 }

						 $Zandaka = $this->Zandakas->find('all', ['conditions' => ['customerId' => $Seikyuus[$k]->customerId]])->order(["created_at"=>"DESC"])->toArray();
						 $zandaka = $Zandaka[0]->zandaka;

						 $nyuukinngaku = 0;
						 $nyuukinbi = "";
						 $nyuukin = 0;
						 $sousai = 0;
						 $tyousei = 0;
						 $syubetu = "";
						 $kogite = "";
						 $kogiteday = "";
						 $kogitetotal = 0;

						 $Nyuukins = $this->Nyuukins->find('all', ['conditions' => ['customerId' => $Seikyuus[$k]->customerId, 'datenyuukin >=' => $Seikyuus[$k]->date_seikyuu]])->order(["created_at"=>"DESC"])->toArray();
						 if(isset($Nyuukins[0])){

							 for ($l=0; $l<count($Nyuukins); $l++){

								 $nyuukinngaku = $nyuukinngaku + $Nyuukins[$l]->nyuukinngaku;
								 $nyuukinbi = $Nyuukins[$l]->datenyuukin->format('m/d');

								 if($Nyuukins[$l]->syubetu === "相殺"){

									 $sousai = $sousai + $Nyuukins[$l]->nyuukinngaku;

								 }elseif($Nyuukins[$l]->syubetu === "調整"){

									 $tyousei = $tyousei + $Nyuukins[$l]->nyuukinngaku;

								 }elseif($Nyuukins[$l]->syubetu === "小切手"){

									 $nyuukin = $nyuukin + $Nyuukins[$l]->nyuukinngaku;
									 $kogite = "小切手";
									 $kogiteday = $Nyuukins[$l]->datenyuukin->format('m/d');
									 $nyuukin = $nyuukin - $Nyuukins[$l]->nyuukinngaku;
									 $kogitetotal = $kogitetotal + $Nyuukins[$l]->nyuukinngaku;

								 }else{

									 $nyuukin = $nyuukin + $Nyuukins[$l]->nyuukinngaku;
									 $syubetu = $syubetu." ".$Nyuukins[$l]->syubetu;

								 }

							 }

							 $zandaka = $Seikyuus[$k]->total_price - $nyuukinngaku;

						 }else{
							 $nyuukinngaku = 0;
							 $nyuukin = 0;
							 $sousai = 0;
							 $tyousei = 0;
						 }

						 $arrSeikyuu[] = array(
							 "customer" => $customername,
							 "customernyuukinbi" => $customernyuukinbi,
							 "syutsuryokubi" => $Seikyuus[$k]->date_seikyuu->format('m/d'),
							 "kurikosi" => $kurikosi,
							 "nyuukingaku" => $nyuukin,
							 "seikyuugaku" => $Seikyuus[$k]->total_price,
							 "nyuukinbi" => $nyuukinbi,
							 "sousai" => $sousai,
							 "tyousei" => $tyousei,
							 "syubetu" => $syubetu,
							 "zandaka" => $zandaka,
							 "bik" => $bik,
							 "kogite" => $kogite,
							 "kogiteday" => $kogiteday,
							 "kogitetotal" => $kogitetotal,
						 );

						 $totalkingaku = $totalkingaku + $Seikyuus[$k]->total_price;

						 $totalzandaka = $totalzandaka + $arrSeikyuu[$k]["zandaka"];
						 $totalkurikosi = $totalkurikosi + $arrSeikyuu[$k]["kurikosi"];
						 $totalnyuukin = $totalnyuukin + $arrSeikyuu[$k]["nyuukingaku"];

					 }
					 $this->set('totalkingaku',$totalkingaku);
					 $this->set('totalkurikosi',$totalkurikosi);
					 $this->set('totalnyuukin',$totalnyuukin);
					 $this->set('totalzandaka',$totalzandaka);
/*
					 echo "<pre>";
					 print_r($arrSeikyuu);
					 echo "</pre>";
*/
					$mesxlsx = "";
					$this->set('mesxlsx',$mesxlsx);
					$this->set('arrSeikyuu',$arrSeikyuu);


					 if(!empty($data["excel"])){

						 echo "<pre>";
						 print_r("excel");
						 echo "</pre>";

						 //エクセル出力
 		 			 $filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\売掛一覧.xlsx'; //読み込みたいファイルの指定
					 $reader = new XlsxReader();
					 $spreadsheet = $reader->load($filepath);
						$sheet = $spreadsheet->getSheetByName("Sheet1");

						$num = 2;
	  		 		 for($j=0; $j<=count($arrSeikyuu); $j++){

							 if($j < count($arrSeikyuu)){
								 $sheet->setCellValue("A".$num, $arrSeikyuu[$j]["syutsuryokubi"]);
								 $sheet->setCellValue("B".$num, $arrSeikyuu[$j]["customer"]);
								 if($arrSeikyuu[$j]["kurikosi"] > 0){
									 $sheet->setCellValue("C".$num, $arrSeikyuu[$j]["kurikosi"]);
								 }
								 $sheet->setCellValue("D".$num, $arrSeikyuu[$j]["seikyuugaku"]);
								 $sheet->setCellValue("E".$num, $arrSeikyuu[$j]["nyuukinbi"]);

								 $num = $num + 1;
							 }else{
								 $sheet->setCellValue("A".$num, "合計");
								 $sheet->setCellValue("D".$num, $totalkingaku);

							 }

	  		 		 }

	  				 $writer = new XlsxWriter($spreadsheet);

 		 			 $datetime = date('H時i分s秒出力', strtotime('+9hour'));
 		 			 $year = date('Y', strtotime('+9hour'));
 		 			 $month = date('m', strtotime('+9hour'));
 		 			 $day = date('d', strtotime('+9hour'));
					 $date_m = $data['date_sta_m'];
					 $date_y = $data['date_sta_y'];

 		 			 if(is_dir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/売掛一覧/$year/$month/$day")){//ディレクトリが存在すればOK

 		 				 $file_name = "売掛_".$date_y."年".$date_m."月分_".$datetime.".xlsx";
 		 				 $outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/売掛一覧/$year/$month/$day/$file_name"; //出力したいファイルの指定

 		 			 }else{//ディレクトリが存在しなければ作成する

 		 				 mkdir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/売掛一覧/$year/$month/$day", 0777, true);
						 $file_name = "売掛_".$date_y."年".$date_m."月分_".$datetime.".xlsx";
 		 				 $outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/売掛一覧/$year/$month/$day/$file_name"; //出力したいファイルの指定

 		 			 }

 		 			 $mesxlsx = "「エクセル出力/売掛一覧/".$year."/".$month."/".$day."」フォルダにエクセルシート「".$file_name."」が出力されました。";
 		 			 $this->set('mesxlsx',$mesxlsx);

 		 			 $writer->save($outfilepath);

					 }

				 }

				 public function nyuukinsyoukaiseikyuuform()
		     {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);

					 $year = date('Y');
					 $this->set('year',$year);
					 for($n=2010; $n<=$year; $n++){

						 $arrYear[$n] = $n;

		        }
					 $this->set('arrYear',$arrYear);


					for($n=1; $n<=12; $n++){

						$arrMonth[$n] = $n;

					 }
					 $this->set('arrMonth',$arrMonth);

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
					 if($data['date_sta_m'] == 12){
						 $date_fin_m = 1;
						 $date_fin_y = $data['date_sta_y'] + 1;
					 }else{
						 $date_fin_m = $data['date_sta_m'] + 1;
						 $date_fin_y = $data['date_sta_y'];
					 }

					 $date_sta = $data['date_sta_y']."-".$data['date_sta_m']."-1";
					 $date_fin = $date_fin_y."-".$date_fin_m."-1";
					 $this->set('date_y',$data['date_sta_y']);
					 $this->set('date_m',$data['date_sta_m']);

					 $date_sta = strtotime($date_sta);
					 $date_fin = strtotime($date_fin);

					 $Nyuukins = $this->Nyuukins->find()
					 ->where(['datenyuukin >=' => $date_sta, 'datenyuukin <' => $date_fin, 'delete_flag' => 0])->order(["dateseikyuu"=>"ASC"])->toArray();
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
						 $hittyakubi = $Customer[0]->hittyakubi;
						 $this->set('hittyakubi',$hittyakubi);
						 $nyuukinbi = $Customer[0]->nyuukinbi;
						 $this->set('nyuukinbi',$nyuukinbi);
						 $kaisyuu = $Customer[0]->kaisyuu;
						 if($kaisyuu == 1){
							 $kaisyuu = "振込";
						 }else{
							 $kaisyuu = "集金";
						 }
						 $this->set('kaisyuu',$kaisyuu);
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
						 $hittyakubi = $Customer[0]->hittyakubi;
						 $this->set('hittyakubi',$hittyakubi);
						 $nyuukinbi = $Customer[0]->nyuukinbi;
						 $this->set('nyuukinbi',$nyuukinbi);
						 $kaisyuu = $Customer[0]->kaisyuu;
						 if($kaisyuu == 1){
							 $kaisyuu = "振込";
						 }else{
							 $kaisyuu = "集金";
						 }
						 $this->set('kaisyuu',$kaisyuu);
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
						 $hittyakubi = $Customer[0]->hittyakubi;
						 $this->set('hittyakubi',$hittyakubi);
						 $nyuukinbi = $Customer[0]->nyuukinbi;
						 $this->set('nyuukinbi',$nyuukinbi);
						 $kaisyuu = $Customer[0]->kaisyuu;
						 if($kaisyuu == 1){
							 $kaisyuu = "振込";
						 }else{
							 $kaisyuu = "集金";
						 }
						 $this->set('kaisyuu',$kaisyuu);
					 }else{
						 $name = "";
					 }

					 $Uriage = $this->Uriagemasters->find('all', ['conditions' => ['customerId' => $id, 'delete_flag' => 0, 'seikyuuId' => 0]])->order(['	uriagebi' => 'ASC'])->toArray();
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

								 $arrDenpyou[] = $Uriage[$k]->denpyou_num;
								 $arrSyuturyoku[] = $Uriage[$k]->uriagebi->format('m/d');

								 $Uriagesyousais = $this->Uriagesyousais->find()
								 ->where([ 'uriagemasterId' => $Uriage[$k]->id, 'delete_flag' => 0])->order(['num' => 'asc'])->toArray();

								 $arrPro_1[] = $Uriagesyousais[0]->pro;

								 ${"Totalprice".$k} = 0;
								 for($i=0; $i<count($Uriagesyousais); $i++){

									 if(!empty($Uriagesyousais[$i]->pro)){
										 /*
										 echo "<pre>";
										 print_r($Uriagesyousais[$i]->id." ".$Uriagesyousais[$i]->price);
										 echo "</pre>";
*/
										 $totalkingaku = $totalkingaku + $Uriagesyousais[$i]->price;
										 ${"Totalprice".$k} = ${"Totalprice".$k} + $Uriagesyousais[$i]->price;

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
					 $hittyakubi = $Customer[0]->hittyakubi;
					 $this->set('hittyakubi',$hittyakubi);
					 $nyuukinbi = $Customer[0]->nyuukinbi;
					 $this->set('nyuukinbi',$nyuukinbi);
					 $kaisyuu = $Customer[0]->kaisyuu;
					 if($kaisyuu == 1){
						 $kaisyuu = "振込";
					 }else{
						 $kaisyuu = "集金";
					 }
					 $this->set('kaisyuu',$kaisyuu);

					 $Uriage = $this->Uriagemasters->find('all', ['conditions' => ['customerId' => $id, 'delete_flag' => 0, 'seikyuuId' => 0]])->order(['	uriagebi' => 'ASC'])->toArray();
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

								 $arrDenpyou[] = $Uriage[$k]->denpyou_num;
								 $arrSyuturyoku[] = $Uriage[$k]->uriagebi->format('m/d');

								 $Uriagesyousais = $this->Uriagesyousais->find()
								 ->where([ 'uriagemasterId' => $Uriage[$k]->id, 'delete_flag' => 0])->order(['num' => 'asc'])->toArray();
								 echo "<pre>";
								 print_r(count($Uriagesyousais));
								 echo "</pre>";

								 $arrPro_1[] = $Uriagesyousais[0]->pro;

								 ${"Totalprice".$k} = 0;
								 for($i=0; $i<count($Uriagesyousais); $i++){

									 if(!empty($Uriagesyousais[$i]->pro)){

										 $totalkingaku = $totalkingaku + $Uriagesyousais[$i]->price;
										 ${"Totalprice".$k} = ${"Totalprice".$k} + $Uriagesyousais[$i]->price;

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

				//	 $Today = date('m')."/".date('d', strtotime('+9hour'));
					 $Today = $data['date']['year']."-".$data['date']['month']."-".$data['date']['day'];
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
/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
*/
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
					 $hittyakubi = $Customer[0]->hittyakubi;
					 $this->set('hittyakubi',$hittyakubi);
					 $nyuukinbi = $Customer[0]->nyuukinbi;
					 $this->set('nyuukinbi',$nyuukinbi);
					 $kaisyuu = $Customer[0]->kaisyuu;
					 if($kaisyuu == 1){
						 $kaisyuu = "振込";
					 }else{
						 $kaisyuu = "集金";
					 }
					 $this->set('kaisyuu',$kaisyuu);
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

					 $Uriage = $this->Uriagemasters->find('all', ['conditions' => ['customerId' => $id, 'delete_flag' => 0, 'seikyuuId' => 0]])->order(['	uriagebi' => 'ASC'])->toArray();
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

								 $arrDenpyou[] = $Uriage[$k]->denpyou_num;
								 $arrSyuturyoku[] = $Uriage[$k]->uriagebi->format('m/d');

								 $Uriagesyousais = $this->Uriagesyousais->find()
								 ->where([ 'uriagemasterId' => $Uriage[$k]->id, 'delete_flag' => 0])->order(['num' => 'asc'])->toArray();

								 $arrPro_1[] = $Uriagesyousais[0]->pro;

								 ${"Totalprice".$k} = 0;
								 for($i=0; $i<count($Uriagesyousais); $i++){

									 if(!empty($Uriagesyousais[$i]->pro)){

										 $totalkingaku = $totalkingaku + $Uriagesyousais[$i]->price;
										 ${"Totalprice".$k} = ${"Totalprice".$k} + $Uriagesyousais[$i]->price;

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

			//		 $Today = date('m', strtotime('+9hour'))."/".date('d', strtotime('+9hour'));
			//		 $this->set('Today',$Today);
					 $monthSeikyuu = date('Y', strtotime('+9hour'))."年 ".date('m', strtotime('+9hour'))."月度";
					 $this->set('monthSeikyuu',$monthSeikyuu);

					 $tourokuArr = array();

					 $tourokuArr = array('customerId' => $data["id"],'furigana' => $furigana,
					 'date_seikyuu' => $data["seikyuubi"],'nyuukingaku' => $data["nyuukingaku"],'tyousei' => $data["tyousei"],
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

						$Uriage = $this->Uriagemasters->find('all', ['conditions' => ['customerId' => $id, 'delete_flag' => 0, 'seikyuuId' => 0]])->order(['	uriagebi' => 'ASC'])->toArray();
						$count = count($Uriage);
						$this->set('count',$count);

 					 if($count > 0){

 							 for ($k=0; $k<$count; $k++){

								 $Uriagesyousais = $this->Uriagesyousais->find()
								 ->where([ 'uriagemasterId' => $Uriage[$k]->id, 'delete_flag' => 0])->order(['num' => 'asc'])->toArray();

								 for($i=0; $i<count($Uriagesyousais); $i++){

									 if(!empty($Uriagesyousais[$i]->pro)){

										 $arrPros[] = array('pro' => $Uriagesyousais[$i]->pro, 'amount' => $Uriagesyousais[$i]->amount,
										 'tani' => $Uriagesyousais[$i]->tani, 'tanka' => $Uriagesyousais[$i]->tanka,
										 'price' => $Uriagesyousais[$i]->price, 'bik' => $Uriagesyousais[$i]->bik);

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

					 if($data["datehyouji_flag"] == 1){
						 $sheet->setCellValue('F2', " 年        月        日");
					 }else{
						 $sheet->setCellValue('F2', $dateexcl);
					 }

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

					 if($data["datehyouji_flag"] == 1){
						 $sheet->setCellValue('G3', " 年        月        日");
					 }else{
						 $sheet->setCellValue('G3', $dateexcl);
					 }

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

								$Uriage = $this->Uriagemasters->find('all', ['conditions' => ['customerId' => $data["id"], 'delete_flag' => 0, 'seikyuuId' => 0]])->toArray();
								$count = count($Uriage);

								$SeikyuusId = $this->Seikyuus->find('all', ['conditions' => ['customerId' => $data["id"], 'delete_flag' => 0]])->order(['	id' => 'desc'])->toArray();

								for ($k=0; $k<$count; $k++){

									$this->Uriagemasters->updateAll(
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

					 $year = date('Y');
					 $this->set('year',$year);
					 for($n=2010; $n<=$year; $n++){

						 $arrYear[$n] = $n;

						}
					 $this->set('arrYear',$arrYear);


					for($n=1; $n<=12; $n++){

						$arrMonth[$n] = $n;

					 }
					 $this->set('arrMonth',$arrMonth);

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

					 $date_sta = $data['date_sta']['year']."-".$data['date_sta']['month']."-".$data['date_sta']['day'];
					 $date_fin = $data['date_fin']['year']."-".$data['date_fin']['month']."-".$data['date_fin']['day'];
					 $this->set('date_sta',$date_sta);
					 $this->set('date_fin',$date_fin);

					 $date_fin = strtotime($date_fin);

					 $data = $this->request->getData();
/*
					 echo "<pre>";
					 print_r($data);
					 echo "</pre>";
*/
					 if($data['date_sta_m'] == 12){
						 $date_fin_m = 1;
						 $date_fin_y = $data['date_sta_y'] + 1;
					 }else{
						 $date_fin_m = $data['date_sta_m'] + 1;
						 $date_fin_y = $data['date_sta_y'];
					 }

					 $date_sta = $data['date_sta_y']."-".$data['date_sta_m']."-1";
					 $date_fin = $date_fin_y."-".$date_fin_m."-1";
					 $this->set('date_y',$data['date_sta_y']);
					 $this->set('date_m',$data['date_sta_m']);

					 $date_sta = strtotime($date_sta);
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

					 $year = date('Y');
					 $this->set('year',$year);
					 for($n=2010; $n<=$year; $n++){

						 $arrYear[$n] = $n;

						}
					 $this->set('arrYear',$arrYear);


					for($n=1; $n<=12; $n++){

						$arrMonth[$n] = $n;

					 }
					 $this->set('arrMonth',$arrMonth);

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
					 if($data['date_sta_m'] == 12){
					 	$date_fin_m = 1;
					 	$date_fin_y = $data['date_sta_y'] + 1;
					 }else{
					 	$date_fin_m = $data['date_sta_m'] + 1;
					 	$date_fin_y = $data['date_sta_y'];
					 }

					 $date_sta = $data['date_sta_y']."-".$data['date_sta_m']."-1";
					 $date_fin = $date_fin_y."-".$date_fin_m."-1";
					 $this->set('date_y',$data['date_sta_y']);
					 $this->set('date_m',$data['date_sta_m']);

					 $date_sta = strtotime($date_sta);
					 $date_fin = strtotime($date_fin);

					 $Zandakas = $this->Zandakas->find()
					 ->where(['koushinbi >=' => $date_sta, 'koushinbi <' => $date_fin, 'zandaka >' => 0, 'delete_flag' => 0])->order(["koushinbi"=>"ASC"])->toArray();
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

				 public function uriagekensakumenu()//売上照会
				 {
					 $nyuukins = $this->Nyuukins->newEntity();
					 $this->set('nyuukins',$nyuukins);
				 }

				 public function uriagekensakucustomer()//売上照会
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

				 public function uriagekensakucustomerfurigana()//売上照会
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

				 public function uriagekensakucustomerview()//売上照会
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

						 return $this->redirect(['action' => 'uriagekensakucustomerfurigana',
						 's' => ['data' => $data]]);

					 }

					 if(!empty($data["name1"])){
						 $id = $data["name1"];
					 }elseif(!empty($data["name2"])){
						 $id = $data["name2"];
					 }else{
						 $name = "";
					 }

					 //新
					 $Uriagemasters = $this->Uriagemasters->find()
					 ->where(['customerId' => $id, 'delete_flag' => 0])->order(["uriagebi"=>"ASC"])->toArray();
					 $this->set('Uriagemasters',$Uriagemasters);

					 $count = count($Uriagemasters);
					 $Uriagetotalhyouji = 0;
					 $arrUriages = array();

					 for($j=0; $j<$count; $j++){

						 $Uriagesyousais = $this->Uriagesyousais->find()
						 ->where(['uriagemasterId' => $Uriagemasters[$j]->id, 'delete_flag' => 0])->order(["num"=>"ASC"])->toArray();
						 $this->set("Uriagesyousais".$j,$Uriagesyousais);

						 $countsyousai = count($Uriagesyousais);

						 for($i=0; $i<$countsyousai; $i++){

							 if(!empty($Uriagesyousais[$i]->price)){

								 $arrUriages[] = array(
									 "denpyou_num" => $Uriagemasters[$j]->denpyou_num,
									 "syutsuryokubi" => $Uriagemasters[$j]->uriagebi->format('Y-m-d'),
									 "pro" => $Uriagesyousais[$i]->pro,
									 "amount" => $Uriagesyousais[$i]->amount,
									 "tani" => $Uriagesyousais[$i]->tani,
									 "tanka" => $Uriagesyousais[$i]->tanka,
									 "price" => $Uriagesyousais[$i]->price,
									 "bik" => $Uriagesyousais[$i]->bik,
								 );

								 $Uriagetotalhyouji = $Uriagetotalhyouji + $Uriagesyousais[$i]->price;

							 }

						 }

					 }
					 $Uriagetotalhyouji = $Uriagetotalhyouji * 1.1;
					 $this->set('Uriagetotalhyouji',$Uriagetotalhyouji);


/*/元
					 $Uriages = $this->Uriages->find()
		 			->where(['customerId' => $id, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
		 			$this->set('Uriages',$Uriages);

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
/*/
		     }

				 public function uriagekakocustomer()//売上照会
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

				 public function uriagekakocustomerrfurigana()//売上照会
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

				 public function uriagekakocustomerview()//売上照会
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

						 return $this->redirect(['action' => 'uriagekakocustomerrfurigana',
						 's' => ['data' => $data]]);

					 }

					 if(!empty($data["name1"])){
						 $id = $data["name1"];
					 }elseif(!empty($data["name2"])){
						 $id = $data["name2"];
					 }else{
						 $name = "";
					 }

/*/元
					 $Uriages = $this->Uriages->find()
		 			->where(['customerId' => $id, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
		 			$this->set('Uriages',$Uriages);

					 $count = count($Uriages);
					 $Uriagetotalhyouji = 0;
					 $arrUriages = array();

					 for($j=0; $j<$count; $j++){

						 for($i=1; $i<=20; $i++){

							 if(!empty($Uriages[$j]->{"price_{$i}"})){

								 $arrUriages[] = array(
									 "denpyou_num" => $Uriages[$j]->denpyou_num,
									 "syutsuryokubi" => $Uriages[$j]->syutsuryokubi->format('Y-m-d'),
									 "pro" => $Uriages[$j]->{"pro_{$i}"},
									 "amount" => $Uriages[$j]->{"amount_{$i}"},
									 "tani" => $Uriages[$j]->{"tani_{$i}"},
									 "tanka" => $Uriages[$j]->{"tanka_{$i}"},
									 "price" => $Uriages[$j]->{"price_{$i}"},
									 "bik" => $Uriages[$j]->{"bik_{$i}"},
								 );

								 $Uriagetotalhyouji = $Uriagetotalhyouji + $Uriages[$j]->{"price_{$i}"};

							 }

						 }

					 }
					 $Uriagetotalhyouji = $Uriagetotalhyouji * 1.1;
					 $this->set('Uriagetotalhyouji',$Uriagetotalhyouji);

					 $this->set('arrUriages',$arrUriages);
/*/
//新
						$Uriagemasters = $this->Uriagemasters->find()
						->where(['customerId' => $id, 'delete_flag' => 0])->order(["uriagebi"=>"ASC"])->toArray();

						$count = count($Uriagemasters);
						$Uriagetotalhyouji = 0;
						$arrUriages = array();

						for($j=0; $j<$count; $j++){

							$Uriagesyousais = $this->Uriagesyousais->find()
							->where(['uriagemasterId' => $Uriagemasters[$j]->id, 'delete_flag' => 0])->order(["num"=>"ASC"])->toArray();

							$countsyousai = count($Uriagesyousais);

							for($i=0; $i<$countsyousai; $i++){

								if(!empty($Uriagesyousais[$i]->price)){

									$arrUriages[] = array(
										"denpyou_num" => $Uriagemasters[$j]->denpyou_num,
										"syutsuryokubi" => $Uriagemasters[$j]->uriagebi->format('Y-m-d'),
										"pro" => $Uriagesyousais[$i]->pro,
										"amount" => $Uriagesyousais[$i]->amount,
										"tani" => $Uriagesyousais[$i]->tani,
										"tanka" => $Uriagesyousais[$i]->tanka,
										"price" => $Uriagesyousais[$i]->price,
										"bik" => $Uriagesyousais[$i]->bik,
									);

									$Uriagetotalhyouji = $Uriagetotalhyouji + $Uriagesyousais[$i]->price;

								}

							}

						}
						$Uriagetotalhyouji = $Uriagetotalhyouji * 1.1;
						$this->set('Uriagetotalhyouji',$Uriagetotalhyouji);

						$this->set('arrUriages',$arrUriages);

						$Uriages = $this->Uriages->find()
 		 			->where(['customerId' => $id, 'delete_flag' => 0])->order(["syutsuryokubi"=>"ASC"])->toArray();
 		 			$this->set('Uriages',$Uriages);

					 $customer = $Uriages[0]->customer;
					 $this->set('customer',$customer);

		     }

/*
				 public function printcustomer()
		     {
		       $Customer = $this->Customers->newEntity();
		       $this->set('Customer', $Customer);

		       $Customers = $this->Customers->find()->where(['delete_flag' => 0])->order(["furigana"=>"ASC"])->toArray();
		       $this->set('Customers',$Customers);

		       $data = $this->request->getData();

		       $mess = "登録済み取引先データをエクセルに出力します。";
		       if(isset($data["confirm"])){

						 //エクセル出力
						 $filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\取引先データ一覧.xlsx'; //読み込みたいファイルの指定
						 $reader = new XlsxReader();
						 $spreadsheet = $reader->load($filepath);
						 $sheet = $spreadsheet->getSheetByName("Sheet1");

						$num = 2;
						for($j=0; $j<4; $j++){

	            $num = $j + 2;

	              $sheet->setCellValue("A".$num, $Customers[$j]["id"]);
	              $sheet->setCellValue("B".$num, $Customers[$j]["id"]);
	              $sheet->setCellValue("C".$num, $Customers[$j]["name"]);
	              $sheet->setCellValue("D".$num, $Customers[$j]["id"]);
	              $sheet->setCellValue("E".$num, $Customers[$j]["id"]);

	          }

	  				 $writer = new XlsxWriter($spreadsheet);

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

 		 			 $mesxlsx = "「エクセル出力/売掛一覧/".$year."/".$month."/".$day."」フォルダにエクセルシート「".$file_name."」が出力されました。";
 		 			 $this->set('mesxlsx',$mesxlsx);

 		 			 $writer->save($outfilepath);

		       }

		       $this->set('mess',$mess);
		       echo "<pre>";
		       print_r(count($Customers));
		       echo "</pre>";

		     }
*/

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
