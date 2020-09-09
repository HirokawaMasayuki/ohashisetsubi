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
			$this->Tests = TableRegistry::get('tests');
			$this->Customers = TableRegistry::get('customers');
		  }

		 public function index()
     {
	//		 echo phpinfo();
     }

		 public function form()
     {
			 $customers = $this->Customers->newEntity();
       $this->set('customers',$customers);
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
