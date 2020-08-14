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
/*
			 $arrtouroku = array();
       $arrtouroku[] = array(
         'test' => "test4",
         'delete_flag' => 0,
         'created_at' => date('Y-m-d H:i:s', strtotime('+9hour'))
       );

			 $tests = $this->Tests->patchEntity($this->Tests->newEntity(), $arrtouroku[0]);
       $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4

         if ($this->Tests->save($tests)) {

           $mes = "※下記のように登録されました";
           $this->set('mes',$mes);

           $connection->commit();// コミット5

         } else {

           $mes = "※登録されませんでした";
           $this->set('mes',$mes);

           $this->Flash->error(__('The data could not be saved. Please, try again.'));
           throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

         }

       } catch (Exception $e) {//トランザクション7
       //ロールバック8
         $connection->rollback();//トランザクション9

       }//トランザクション10
*/


/*
//エラー出るけどダウンロードはできる
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World 1');
$sheet->setCellValue('A2', 'Hello World 2');

// xlsx ファイルをダウンロード
$this->loadComponent('Excel');
$filename = 'サンプル_' . date('ymd_His');
return $this->Excel->download($spreadsheet, $filename);
*/

/*
//現在のパスを取得
$get_dir = __FILE__;
echo $get_dir . "<br />";
$get_dir_path =  dirname($get_dir);
echo $get_dir_path;
*/

$filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\test_x.xlsx'; //読み込みたいファイルの指定
//$filepath = '\\DESKTOP-QT28T8M\共有hirokawa\xampp\htdocs\CakePHPapp\webroot\エクセル出力\test_x.xlsx'; //読み込みたいファイルの指定

$reader = new XlsxReader();
$spreadsheet = $reader->load($filepath);
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'xxx');
$sheet->setCellValue('A2', 'yyy');

$writer = new XlsxWriter($spreadsheet);

$outfilepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル出力\test_y.xlsx'; //出力したいファイルの指定
//$outfilepath = '\\DESKTOP-QT28T8M\共有hirokawa\xampp\htdocs\CakePHPapp\webroot\エクセル出力\test_y.xlsx'; //読み込みたいファイルの指定

$writer->save($outfilepath);

     }

}
