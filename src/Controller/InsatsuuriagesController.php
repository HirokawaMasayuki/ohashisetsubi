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
use PhpOffice\PhpSpreadsheet\Style\Alignment as Align;

class InsatsuuriagesController extends AppController
{

	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Paginator');

		$this->Customers = TableRegistry::get('customers');
		$this->Uriagemasters = TableRegistry::get('uriagemasters');
		$this->Uriagesyousais = TableRegistry::get('uriagesyousais');
	}

	public function yobidashidate()
	{
		$uriages = $this->Uriagesyousais->newEntity();
		$this->set('uriages',$uriages);

		$Data = $this->request->query('s');
		if(isset($Data["mess"])){
			$mess = $Data["mess"];
		}else{
			$mess = "";
		}
		$this->set('mess',$mess);

		if(!isset($_SESSION)){
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function uriageinsatsu()
	{
		$uriages = $this->Uriagesyousais->newEntity();
		$this->set('uriages',$uriages);
		$data = $this->request->getData();

		if(isset($data['date_sta']['year'])){
			$date_sta = $data['date_sta']['year']."-".$data['date_sta']['month']."-".$data['date_sta']['day'];
			$date_fin = $data['date_fin']['year']."-".$data['date_fin']['month']."-".$data['date_fin']['day'];
		}else{
			$date_sta = $data['date_sta'];
			$date_fin = $data['date_fin'];
		}
		$this->set('date_sta',$date_sta);
		$this->set('date_fin',$date_fin);

		$date_diff = strtotime($date_fin) - strtotime($date_sta);

		if($date_diff/(60 * 60 * 24) > 1095){//1095日（3年）以上は出力不可とする

			return $this->redirect(['action' => 'yobidashidate',
			's' => ['mess' => "※３年を超える範囲では出力できません。"]]);

		}

		$Uriagesyousais = $this->Uriagesyousais->find()
		->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'delete_flag' => 0])
		->order(["uriagebi"=>"ASC"])->toArray();

		$count1 = count($Uriagesyousais);
		for($i=0; $i<$count1; $i++){

			if(strlen($data['denpyou_num_sta']) > 2 || strlen($data['denpyou_num_fin']) > 2){
				$denpyou_num_sta = $data['denpyou_num_sta'];
				$this->set('denpyou_num_sta',$denpyou_num_sta);
				$denpyou_num_fin = $data['denpyou_num_fin'];
				$this->set('denpyou_num_fin',$denpyou_num_fin);

				if(strlen($data['denpyou_num_sta']) < 1){
					$Uriagemasters = $this->Uriagemasters->find()
					->where(['id' => $Uriagesyousais[$i]["uriagemasterId"],
					'denpyou_num <=' =>  $denpyou_num_fin,
					'delete_flag' => 0])->toArray();
				}elseif(strlen($data['denpyou_num_fin']) < 1){
					$Uriagemasters = $this->Uriagemasters->find()
					->where(['id' => $Uriagesyousais[$i]["uriagemasterId"],
					'denpyou_num >=' =>  $denpyou_num_sta,
					'delete_flag' => 0])->toArray();
				}else{
					$Uriagemasters = $this->Uriagemasters->find()
					->where(['id' => $Uriagesyousais[$i]["uriagemasterId"],
					'denpyou_num >=' =>  $denpyou_num_sta,
					'denpyou_num <=' =>  $denpyou_num_fin,
					'delete_flag' => 0])->toArray();
				}

				if(isset($Uriagemasters[0])){

					if($Uriagesyousais[$i]["num"] == 1){
						$genba = $Uriagesyousais[$i]["pro"];
					}
					$Uriagesyousais[$i]["genba"] = $genba;
					$Uriagesyousais[$i]["delete_flag"] = $Uriagemasters[0]["denpyou_num"];
					$Uriagesyousais[$i]["created_at"] = $Uriagemasters[0]["customer"];
					$Uriagesyousais[$i]["id"] = $Uriagemasters[0]["bunrui"];

				}else{

					unset($Uriagesyousais[$i]);

				}

			}else{

				$denpyou_num_sta = "-";
				$this->set('denpyou_num_sta',$denpyou_num_sta);
				$denpyou_num_fin = "-";
				$this->set('denpyou_num_fin',$denpyou_num_fin);

				$Uriagemasters = $this->Uriagemasters->find()
				->where([ 'id' => $Uriagesyousais[$i]["uriagemasterId"],
				'delete_flag' => 0])->toArray();

				if(isset($Uriagemasters[0])){

					$genba = "";
					if($Uriagesyousais[$i]["num"] == 1){
						$genba = $Uriagesyousais[$i]["pro"];
					}
					$Uriagesyousais[$i]["genba"] = $genba;
					$Uriagesyousais[$i]["delete_flag"] = $Uriagemasters[0]["denpyou_num"];
					$Uriagesyousais[$i]["created_at"] = $Uriagemasters[0]["customer"];
					$Uriagesyousais[$i]["id"] = $Uriagemasters[0]["bunrui"];

				}else{

					unset($Uriagesyousais[$i]);

				}

			}

		}
		$Uriages = $Uriagesyousais;

		if(count($Uriages) > 0){

			foreach( $Uriages as $key => $row ) {
				$tmp_uriagebi[$key] = $row["uriagebi"];
				$tmp_denpyou_num[$key] = $row["delete_flag"];
			}

			array_multisort( array_map( "strtotime", $tmp_uriagebi ),
			$tmp_denpyou_num, SORT_ASC, SORT_NUMERIC,
			$Uriages );

			$Uriages = array_values($Uriages);

		}

		$this->set('Uriages',$Uriages);

		$mess = "";
		if(isset($data['output'])){//エクセル出力

			$filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\売上データ一覧.xlsx'; //読み込みたいファイルの指定
			$reader = new XlsxReader();
			$spreadsheet = $reader->load($filepath);
			$sheet = $spreadsheet->getSheetByName("Sheet1");

			$baseSheet = $spreadsheet->getSheet(0);
			$newSheet = $baseSheet->copy();
			$newSheet->setTitle($date_sta."~".$date_fin);
			$spreadsheet->addSheet( $newSheet );

			$sheet = $spreadsheet->getSheetByName($date_sta."~".$date_fin);

			$writer = new XlsxWriter($spreadsheet);

			for($j=0; $j<count($Uriages); $j++){
				$num = $j + 2;
				$sheet->setCellValue("A".$num, $Uriages[$j]["delete_flag"]);
				$sheet->setCellValue("B".$num, $Uriages[$j]["uriagebi"]->format('Y-m-d'));
				$sheet->setCellValue("C".$num, $Uriages[$j]["created_at"]);
				$sheet->setCellValue("D".$num, $Uriages[$j]["genba"]);
				$sheet->setCellValue("E".$num, $Uriages[$j]["id"]);
				$sheet->setCellValue("F".$num, $Uriages[$j]["num"]);
				if($Uriages[$j]["zeiritu"] == 8){
					$sheet->setCellValue("G".$num, $Uriages[$j]["pro"]."　※");
				}else{
					$sheet->setCellValue("G".$num, $Uriages[$j]["pro"]);
				}
				$sheet->setCellValue("H".$num, $Uriages[$j]["amount"]);
				$sheet->setCellValue("I".$num, $Uriages[$j]["tani"]);
				$sheet->setCellValue("J".$num, $Uriages[$j]["tanka"]);
				$sheet->setCellValue("K".$num, $Uriages[$j]["price"]);
				$sheet->setCellValue("L".$num, $Uriages[$j]["bik"]);
			}

			$sheetIndex = $spreadsheet->getIndex(
				$spreadsheet->getSheetByName('Sheet1')
			);
			$spreadsheet->removeSheetByIndex($sheetIndex);

			$datetime = date('H時i分s秒出力', strtotime('+9hour'));
			$year = date('Y', strtotime('+9hour'));
			$month = date('m', strtotime('+9hour'));
			$day = date('d', strtotime('+9hour'));
			$date_m = date('m', strtotime('+9hour'));
			$date_y = date('Y', strtotime('+9hour'));

			$file_name = "売上データ一覧_".$year."-".$month."-".$day."-".$datetime.".xlsx";

			if(is_dir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/売上データ一覧/$year/$month/$day")){//ディレクトリが存在すればOK

				$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/売上データ一覧/$year/$month/$day/$file_name"; //出力したいファイルの指定

			}else{//ディレクトリが存在しなければ作成する

				mkdir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/売上データ一覧/$year/$month/$day", 0777, true);
				$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/売上データ一覧/$year/$month/$day/$file_name"; //出力したいファイルの指定

			}

			$writer->save($outfilepath);

			$mess = "「エクセル出力/売上データ一覧/".$year."/".$month."/".$day."」フォルダにエクセルシート「".$file_name."」が出力されました。";
		}
		$this->set('mess',$mess);


		if(!isset($_SESSION)){
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');

	}

}
