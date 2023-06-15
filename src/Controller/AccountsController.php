<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager; //トランザクション
use Cake\Core\Exception\Exception; //トランザクション
use Cake\Core\Configure; //トランザクション
use Cake\ORM\TableRegistry; //独立したテーブルを扱う
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Style\Alignment as Align;

class AccountsController extends AppController
{

	public $paginate = [
		'limit' => 50
	];

	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Paginator');

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
		/*
		echo "<pre>";
		print_r("aaa");
		echo "</pre>";
		*/
	}

	public function uriageformcustomer()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0']])->order(['furigana' => 'ASC']);
		$arrCustomer = array();
		foreach ($arrCustomers as $value) {
			$furigana = $value->furigana;
			$furigana = mb_substr($furigana, 0, 1);;
			$arrCustomer[] = array($value->id => $furigana . " - " . $value->name . ' ' . $value->siten);
		}
		$this->set('arrCustomer', $arrCustomer);

		$autoCustomers = $this->Customers->find()
			->where(['delete_flag' => 0])->toArray();
		$arrCustomer_list = array();
		for ($j = 0; $j < count($autoCustomers); $j++) {

			if (strlen($autoCustomers[$j]["siten"]) > 0) {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"] . ":" . $autoCustomers[$j]["siten"]);
			} else {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"]);
			}
		}
		$arrCustomer_list = array_unique($arrCustomer_list);
		$arrCustomer_list = array_values($arrCustomer_list);
		$this->set('arrCustomer_list', $arrCustomer_list);
	}

	public function uriageformcustomerfurigana()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$Data = $this->request->query('s');
		$data = $Data['data'];

		$furigana = $data["nyuryokufurigana"];
		$arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0', 'furigana like' => '%' . $furigana . '%']])->order(['furigana' => 'ASC']);
		$arrCustomer = array();
		foreach ($arrCustomers as $value) {
			$arrCustomer[] = array($value->id => $value->name . ' ' . $value->siten);
		}
		$this->set('arrCustomer', $arrCustomer);
	}

	public function uriageformsyousai()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$Data = $this->request->query('s');
		$check_orver = 0;
		$this->set('check_orver', $check_orver);

		$mess = "";
		$this->set('mess', $mess);

		if (isset($Data["get"])) {

			session_start();
			header('Expires:-1');
			header('Cache-Control:');
			header('Pragma:');

			$session = $this->request->session();
			$data = $_SESSION['pass'];
		} else {

			$data = $this->request->getData();
			session_start();
			$session = $this->request->session();
		}

		$Uriages = $this->Uriagemasters->find()->where(['delete_flag' => 0])->order(["denpyou_num" => "desc"])->toArray();
		if (isset($Uriages[0])) {
			$denpyou_num = $Uriages[0]->denpyou_num + 1;
		} else {
			$denpyou_num = 10000;
		}
		$this->set('denpyou_num', $denpyou_num);

		if (!empty($data["nyuryokufurigana"])) {

			return $this->redirect([
				'action' => 'uriageformcustomerfurigana',
				's' => ['data' => $data]
			]);
		}

		$name = "";

		if (!empty($data["name1"])) {

			$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["name1"]]])->toArray();
			$name = $Customer[0]->name;
			$siten = $Customer[0]->siten;
			$namehyouji = $name . " " . $siten;
			$this->set('namehyouji', $namehyouji);
			$id = $Customer[0]->id;
			$this->set('id', $id);
			$session->delete('uriage' . $id);
			$_SESSION['uriage' . $id] = array();
			$_SESSION['pass' . $id] = array();
			$_SESSION['touroku' . $id] = array();
			$_SESSION['uriageform_date' . $id] = "";
			$_SESSION['uriageform_bunrui' . $id] = "";
			$_SESSION['uriageform_yuubin' . $id] = "";
			$_SESSION['uriageform_address' . $id] = "";
			$_SESSION['uriageform_keisyou' . $id] = "";
			$_SESSION['editpass' . $id] = array();
		} elseif (!empty($data["name2"])) {

			$arrname2 = explode(':', $data["name2"]);
			$name2 = $arrname2[0];

			if (isset($arrname2[1])) {
				$Customer = $this->Customers->find('all', ['conditions' => ['delete_flag' => 0, 'name' => $name2, 'siten' => $arrname2[1]]])->toArray();
			} else {
				$Customer = $this->Customers->find('all', ['conditions' => ['delete_flag' => 0, 'name' => $name2]])->toArray();
			}

			$name = $Customer[0]->name;
			$siten = $Customer[0]->siten;
			$namehyouji = $name . " " . $siten;
			$this->set('namehyouji', $namehyouji);
			$id = $Customer[0]->id;
			$this->set('id', $id);
			$session->delete('uriage' . $id);
			$_SESSION['uriage' . $id] = array();
			$_SESSION['pass' . $id] = array();
			$_SESSION['touroku' . $id] = array();
			$_SESSION['uriageform_date' . $id] = "";
			$_SESSION['uriageform_bunrui' . $id] = "";
			$_SESSION['uriageform_yuubin' . $id] = "";
			$_SESSION['uriageform_address' . $id] = "";
			$_SESSION['uriageform_keisyou' . $id] = "";
			$_SESSION['editpass' . $id] = array();
		} elseif (isset($this->request->query('s')["customerId"])) {

			$Customer = $this->Customers->find('all', ['conditions' => ['id' => $this->request->query('s')["customerId"]]])->toArray();
			$name = $Customer[0]->name;
			$siten = $Customer[0]->siten;
			$namehyouji = $name . " " . $siten;
			$this->set('namehyouji', $namehyouji);
			$id = $Customer[0]->id;
			$this->set('id', $id);
			$session->delete('uriage' . $id);
			$_SESSION['uriage' . $id] = array();
			$_SESSION['pass' . $id] = array();
			$_SESSION['touroku' . $id] = array();
			$_SESSION['uriageform_date' . $id] = "";
			$_SESSION['uriageform_bunrui' . $id] = "";
			$_SESSION['uriageform_yuubin' . $id] = "";
			$_SESSION['uriageform_address' . $id] = "";
			$_SESSION['uriageform_keisyou' . $id] = "";
			$_SESSION['editpass' . $id] = array();
		} else {
			$name = "";
		}

		$yuubin = "";
		$address = "";
		$keisyou = "";
		$furigana = "";
		$customercheck = 1;

		if (isset($data["id"]) || isset($Customer[0])) {

			if (isset($data["id"])) {
				$this->set('id', $data["id"]);
				$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
			}
			$yuubin = $Customer[0]->yuubin;
			$address = $Customer[0]->address;
			$keisyou = $Customer[0]->keisyou;
			$furigana = $Customer[0]->furigana;
			$name = $Customer[0]->name;
			$siten = $Customer[0]->siten;
			$namehyouji = $name . " " . $siten;
			if ($keisyou == 1) {
				$keisyou = "様";
			} elseif ($keisyou == 2) {
				$keisyou = "御中";
			} elseif ($keisyou == 3) {
				$keisyou = "殿";
			} else {
				$keisyou = "御中";
			}
		} elseif (isset($data['next'])) {
			$customercheck = 2;
		}

		if (!isset($_SESSION)) {
			session_start();
		}

		if (isset($data['id'])) {
			$id = $data['id'];
		}

		if (isset($_SESSION['uriageform_bunrui' . $id])) {

			if (strlen($_SESSION['uriageform_bunrui' . $id]) > 0) {

				$uriageform_date = $_SESSION['uriageform_date' . $id];
				$bunrui = $_SESSION['uriageform_bunrui' . $id];
				$yuubin = $_SESSION['uriageform_yuubin' . $id];
				$address = $_SESSION['uriageform_address' . $id];
				$keisyou = $_SESSION['uriageform_keisyou' . $id];
			} else {

				$uriageform_date = date('Y-m-d H:i:s', strtotime('+9hour'));
				$bunrui = "電気";
			}
		} else {

			$uriageform_date = date('Y-m-d H:i:s', strtotime('+9hour'));
			$bunrui = "電気";
		}

		$arrZeiritu = [
			10 => 10,
			8 => 8,
			0 => "非課税"
		];
		$this->set('arrZeiritu', $arrZeiritu);

		$arrBunrui = [
			'電気' => '電気',
			'防災' => '防災',
			'管' => '管'
		];
		$this->set('arrBunrui', $arrBunrui);

		$this->set('namehyouji', $namehyouji);

		$this->set('uriageform_date', $uriageform_date);
		$this->set('bunrui', $bunrui);
		$this->set('yuubin', $yuubin);
		$this->set('address', $address);
		$this->set('keisyou', $keisyou);
		$this->set('furigana', $furigana);
		$this->set('customercheck', $customercheck);

		if (isset($data['tuika'])) {

			if ($data['num'] >= 2000) {

				$tuika = $data['num'];
				$this->set('tuika', $tuika);

				echo "<pre>";
				print_r("2000行以上の登録は同時にできません。２回に分けて登録してください。");
				echo "</pre>";
			} else {

				$uriageform_date = $data["date"]["year"] . "-" . $data["date"]["month"] . "-" . $data["date"]["day"];
				$bunrui = $data["bunrui"];
				$yuubin = $data["yuubin"];
				$address = $data["address"];
				$keisyou = $data["keisyou"];

				$this->set('uriageform_date', $uriageform_date);
				$this->set('bunrui', $bunrui);
				$this->set('yuubin', $yuubin);
				$this->set('address', $address);
				$this->set('keisyou', $keisyou);

				$tuika = $data['num'] + 1;
				$this->set('tuika', $tuika);
			}
		} elseif (isset($data['confirm'])) {

			$tuika = $data['num'];
			$this->set('tuika', $tuika);

			$check_orver = 1;
			$this->set('check_orver', $check_orver);

			$id = $data['id'];
			$_SESSION['touroku' . $id] = $data;

			$uriageform_date = $data["date"]["year"] . "-" . $data["date"]["month"] . "-" . $data["date"]["day"];

			$bunrui = $data["bunrui"];
			$yuubin = $data["yuubin"];
			$address = $data["address"];
			$keisyou = $data["keisyou"];

			$this->set('uriageform_date', $uriageform_date);
			$this->set('bunrui', $bunrui);
			$this->set('yuubin', $yuubin);
			$this->set('address', $address);
			$this->set('keisyou', $keisyou);

			if (checkdate($data["date"]["month"], $data["date"]["day"], $data["date"]["year"])) {

				$mb_strlen_check = 0;
				for ($i = 1; $i <= $data["num"]; $i++) {

					if (!empty($data["pro_" . $i]) && $data["delete_flag_" . $i] == 0) {
						if (mb_strwidth($data['pro_' . $i]) > 49) {

							${"pro_orver" . $i} = 1;
							$this->set('pro_orver' . $i, ${"pro_orver" . $i});

							$mb_strlen_check = 1;
							$mess = "２５文字以上の品名は登録できません。品名を修正してください。";
							$this->set('mess', $mess);
						}
					}
				}

				if ($mb_strlen_check == 0) {

					$id = $data["id"];
					return $this->redirect([
						'action' => 'uriagesyuturyokukakunin',
						's' => ['id' => $id]
					]);
				}
			} else {

				$mess = "無効な日付が選択されました。";
				$this->set('mess', $mess);
			}
		} else {

			$tuika = 0;
			$this->set('tuika', $tuika);

			$i = 1;
			${"num_" . $i} = 1;
			$this->set('num_' . $i, ${"num_" . $i});
			${"pro_" . $i} = "";
			$this->set('pro_' . $i, ${"pro_" . $i});
			${"amount_" . $i} = "";
			$this->set('amount_' . $i, ${"amount_" . $i});
			${"tani_" . $i} = "";
			$this->set('tani_' . $i, ${"tani_" . $i});
			${"tanka_" . $i} = "";
			$this->set('tanka_' . $i, ${"tanka_" . $i});
			${"zeiritu_" . $i} = 10;
			$this->set('zeiritu_' . $i, ${"zeiritu_" . $i});
			${"bik_" . $i} = "";
			$this->set('bik_' . $i, ${"bik_" . $i});
		}

		$zenkaicheck = 0;
		$this->set('zenkaicheck', $zenkaicheck);

		if (isset($data["zenkai0"])) {

			$zenkaicheck = 1;
			$this->set('zenkaicheck', $zenkaicheck);
			$id = $data["id"];
			$_SESSION['pass' . $id] = $data;

			return $this->redirect([
				'action' => 'uriageformzenkaikensaku',
				's' => ['get' => 1, 'id' => $id]
			]);
		} elseif (isset($data["zenkai"])) {

			$zenkaicheck = 1;
			$this->set('zenkaicheck', $zenkaicheck);
			$id = $data["id"];
			$_SESSION['pass' . $id] = $data;

			return $this->redirect([
				'action' => 'uriageformzenkai',
				's' => ['get' => 1, 'id' => $id]
			]);
		} elseif (isset($data["zenkai2"])) {

			$zenkaicheck = 1;
			$this->set('zenkaicheck', $zenkaicheck);
			$id = $data["id"];
			$_SESSION['pass' . $id] = $data;

			return $this->redirect([
				'action' => 'uriageformzenkaisyousai',
				's' => ['get' => 1, 'id' => $id]
			]);
		} elseif (isset($data["zenkai3"])) {

			$zenkaicheck = 1;
			$this->set('zenkaicheck', $zenkaicheck);
			$id = $data["id"];

			$_SESSION['uriage' . $id][] = array(
				'UriagesyousaiId' => 0,
			);
		} elseif (isset($data["zenkai4"])) {

			$zenkaicheck = 1;
			$this->set('zenkaicheck', $zenkaicheck);
			$id = $data["id"];

			unset($_SESSION['uriage' . $id][count($_SESSION['uriage' . $id]) - 1]);
		}

		if (isset($data["zenkaiikkatu"])) {

			$id = $data["id"];
			$data = array_keys($data, '選択');
			$UriagemasterId = $data[0];
			$Uriagesyousaiszenkai = $this->Uriagesyousais->find()->where(['uriagemasterId' => $UriagemasterId, 'delete_flag' => 0])->order(["num" => "asc"])->toArray();

			for ($i = 0; $i < count($Uriagesyousaiszenkai); $i++) {

				$_SESSION['uriage' . $id][] = array(
					'UriagesyousaiId' => $Uriagesyousaiszenkai[$i]["id"],
				);
			}
		} elseif (isset($data["zenkaikobetu"])) {

			$arrzenkaikobetu = array();
			for ($i = 0; $i < $data["num_max"]; $i++) {

				if (strlen($data["select" . $i]) > 0) {

					$arrzenkaikobetu[] = [
						"num" => $data["select" . $i],
						"id" => $data[$i]
					];
				}
			}

			$sort = array();
			foreach ((array) $arrzenkaikobetu as $key => $value) {
				$sort[$key] = $value['num'];
			}
			array_multisort($sort, SORT_ASC, $arrzenkaikobetu);

			for ($i = 0; $i < count($arrzenkaikobetu); $i++) {

				$id = $data["id"];
				$_SESSION['uriage' . $id][] = array(
					'UriagesyousaiId' => $arrzenkaikobetu[$i]["id"],
				);
			}
		}

		if (isset($_SESSION['uriage' . $id])) {
			$tuikauriage = count($_SESSION['uriage' . $id]);
		} else {
			$tuikauriage = 0;
		}

		if ($tuikauriage  > 0) {
			$zenkaicheck = 1;
			$this->set('zenkaicheck', $zenkaicheck);
			$this->set('tuika', $tuikauriage);

			for ($i = 1; $i <= $tuikauriage; $i++) {

				if ($_SESSION['uriage' . $id][$i - 1]["UriagesyousaiId"] < 1) {

					${"num_" . $i} = $i;
					$this->set('num_' . $i, ${"num_" . $i});
					${"pro_" . $i} = "";
					$this->set('pro_' . $i, ${"pro_" . $i});
					${"amount_" . $i} =  "";
					$this->set('amount_' . $i, ${"amount_" . $i});
					${"tani_" . $i} =  "";
					$this->set('tani_' . $i, ${"tani_" . $i});
					${"tanka_" . $i} =  "";
					$this->set('tanka_' . $i, ${"tanka_" . $i});
					${"zeiritu_" . $i} =  "";
					$this->set('zeiritu_' . $i, ${"zeiritu_" . $i});
					${"bik_" . $i} =  "";
					$this->set('bik_' . $i, ${"bik_" . $i});
				} else {

					$Uriagesyousais = $this->Uriagesyousais->find()->where(['id' => $_SESSION['uriage' . $id][$i - 1]["UriagesyousaiId"]])->order(["num" => "asc"])->toArray();

					${"num_" . $i} = $i;
					$this->set('num_' . $i, ${"num_" . $i});
					${"pro_" . $i} = $Uriagesyousais[0]->pro;
					$this->set('pro_' . $i, ${"pro_" . $i});
					${"amount_" . $i} = $Uriagesyousais[0]->amount;
					$this->set('amount_' . $i, ${"amount_" . $i});
					${"tani_" . $i} = $Uriagesyousais[0]->tani;
					$this->set('tani_' . $i, ${"tani_" . $i});
					${"tanka_" . $i} = $Uriagesyousais[0]->tanka;
					$this->set('tanka_' . $i, ${"tanka_" . $i});
					${"zeiritu_" . $i} = $Uriagesyousais[0]->zeiritu;
					$this->set('zeiritu_' . $i, ${"zeiritu_" . $i});
					${"bik_" . $i} = $Uriagesyousais[0]->bik;
					$this->set('bik_' . $i, ${"bik_" . $i});
				}
			}
		}

		if (isset($_SESSION['pass' . $id]["num"])) {
			if (strlen($_SESSION['pass' . $id]["pro_1"]) > 0) {
				$this->set('pro_1', $_SESSION['pass' . $id]["pro_1"]);
			}
			for ($i = 2; $i <= $_SESSION['pass' . $id]["num"]; $i++) {
				$this->set('pro_' . $i, $_SESSION['pass' . $id]["pro_" . $i]);
				$this->set('amount_' . $i, $_SESSION['pass' . $id]["amount_" . $i]);
				$this->set('tani_' . $i, $_SESSION['pass' . $id]["tani_" . $i]);
				$this->set('amount_' . $i, $_SESSION['pass' . $id]["amount_" . $i]);
				$this->set('tanka_' . $i, $_SESSION['pass' . $id]["tanka_" . $i]);
				$this->set('zeiritu_' . $i, $_SESSION['pass' . $id]["zeiritu_" . $i]);
				$this->set('bik_' . $i, $_SESSION['pass' . $id]["bik_" . $i]);
			}
		}

		if (isset($data["num"])) {
			if (strlen($data["pro_1"]) > 0) {
				$this->set('pro_1', $data["pro_1"]);
			}
			for ($i = 2; $i <= $data["num"]; $i++) {
				$this->set('pro_' . $i, $data["pro_" . $i]);
				$this->set('amount_' . $i, $data["amount_" . $i]);
				$this->set('tani_' . $i, $data["tani_" . $i]);
				$this->set('amount_' . $i, $data["amount_" . $i]);
				$this->set('tanka_' . $i, $data["tanka_" . $i]);
				$this->set('zeiritu_' . $i, $data["zeiritu_" . $i]);
				$this->set('bik_' . $i, $data["bik_" . $i]);
			}
		}

		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function uriageformzenkaikensaku()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);
		$Data = $this->request->query('s');
		$id = $Data["id"];

		session_start();
		$session = $this->request->session();

		$data = $_SESSION['pass' . $id];
		$_SESSION['uriageform_date' . $id] = $data["date"]["year"] . "-" . $data["date"]["month"] . "-" . $data["date"]["day"];
		$_SESSION['uriageform_bunrui' . $id] = $data["bunrui"];
		$_SESSION['uriageform_yuubin' . $id] = $data["yuubin"];
		$_SESSION['uriageform_address' . $id] = $data["address"];
		$_SESSION['uriageform_keisyou' . $id] = $data["keisyou"];

		if (isset($data["id"])) {
			$this->set('id', $data["id"]);
			$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
		}
		$yuubin = $Customer[0]->yuubin;
		$address = $Customer[0]->address;
		$keisyou = $Customer[0]->keisyou;
		$furigana = $Customer[0]->furigana;
		$name = $Customer[0]->name;
		$siten = $Customer[0]->siten;
		$namehyouji = $name . " " . $siten;
		if ($keisyou == 1) {
			$keisyou = "様";
		} elseif ($keisyou == 2) {
			$keisyou = "御中";
		} elseif ($keisyou == 3) {
			$keisyou = "殿";
		} else {
			$keisyou = "御中";
		}

		$this->set('namehyouji', $namehyouji);

		$this->set('id', $data["id"]);
		$this->set('yuubin', $yuubin);
		$this->set('address', $address);
		$this->set('keisyou', $keisyou);
		$this->set('furigana', $furigana);
	}

	public function uriageformzenkaikensakuview()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$data = $this->request->getData();

		if (isset($data["id"])) {
			$this->set('id', $data["id"]);
			$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
		}
		$yuubin = $Customer[0]->yuubin;
		$address = $Customer[0]->address;
		$keisyou = $Customer[0]->keisyou;
		$furigana = $Customer[0]->furigana;
		$name = $Customer[0]->name;
		$siten = $Customer[0]->siten;
		$namehyouji = $name . " " . $siten;
		if ($keisyou == 1) {
			$keisyou = "様";
		} elseif ($keisyou == 2) {
			$keisyou = "御中";
		} elseif ($keisyou == 3) {
			$keisyou = "殿";
		} else {
			$keisyou = "御中";
		}

		$this->set('namehyouji', $namehyouji);

		$this->set('id', $data["id"]);
		$this->set('yuubin', $yuubin);
		$this->set('address', $address);
		$this->set('keisyou', $keisyou);
		$this->set('furigana', $furigana);

		$date_sta = $data['date_sta']['year'] . "-" . $data['date_sta']['month'] . "-" . $data['date_sta']['day'];
		$date_fin = $data['date_fin']['year'] . "-" . $data['date_fin']['month'] . "-" . $data['date_fin']['day'];

		$proname = $data['proname'];

		if (empty($data['proname'])) { //pronameの入力がないとき

			$Uriagemasters = $this->Uriagemasters->find()->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'customerId' => $data["id"], 'delete_flag' => 0])->order(["uriagebi" => "desc"])->toArray();
			$arrUriagesyousais = array();
			for ($i = 0; $i < count($Uriagemasters); $i++) {
				$Uriagesyousais = $this->Uriagesyousais->find()->where(['uriagemasterId' => $Uriagemasters[$i]["id"], 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();
				$arrUriagesyousais = array_merge($arrUriagesyousais, $Uriagesyousais);

				if (count($arrUriagesyousais) > 500) {
					break;
				}
			}
		} else { //pronameの入力があるときpronameとcustomerと日にちで絞り込み

			$Uriagemasters = $this->Uriagemasters->find()->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'customerId' => $data["id"], 'delete_flag' => 0])->order(["uriagebi" => "desc"])->toArray();
			$arrUriagesyousais = array();
			for ($i = 0; $i < count($Uriagemasters); $i++) {
				$Uriagesyousais = $this->Uriagesyousais->find()->where(['uriagemasterId' => $Uriagemasters[$i]["id"], 'pro like' => '%' . $proname . '%', 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();
				$arrUriagesyousais = array_merge($arrUriagesyousais, $Uriagesyousais);

				if (count($arrUriagesyousais) > 500) {
					break;
				}
			}
		}

		$this->set('Uriages', $arrUriagesyousais);

		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function uriageformzenkai()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		session_start();
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');

		$session = $this->request->session();
		$Data = $this->request->query('s');
		$id = $Data["id"];

		$data = $_SESSION['pass' . $id];

		$_SESSION['uriageform_date' . $id] = $data["date"]["year"] . "-" . $data["date"]["month"] . "-" . $data["date"]["day"];
		$_SESSION['uriageform_bunrui' . $id] = $data["bunrui"];
		$_SESSION['uriageform_yuubin' . $id] = $data["yuubin"];
		$_SESSION['uriageform_address' . $id] = $data["address"];
		$_SESSION['uriageform_keisyou' . $id] = $data["keisyou"];

		if (isset($data["id"])) {
			$this->set('id', $data["id"]);
			$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
		}
		$yuubin = $Customer[0]->yuubin;
		$address = $Customer[0]->address;
		$keisyou = $Customer[0]->keisyou;
		$furigana = $Customer[0]->furigana;
		$name = $Customer[0]->name;
		$siten = $Customer[0]->siten;
		$namehyouji = $name . " " . $siten;
		if ($keisyou == 1) {
			$keisyou = "様";
		} elseif ($keisyou == 2) {
			$keisyou = "御中";
		} elseif ($keisyou == 3) {
			$keisyou = "殿";
		} else {
			$keisyou = "御中";
		}

		$this->set('namehyouji', $namehyouji);

		$this->set('yuubin', $yuubin);
		$this->set('address', $address);
		$this->set('keisyou', $keisyou);
		$this->set('furigana', $furigana);

		$Uriagemasters = $this->Uriagemasters->find()->where(['customerId' => $data["id"], 'delete_flag' => 0])->order(["uriagebi" => "desc"])->toArray();
		$this->set('Uriages', $Uriagemasters);
	}

	public function uriageformzenkaisyousai()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		session_start();
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');

		$session = $this->request->session();
		$Data = $this->request->query('s');
		$id = $Data["id"];

		$data = $_SESSION['pass' . $id];

		$_SESSION['uriageform_date' . $id] = $data["date"]["year"] . "-" . $data["date"]["month"] . "-" . $data["date"]["day"];
		$_SESSION['uriageform_bunrui' . $id] = $data["bunrui"];
		$_SESSION['uriageform_yuubin' . $id] = $data["yuubin"];
		$_SESSION['uriageform_address' . $id] = $data["address"];
		$_SESSION['uriageform_keisyou' . $id] = $data["keisyou"];

		if (isset($data["id"])) {
			$this->set('id', $data["id"]);
			$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
		}
		$yuubin = $Customer[0]->yuubin;
		$address = $Customer[0]->address;
		$keisyou = $Customer[0]->keisyou;
		$furigana = $Customer[0]->furigana;
		$name = $Customer[0]->name;
		$siten = $Customer[0]->siten;
		$namehyouji = $name . " " . $siten;
		if ($keisyou == 1) {
			$keisyou = "様";
		} elseif ($keisyou == 2) {
			$keisyou = "御中";
		} elseif ($keisyou == 3) {
			$keisyou = "殿";
		} else {
			$keisyou = "御中";
		}

		$this->set('namehyouji', $namehyouji);

		$this->set('yuubin', $yuubin);
		$this->set('address', $address);
		$this->set('keisyou', $keisyou);
		$this->set('furigana', $furigana);

		//		 $Uriagemasters = $this->Uriagemasters->find()->where(['customerId' => $data["id"], 'delete_flag' => 0])->order(["created_at"=>"desc"])->limit(10)->toArray();
		$Uriagemasters = $this->Uriagemasters->find()->where(['customerId' => $data["id"], 'delete_flag' => 0])->order(["uriagebi" => "desc"])->toArray();
		$arrUriagesyousais = array();
		for ($i = 0; $i < count($Uriagemasters); $i++) {
			$Uriagesyousais = $this->Uriagesyousais->find()->where(['uriagemasterId' => $Uriagemasters[$i]["id"], 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();
			$arrUriagesyousais = array_merge($arrUriagesyousais, $Uriagesyousais);

			if (count($arrUriagesyousais) > 500) {
				break;
			}
		}

		$this->set('Uriages', $arrUriagesyousais);
	}

	public function uriagesyuturyokukakunin()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);
		$Data = $this->request->query('s');
		$id = $Data["id"];

		session_start();
		$session = $this->request->session();

		$data = $_SESSION['touroku' . $id];

		$this->set('id', $data["id"]);
		$this->set('name', $data["name"]);
		$this->set('furigana', $data["furigana"]);
		$this->set('yuubin', $data["yuubin"]);
		$this->set('address', $data["address"]);
		$this->set('keisyou', $data["keisyou"]);
		$this->set('bunrui', $data["bunrui"]);

		$month = (int)$data['date']['month'];
		$day = (int)$data['date']['day'];
		$dateexcl = $data['date']['year'] . "年" . $month . "月" . $day . "日";
		$datetouroku = $data['date']['year'] . "-" . $data['date']['month'] . "-" . $data['date']['day'];
		$this->set('dateexcl', $dateexcl);
		$this->set('datetouroku', $datetouroku);

		$numpro = 0;
		$arrayNum = array();
		$arrayZeiritu = array();
		$arrayNumjun = array();
		$numcheck = 0;

		$Uriages = $this->Uriagemasters->find()->where(['delete_flag' => 0])->order(["denpyou_num" => "desc"])->toArray();
		if (isset($Uriages[0])) {
			$denpyou_num = $Uriages[0]->denpyou_num + 1;
		} else {
			$denpyou_num = 10000;
		}
		$this->set('denpyou_num', $denpyou_num);

		for ($i = 1; $i <= $data["num"]; $i++) {

			if (!empty($data["pro_" . $i])) {
				if ($data["num_" . $i] > 0) {
					$arrayNum = $arrayNum + [$i => $data["num_" . $i]];
				} else {
					$numcheck = 1;
				}
			}
		}

		asort($arrayNum);
		$arrayNumjun = array_keys($arrayNum);

		if ($numcheck < 1) {

			$total_price = 0;

			for ($k = 0; $k < count($arrayNumjun); $k++) {

				$i = $arrayNumjun[$k];

				if (!empty($data["pro_" . $i]) && $data["delete_flag_" . $i] == 0) {
					if ($data["num_" . $i] > 0) {
						$arrayNum = $arrayNum + [$i => $data["num_" . $i]];
					} else {
						$numcheck = 1;
					}
				}

				if (!empty($data["pro_" . $i]) && $data["delete_flag_" . $i] == 0) {

					$numpro = $numpro + 1;

					$this->set('tuika', $numpro);

					$this->set('pro_' . $numpro, $data["pro_" . $i]);
					$this->set('amount_' . $numpro, $data["amount_" . $i]);
					$this->set('tani_' . $numpro, $data["tani_" . $i]);
					$this->set('amount_' . $numpro, $data["amount_" . $i]);
					$this->set('tanka_' . $numpro, $data["tanka_" . $i]);
					$this->set('bik_' . $numpro, $data["bik_" . $i]);

					if (strlen($data['tanka_' . $i]) > 0) {
						${"price_" . $numpro} = (int)$data["tanka_" . $i] * (int)$data["amount_" . $i];
						$total_price = $total_price + ${"price_" . $numpro};
						${"zeiritu_" . $numpro} = $data["zeiritu_" . $i];
					} else {
						${"price_" . $numpro} = "";
						${"zeiritu_" . $numpro} = "";
					}
					$this->set('price_' . $numpro, ${"price_" . $numpro});
					$this->set('zeiritu_' . $numpro, ${"zeiritu_" . $numpro});

					if (isset($arrayZeiritu[${"zeiritu_" . $numpro}]) && strlen(${"zeiritu_" . $numpro}) > 0) {
						$arrayZeiritu[${"zeiritu_" . $numpro}]["total_price"] = $arrayZeiritu[${"zeiritu_" . $numpro}]["total_price"] + ${"price_" . $numpro};
						$arrayZeiritu[${"zeiritu_" . $numpro}]["total_tax"] = $arrayZeiritu[${"zeiritu_" . $numpro}]["total_price"] * (int)$arrayZeiritu[${"zeiritu_" . $numpro}]["zeiritu"] / 100;
					} elseif (strlen(${"zeiritu_" . $numpro}) > 0) {
						$arrayZeiritu[${"zeiritu_" . $numpro}]["total_price"] = ${"price_" . $numpro};
						if (${"zeiritu_" . $numpro} == 0) {
							$arrayZeiritu[${"zeiritu_" . $numpro}]["zeiritu"] = "非課税";
						} else {
							$arrayZeiritu[${"zeiritu_" . $numpro}]["zeiritu"] = ${"zeiritu_" . $numpro};
						}
						$arrayZeiritu[${"zeiritu_" . $numpro}]["total_tax"] = ${"price_" . $numpro} * ${"zeiritu_" . $numpro} / 100;
					}
				}
			}
		} else {

			$total_price = 0;

			for ($i = 1; $i <= $data["num"]; $i++) {

				if (!empty($data["pro_" . $i]) && $data["delete_flag_" . $i] == 0) {

					$numpro = $numpro + 1;

					$this->set('tuika', $numpro);

					$this->set('pro_' . $numpro, $data["pro_" . $i]);
					$this->set('amount_' . $numpro, $data["amount_" . $i]);
					$this->set('tani_' . $numpro, $data["tani_" . $i]);
					$this->set('amount_' . $numpro, $data["amount_" . $i]);
					$this->set('tanka_' . $numpro, $data["tanka_" . $i]);
					$this->set('bik_' . $numpro, $data["bik_" . $i]);

					if (strlen($data['tanka_' . $i]) > 0) {
						${"price_" . $numpro} = (int)$data["tanka_" . $i] * (int)$data["amount_" . $i];

						$total_price = $total_price + ${"price_" . $numpro};
						${"zeiritu_" . $numpro} = $data["zeiritu_" . $i];
					} else {
						${"price_" . $numpro} = "";
						${"zeiritu_" . $numpro} = "";
					}
					$this->set('price_' . $numpro, ${"price_" . $numpro});
					$this->set('zeiritu_' . $numpro, ${"zeiritu_" . $numpro});

					if (isset($arrayZeiritu[${"zeiritu_" . $numpro}]) && strlen(${"zeiritu_" . $numpro}) > 0) {
						$arrayZeiritu[${"zeiritu_" . $numpro}]["total_price"] = $arrayZeiritu[${"zeiritu_" . $numpro}]["total_price"] + ${"price_" . $numpro};
						$arrayZeiritu[${"zeiritu_" . $numpro}]["total_tax"] = $arrayZeiritu[${"zeiritu_" . $numpro}]["total_price"] * $arrayZeiritu[${"zeiritu_" . $numpro}]["zeiritu"] / 100;
					} elseif (strlen(${"zeiritu_" . $numpro}) > 0) {
						$arrayZeiritu[${"zeiritu_" . $numpro}]["total_price"] = ${"price_" . $numpro};
						if (${"zeiritu_" . $numpro} == 0) {
							$arrayZeiritu[${"zeiritu_" . $numpro}]["zeiritu"] = "非課税";
						} else {
							$arrayZeiritu[${"zeiritu_" . $numpro}]["zeiritu"] = ${"zeiritu_" . $numpro};
						}
						$arrayZeiritu[${"zeiritu_" . $numpro}]["total_tax"] = ${"price_" . $numpro} * ${"zeiritu_" . $numpro} / 100;
					}
				}
			}
		}
		$arrayZeiritu = array_values($arrayZeiritu);

		$total_price_tax = $total_price * 0.1;
		$this->set('total_price', $total_price);
		$this->set('total_price_tax', $total_price_tax);
		$this->set('arrayZeiritu', $arrayZeiritu);

		//税率8%と10%の税額と合計金額を取得しておく
		$tax_8 = 0;
		$total_8 = 0;
		$tax_10 = 0;
		$total_10 = 0;
		for ($i = 0; $i < count($arrayZeiritu); $i++) {
			if ($arrayZeiritu[$i]["zeiritu"] == 8) {
				$tax_8 = $arrayZeiritu[$i]["total_tax"];
				$total_8 = $arrayZeiritu[$i]["total_tax"] + $arrayZeiritu[$i]["total_price"];
			} elseif ($arrayZeiritu[$i]["zeiritu"] == 10) {
				$tax_10 = $arrayZeiritu[$i]["total_tax"];
				$total_10 = $arrayZeiritu[$i]["total_tax"] + $arrayZeiritu[$i]["total_price"];
			}
		}
		$this->set('tax_8', $tax_8);
		$this->set('total_8', $total_8);
		$this->set('tax_10', $tax_10);
		$this->set('total_10', $total_10);
	}

	public function uriagesyuturyoku()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$data = $this->request->getData();

		$this->set('customerId', $data["id"]);

		if (isset($data["tugihe"])) {

			return $this->redirect(['action' => 'uriageformcustomer']);
		} elseif (!isset($data["name"])) {

			if (!isset($_SESSION)) {
				session_start();
			}
			$session = $this->request->session();
			$_SESSION['uriageform_date'] = "";
			$_SESSION['uriageform_bunrui'] = "";
			$_SESSION['uriageform_yuubin'] = "";
			$_SESSION['uriageform_address'] = "";
			$_SESSION['uriageform_keisyou'] = "";
			$_SESSION['editpass'] = array();

			$dataid = array_keys($data, '同じ宛先で続けて入力');
			$dataarr = explode("_", $dataid[0]);

			return $this->redirect([
				'action' => 'uriageformsyousai',
				's' => ['customerId' => $dataarr[1]]
			]);
		} else {

			$Uriages = $this->Uriagemasters->find()->where(['delete_flag' => 0])->order(["denpyou_num" => "desc"])->toArray();
			if (isset($Uriages[0])) {
				$denpyou_num = $Uriages[0]->denpyou_num + 1;
			} else {
				$denpyou_num = 10000;
			}

			$tourokuArr = array();
			$tourokusyousaiArr = array();

			if ($data["tax_include_flag"] == 1) {
				$tax_include_flag = 1;
			} else {
				$tax_include_flag = 0;
			}
			$tourokuArr = array(
				'denpyou_num' => $denpyou_num, 'bunrui' => $data["bunrui"],
				'customerId' => $data["id"], 'customer' => $data["name"], 'furigana' => $data["furigana"], 'yuubin' => $data["yuubin"], 'address' => $data["address"], 'tax_include_flag' => $tax_include_flag, 'keisyou' => $data["keisyou"], 'uriagebi' => $data["datetouroku"], 'delete_flag' => 0,
				'created_at' => date('Y-m-d H:i:s', strtotime('+9hour'))
			);

			$total_price = 0;
			$total_price_no_tax = 0;
			for ($i = 1; $i <= $data["tuika"]; $i++) {
				${"tourokusyousaiArr_" . $i} = array();

				${"arr_" . $i} = array(
					'pro' => $data["pro_" . $i], 'amount' => $data["amount_" . $i], 'tani' => $data["tani_" . $i], 'tanka' => $data["tanka_" . $i], 'zeiritu' => $data["zeiritu_" . $i],
					'price' => $data["price_" . $i], 'bik' => $data["bik_" . $i]
				);

				${"tourokusyousaiArr_" . $i} = array_merge(${"tourokusyousaiArr_" . $i}, ${"arr_" . $i});

				if (strlen($data['price_' . $i]) > 0) {
					$data["price_" . $i] = $data["price_" . $i];
				} else {
					$data["price_" . $i] = 0;
				}

				if ($data["tax_include_flag"] == 1) {
					$total_price = $total_price + $data["price_" . $i];
				} else {
					$total_price = $total_price + $data["price_" . $i] + $data["price_" . $i] * (int)$data["zeiritu_" . $i] / 100;
				}
				$total_price_no_tax = $total_price_no_tax + $data["price_" . $i];
			}

			$amari = $data["tuika"] % 8;
			$syou = floor($data["tuika"] / 8);

			$bik = "";
			if ($data["tax_8"] > 0) {
				$bik = "※軽減税率対象";
			}

			//エクセル出力
			$filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\納品書.xlsx'; //読み込みたいファイルの指定
			$reader = new XlsxReader();
			$spreadsheet = $reader->load($filepath);
			$sheet = $spreadsheet->getSheetByName("Sheet1");
			$sheet->setCellValue('H1', "伝票番号:" . $denpyou_num);
			$sheet->setCellValue('A2', "〒 " . $data["yuubin"]);

			$addressarr =  explode("_", $data["address"]);

			$sheet->setCellValue('A3', "　　" . $addressarr[0]);

			if (isset($addressarr[1])) {
				$sheet->setCellValue('A4', "　　　　" . $addressarr[1]);
			} else {
				$sheet->setCellValue('A4', "　　");
			}

			$namearr =  explode("_", $data["name"]);

			if (isset($namearr[1])) {
				$sheet->setCellValue('A5', $namearr[0]);
				$sheet->setCellValue('A6', "　　 " . $namearr[1]);
				$sheet->setCellValue('A29', $namearr[0]);
				$sheet->setCellValue('A30', "　　 " . $namearr[1]);
			} else {
				$sheet->unmergeCells('A5:D5');
				$sheet->unmergeCells('A6:D6');
				$sheet->mergeCells('A5:D6');
				$sheet->setCellValue('A5', $namearr[0]);
				$sheet->unmergeCells('A29:D29');
				$sheet->unmergeCells('A30:D30');
				$sheet->mergeCells('A29:D30');
				$sheet->setCellValue('A29', $namearr[0]);
			}

			$sheet->setCellValue('E5', $data["keisyou"]);
			$sheet->setCellValue('G2', $data["dateexcl"]);
			$sheet->setCellValue('C8', $total_price);

			for ($j = 2; $j < 2 + $syou; $j++) {

				$baseSheet = $spreadsheet->getSheet(0);
				$newSheet = $baseSheet->copy();
				$newSheet->setTitle("Sheet" . $j);
				$spreadsheet->addSheet($newSheet);

				$writer = new XlsxWriter($spreadsheet);
			}

			$pro_check = 0;
			$total_price_taxinc = 0;

			if ($amari == 0) { //以下余白がいらない場合

				$sheetnum = $syou + 1;
				$sheet->setCellValue('I9', " 1/" . $sheetnum . "　");
				$sheet->setCellValue('I33', " 1/" . $sheetnum . "　");

				for ($i = 1; $i <= 8; $i++) {

					if ($i == $data["tuika"] + 2) {
						break;
					}

					$num = 11 + $i;
					$num2 = 35 + $i;
					if (empty($data["pro_" . $i]) && $pro_check == 0) {
						${"pro_" . $i} = "以下余白";
						$pro_check = 1;
					} elseif ($data["zeiritu_" . $i] == 8) {
						${"pro_" . $i} = $data["pro_" . $i] . "※";
					} else {
						${"pro_" . $i} = $data["pro_" . $i];
					}

					if ($data["price_" . $i] > 0) {
						$price = $data["price_" . $i];
					} else {
						$price = "　";
					}
					$sheet->setCellValue("A" . $num, ${"pro_" . $i});
					$sheet->setCellValue("A" . $num2, ${"pro_" . $i});
					$sheet->setCellValue("E" . $num, $data["amount_" . $i]);
					$sheet->setCellValue("E" . $num2, $data["amount_" . $i]);
					$sheet->setCellValue("F" . $num, $data["tani_" . $i]);
					$sheet->setCellValue("F" . $num2, $data["tani_" . $i]);
					$sheet->setCellValue("G" . $num, $data["tanka_" . $i]);
					$sheet->setCellValue("G" . $num2, $data["tanka_" . $i]);
					$sheet->setCellValue("H" . $num, $price);
					$sheet->setCellValue("H" . $num2, $price);
					$sheet->setCellValue("I" . $num, $data["bik_" . $i]);
					$sheet->setCellValue("I" . $num2, $data["bik_" . $i]);

					$total_price_taxinc = $total_price_taxinc + $data["price_" . $i];
				}

				if ($syou == 0) { //最後のシートの時

					$sheet->setCellValue("A21", $bik);
					$sheet->setCellValue("A45", $bik);
					if ($data["tax_include_flag"] == 1) { //内税の場合

						$sheet->setCellValue("H20", $total_price);
						$sheet->setCellValue("H21", "");
						$sheet->setCellValue("H23", $total_price);
						$sheet->setCellValue("H44", $total_price);
						$sheet->setCellValue("H45", "");
						$sheet->setCellValue("H47", $total_price);
					} else {

						$sheet->setCellValue("H20", $total_price_no_tax);
						$sheet->setCellValue("H21", $data["tax_8"]);
						$sheet->setCellValue("H22", $data["tax_10"]);
						$sheet->setCellValue("H23", $total_price);
						$sheet->setCellValue("H44", $total_price_no_tax);
						$sheet->setCellValue("H45", $data["tax_8"]);
						$sheet->setCellValue("H46", $data["tax_10"]);
						$sheet->setCellValue("H47", $total_price_no_tax);
					}
				} else {

					$sheet->setCellValue("A21", $bik);
					$sheet->setCellValue("A45", $bik);
					$sheet->setCellValue("H20", "");
					$sheet->setCellValue("H21", "");
					$sheet->setCellValue("H22", "");
					$sheet->setCellValue("H23", "");
					$sheet->setCellValue("H44", "");
					$sheet->setCellValue("H45", "");
					$sheet->setCellValue("H46", "");
					$sheet->setCellValue("H47", "");
				}
			} else { //以下余白がいる場合

				$sheetnum = $syou + 1;
				$sheet->setCellValue('I9', " 1/" . $sheetnum . "　");
				$sheet->setCellValue('I33', " 1/" . $sheetnum . "　");

				for ($i = 1; $i <= 8; $i++) {

					if ($i == $data["tuika"] + 2) {
						break;
					}

					$num = 11 + $i;
					$num2 = 35 + $i;
					if (empty($data["pro_" . $i]) && $pro_check == 0) {
						${"pro_" . $i} = "以下余白";
						$pro_check = 1;
					} elseif ($data["zeiritu_" . $i] == 8) {
						${"pro_" . $i} = $data["pro_" . $i] . "※";
					} else {
						${"pro_" . $i} = $data["pro_" . $i];
					}

					$sheet->setCellValue("A" . $num, ${"pro_" . $i});
					$sheet->setCellValue("A" . $num2, ${"pro_" . $i});

					if ($i < $data["tuika"] + 1) {

						if ($data["price_" . $i] > 0) {
							$price = $data["price_" . $i];
						} else {
							$price = "　";
						}

						$sheet->setCellValue("E" . $num, $data["amount_" . $i]);
						$sheet->setCellValue("E" . $num2, $data["amount_" . $i]);
						$sheet->setCellValue("F" . $num, $data["tani_" . $i]);
						$sheet->setCellValue("F" . $num2, $data["tani_" . $i]);
						$sheet->setCellValue("G" . $num, $data["tanka_" . $i]);
						$sheet->setCellValue("G" . $num2, $data["tanka_" . $i]);
						$sheet->setCellValue("H" . $num, $price);
						$sheet->setCellValue("H" . $num2, $price);
						$sheet->setCellValue("I" . $num, $data["bik_" . $i]);
						$sheet->setCellValue("I" . $num2, $data["bik_" . $i]);

						$total_price_taxinc = $total_price_taxinc + $data["price_" . $i];
					}

					if ($syou == 0) { //最後のシートの時

						$sheet->setCellValue("A21", $bik);
						$sheet->setCellValue("A45", $bik);
						if ($data["tax_include_flag"] == 1) { //内税の場合

							$sheet->setCellValue("H20", $total_price);
							$sheet->setCellValue("H21", "");
							$sheet->setCellValue("H23", $total_price);
							$sheet->setCellValue("H44", $total_price);
							$sheet->setCellValue("H45", "");
							$sheet->setCellValue("H47", $total_price);
						} else {

							$sheet->setCellValue("H20", $total_price_no_tax);
							$sheet->setCellValue("H21", $data["tax_8"]);
							$sheet->setCellValue("H22", $data["tax_10"]);
							$sheet->setCellValue("H23", $total_price);
							$sheet->setCellValue("H44", $total_price_no_tax);
							$sheet->setCellValue("H45", $data["tax_8"]);
							$sheet->setCellValue("H46", $data["tax_10"]);
							$sheet->setCellValue("H47", $total_price_no_tax);
						}
					} else {

						$sheet->setCellValue("A21", $bik);
						$sheet->setCellValue("A45", $bik);
						$sheet->setCellValue("H20", "");
						$sheet->setCellValue("H21", "");
						$sheet->setCellValue("H22", "");
						$sheet->setCellValue("H23", "");
						$sheet->setCellValue("H44", "");
						$sheet->setCellValue("H45", "");
						$sheet->setCellValue("H46", "");
						$sheet->setCellValue("H47", "");
						$sheet->setCellValue("H46", "");
					}
				}
			}

			$writer = new XlsxWriter($spreadsheet);

			$total_price_taxinc = 0;

			for ($j = 2; $j < 2 + $syou; $j++) {

				$sheet = $spreadsheet->getSheetByName("Sheet" . $j);
				$sheet->setCellValue('A2', "〒 " . $data["yuubin"]);
				$addressarr =  explode("_", $data["address"]);

				$sheet->setCellValue('A3', "　　" . $addressarr[0]);

				if (isset($addressarr[1])) {
					$sheet->setCellValue('A4', "　　　　" . $addressarr[1]);
				} else {
					$sheet->setCellValue('A4', "　　");
				}

				$namearr =  explode("_", $data["name"]);
				$sheet->setCellValue('A5', $namearr[0]);

				$sheet->setCellValue('E5', $data["keisyou"]);

				$sheetnum = $syou + 1;
				$sheet->setCellValue('I9', " " . $j . "/" . $sheetnum . "　");
				$sheet->setCellValue('I33', " " . $j . "/" . $sheetnum . "　");

				for ($i = 8 * ($j - 1) + 1; $i <= 8 * $j; $i++) {

					if ($i == $data["tuika"] + 2) {
						break;
					}

					$num = 11 + $i - 8 * ($j - 1);
					$num2 = 35 + $i - 8 * ($j - 1);
					if (empty($data["pro_" . $i]) && $pro_check == 0) {
						${"pro_" . $i} = "以下余白";
						$pro_check = 1;
					} elseif ($data["zeiritu_" . $i] == 8) {
						${"pro_" . $i} = $data["pro_" . $i] . "※";
					} else {
						${"pro_" . $i} = $data["pro_" . $i];
					}

					$sheet->setCellValue("A" . $num, ${"pro_" . $i});
					$sheet->setCellValue("A" . $num2, ${"pro_" . $i});

					if ($i < $data["tuika"] + 1) {

						$sheet->setCellValue("E" . $num, $data["amount_" . $i]);
						$sheet->setCellValue("E" . $num2, $data["amount_" . $i]);
						$sheet->setCellValue("F" . $num, $data["tani_" . $i]);
						$sheet->setCellValue("F" . $num2, $data["tani_" . $i]);
						$sheet->setCellValue("G" . $num, $data["tanka_" . $i]);
						$sheet->setCellValue("G" . $num2, $data["tanka_" . $i]);
						$sheet->setCellValue("H" . $num, $data["price_" . $i]);
						$sheet->setCellValue("H" . $num2, $data["price_" . $i]);
						$sheet->setCellValue("I" . $num, $data["bik_" . $i]);
						$sheet->setCellValue("I" . $num2, $data["bik_" . $i]);

						$total_price_taxinc = $total_price_taxinc + $data["price_" . $i];
					}

					if ($j == $syou + 1) { //最後のシートの時

						$sheet->setCellValue("A21", $bik);
						$sheet->setCellValue("A45", $bik);
						if ($data["tax_include_flag"] == 1) { //内税の場合

							$sheet->setCellValue("H20", $total_price);
							$sheet->setCellValue("H21", "");
							$sheet->setCellValue("H23", $total_price);
							$sheet->setCellValue("H44", $total_price);
							$sheet->setCellValue("H45", "");
							$sheet->setCellValue("H47", $total_price);
						} else {

							$sheet->setCellValue("H20", $total_price_no_tax);
							$sheet->setCellValue("H21", $data["tax_8"]);
							$sheet->setCellValue("H22", $data["tax_10"]);
							$sheet->setCellValue("H23", $total_price);
							$sheet->setCellValue("H44", $total_price_no_tax);
							$sheet->setCellValue("H45", $data["tax_8"]);
							$sheet->setCellValue("H46", $data["tax_10"]);
							$sheet->setCellValue("H47", $total_price);
						}
					} else {

						$sheet->setCellValue("A21", $bik);
						$sheet->setCellValue("A45", $bik);
						$sheet->setCellValue("H20", "");
						$sheet->setCellValue("H21", "");
						$sheet->setCellValue("H22", "");
						$sheet->setCellValue("H23", "");
						$sheet->setCellValue("H44", "");
						$sheet->setCellValue("H45", "");
						$sheet->setCellValue("H46", "");
						$sheet->setCellValue("H47", "");
					}
				}
			}

			$datetime = date('H時i分s秒出力', strtotime('+9hour'));
			$year = date('Y', strtotime('+9hour'));
			$month = date('m', strtotime('+9hour'));
			$day = date('d', strtotime('+9hour'));

			if (is_dir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/納品書/$year/$month/$day")) { //ディレクトリが存在すればOK

				$file_name = $data["name"] . "_" . $datetime . ".xlsx";
				$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/納品書/$year/$month/$day/$file_name"; //出力したいファイルの指定

			} else { //ディレクトリが存在しなければ作成する

				mkdir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/納品書/$year/$month/$day", 0777, true);
				$file_name = $data["name"] . "_" . $datetime . ".xlsx";
				$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/納品書/$year/$month/$day/$file_name"; //出力したいファイルの指定

			}

			$mesxlsx = "「エクセル出力/納品書/" . $year . "/" . $month . "/" . $day . "」フォルダにエクセルシート「" . $file_name . "」が出力されました。";
			$this->set('mesxlsx', $mesxlsx);

			$writer->save($outfilepath);

			//データベース登録
			$uriagemaster = $this->Uriagemasters->patchEntity($uriages, $tourokuArr);
			$connection = ConnectionManager::get('default'); //トランザクション1
			// トランザクション開始2
			$connection->begin(); //トランザクション3
			try { //トランザクション4
				if ($this->Uriagemasters->save($uriagemaster)) {

					$Uriagemasters = $this->Uriagemasters->find('all', ['conditions' => ['denpyou_num' => $denpyou_num, 'delete_flag' => 0]])
						->order(["id" => "desc"])->toArray();
					$uriagemasterId = $Uriagemasters[0]->id;

					for ($i = 1; $i <= $data["tuika"]; $i++) {

						${"tuikatouroku" . $i} = array('num' => $i, 'uriagemasterId' => $uriagemasterId, 'uriagebi' => $data["datetouroku"], 'delete_flag' => 0, 'created_at' => date('Y-m-d H:i:s', strtotime('+9hour')));

						${"tourokusyousaiArr_" . $i} = array_merge(${"tourokusyousaiArr_" . $i}, ${"tuikatouroku" . $i});

						$uriagesyousai = $this->Uriagesyousais->patchEntity($this->Uriagesyousais->newEntity(), ${"tourokusyousaiArr_" . $i});
						if ($this->Uriagesyousais->save($uriagesyousai)) {
						} else {
							$this->Flash->error(__('This data could not be saved. Please, try again.'));
							throw new Exception(Configure::read("M.ERROR.INVALID")); //失敗6
						}
					}

					$Miseikyuus = $this->Miseikyuus->find('all', ['conditions' => ['customerId' => $data["id"], 'delete_flag' => 0]])->toArray();
					if (isset($Miseikyuus[0])) {

						$miseikyuugaku = $Miseikyuus[0]->miseikyuugaku + $total_price;

						$this->Miseikyuus->updateAll(
							['miseikyuugaku' => $miseikyuugaku, 'kousinbi' => date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
							['id'  => $Miseikyuus[0]->id]
						);
					} else {

						$arrMiseikyuu = array(
							'customerId' => $data["id"], 'furigana' => $data["furigana"], 'miseikyuugaku' => $total_price, 'kousinbi' => date('Y-m-d', strtotime('+9hour')),
							'delete_flag' => 0, 'created_at' => date('Y-m-d H:i:s', strtotime('+9hour'))
						);

						$Miseikyuu = $this->Miseikyuus->patchEntity($this->Miseikyuus->newEntity(), $arrMiseikyuu);
						$this->Miseikyuus->save($Miseikyuu);
					}

					$mes = "※下記のように登録されました";
					$this->set('mes', $mes);

					sleep(2);

					$connection->commit(); // コミット5

				} else {

					$mes = "※登録されませんでした";
					$this->set('mes', $mes);
					$this->Flash->error(__('This data could not be saved. Please, try again.'));
					throw new Exception(Configure::read("M.ERROR.INVALID")); //失敗6

				}
			} catch (Exception $e) { //トランザクション7
				//ロールバック8
				$connection->rollback(); //トランザクション9
			} //トランザクション10

		}
	}

	public function uriagekensakuform() //売上照会
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$arrBunrui = [
			'' => '',
			'電気' => '電気',
			'防災' => '防災',
			'管' => '管'
		];
		$this->set('arrBunrui', $arrBunrui);

		$autoCustomers = $this->Customers->find()
			->where(['delete_flag' => 0])->toArray();
		$arrCustomer_list = array();
		for ($j = 0; $j < count($autoCustomers); $j++) {

			if (strlen($autoCustomers[$j]["siten"]) > 0) {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"] . ":" . $autoCustomers[$j]["siten"]);
			} else {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"]);
			}
		}
		$arrCustomer_list = array_unique($arrCustomer_list);
		$arrCustomer_list = array_values($arrCustomer_list);
		$this->set('arrCustomer_list', $arrCustomer_list);
	}

	public function uriagekensakuview() //売上照会
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);
		$data = $this->request->getData();

		$date_sta = $data['date_sta']['year'] . "-" . $data['date_sta']['month'] . "-" . $data['date_sta']['day'];
		$date_fin = $data['date_fin']['year'] . "-" . $data['date_fin']['month'] . "-" . $data['date_fin']['day'];

		$arrcustomer = explode(':', $data["customer"]);
		$customer = $arrcustomer[0];

		if (empty($data['customer'])) {
			$customercheck = 0;
		} else {
			$customercheck = 1;
			$this->set('customer', $customer);
		}
		$this->set('customercheck', $customercheck);

		$proname = $data['proname'];
		$denpyou_num = $data['denpyou_num'];
		$bunrui = $data['bunrui'];

		$date_fin = strtotime($date_fin);

		if (empty($data['denpyou_num'])) {

			$Uriagesyousais = $this->Uriagesyousais->find()->contain('Uriagemasters')->limit(3000)
				->where([
					'Uriagemasters.uriagebi >=' => $date_sta,
					'Uriagemasters.uriagebi <=' => $date_fin,
					'Uriagesyousais.delete_flag' => 0,
					'Uriagemasters.delete_flag' => 0,
					'Uriagemasters.customer like' => '%' . $customer . '%',
					'Uriagemasters.bunrui like' =>  '%' . $bunrui . '%',
					'Uriagesyousais.pro like' => '%' . $proname . '%'
				])->order(["Uriagesyousais.uriagebi" => "DESC"])->toArray();
		} else {

			$Uriagesyousais = $this->Uriagesyousais->find()->contain('Uriagemasters')->limit(3000)
				->where([
					'Uriagemasters.uriagebi >=' => $date_sta,
					'Uriagemasters.uriagebi <=' => $date_fin,
					'Uriagemasters.denpyou_num' =>  $denpyou_num,
					'Uriagesyousais.delete_flag' => 0,
					'Uriagemasters.delete_flag' => 0,
					'Uriagemasters.customer like' => '%' . $customer . '%',
					'Uriagemasters.bunrui like' =>  '%' . $bunrui . '%',
					'Uriagesyousais.pro like' => '%' . $proname . '%'
				])->order(["Uriagesyousais.uriagebi" => "DESC"])->toArray();
		}

		$Uriages = $Uriagesyousais;

		for ($i = 0; $i < count($Uriages); $i++) {
			$Uriages[$i]["delete_flag"] = $Uriages[$i]["uriagemaster"]["uriagebi"];
			$Uriages[$i]["created_at"] = $Uriages[$i]["uriagemaster"]["denpyou_num"];
		}

		foreach ($Uriages as $key => $row) {
			$tmp_uriagebi[$key] = $row["delete_flag"];
			$tmp_denpyou_num[$key] = $row["created_at"];
		}

		if (count($Uriages) > 0) {
			array_multisort(array_map("strtotime", $tmp_uriagebi), SORT_DESC, $Uriages);
			$Uriages = array_values($Uriages);
		}

		$this->set('Uriages', $Uriages);

		session_start();
		$session = $this->request->session();
		$session->delete('editpass');

		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function uriagekensakuedit()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$Data = $this->request->query('s');

		$data = $this->request->getData();

		$dataidatesaki = array_keys($data, '宛先変更');

		if (isset($dataidatesaki[0])) {
			$dataidatesakiarr = explode("_", $dataidatesaki[0]);

			$UriagemastersId = $dataidatesakiarr[0];

			return $this->redirect([
				'action' => 'uriagekensakueditcustomerform',
				's' => ['UriagemastersId' => $UriagemastersId]
			]);
		}

		if (isset($data["id"])) {

			$UriagemastersId = $data["id"];
			$this->set('id', $UriagemastersId);

			$Uriagetotalmoto = $data["Uriagetotalmoto"];
			$this->set('Uriagetotalmoto', $Uriagetotalmoto);
		} else {

			$dataid = array_keys($data, '修正');

			$dataarr = explode("_", $dataid[0]);

			$UriagemastersId = $dataarr[0];
			$this->set('id', $UriagemastersId);

			if (isset($dataarr[1])) {
				$Uriagetotalmoto = $dataarr[1];
			} else {
				$Uriagetotalmoto = $data["Uriagetotalmoto"];
			}

			$this->set('Uriagetotalmoto', $Uriagetotalmoto);
		}

		$Uriagesyousais = $this->Uriagesyousais->find()->where(['uriagemasterId' => $UriagemastersId, 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();
		$this->set('Uriagesyousais', $Uriagesyousais);

		$totalprice_moto = 0;
		for ($i = 0; $i < count($Uriagesyousais); $i++) {
			if ($Uriagesyousais[$i]["tanka"] > 0 && $Uriagesyousais[$i]["amount"] > 0) {
				$totalprice_moto = $totalprice_moto + $Uriagesyousais[$i]["tanka"] * $Uriagesyousais[$i]["amount"];
			}
		}
		$this->set('totalprice_moto', $totalprice_moto);

		$syutsuryokubisyousai = $Uriagesyousais[0]["uriagebi"]->format('Y-m-d');
		$this->set('syutsuryokubisyousai', $syutsuryokubisyousai);

		$Uriagemasters = $this->Uriagemasters->find()->where(['id' => $UriagemastersId])->toArray();

		$syutsuryokubi = $Uriagemasters[0]["uriagebi"]->format('Y年m月d日');
		$this->set('syutsuryokubi', $syutsuryokubi);

		$customer = $Uriagemasters[0]["customer"];
		$this->set('customer', $customer);
		$yuubin = $Uriagemasters[0]["yuubin"];
		$this->set('yuubin', $yuubin);
		$address = $Uriagemasters[0]["address"];
		$this->set('address', $address);
		$keisyou = $Uriagemasters[0]["keisyou"];
		$this->set('keisyou', $keisyou);
		$bunrui = $Uriagemasters[0]["bunrui"];
		$this->set('bunrui', $bunrui);
		$denpyou_num = $Uriagemasters[0]["denpyou_num"];
		$this->set('denpyou_num', $denpyou_num);
		$tax_include_flag = $Uriagemasters[0]["tax_include_flag"];
		$this->set('tax_include_flag', $tax_include_flag);

		$arrZeiritu = [
			10 => 10,
			8 => 8
		];
		$this->set('arrZeiritu', $arrZeiritu);

		$arrBunrui = [
			'電気' => '電気',
			'防災' => '防災',
			'管' => '管'
		];
		$this->set('arrBunrui', $arrBunrui);

		$arrTax = [
			0 => '税別',
			1 => '内税'
		];
		$this->set('arrTax', $arrTax);

		$count = 1;
		$this->set('count', $count);

		if (isset($data["zenkai3"])) { //行追加

			session_start();
			header('Expires:-1');
			header('Cache-Control:');
			header('Pragma:');

			$session = $this->request->session();

			$_SESSION['editpass'] = $data;
			return $this->redirect([
				'action' => 'uriagekensakuedittuika',
				's' => ['get' => 1]
			]);
		} elseif (isset($data["zenkai0"])) { //前回検索

			session_start();
			header('Expires:-1');
			header('Cache-Control:');
			header('Pragma:');

			$session = $this->request->session();

			$_SESSION['editpass'] = $data;

			return $this->redirect([
				'action' => 'uriagekensakueditzenkaikensaku',
				's' => ['get' => 1]
			]);
		} elseif (isset($data["confirm"])) {

			session_start();
			header('Expires:-1');
			header('Cache-Control:');
			header('Pragma:');

			$session = $this->request->session();

			$_SESSION['editpass'] = $data;
			return $this->redirect([
				'action' => 'uriagekensakueditconfirm',
				's' => ['get' => 1]
			]);
		}

		session_start();
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function uriagekensakuedittuika()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$getData = $this->request->getData();

		$arrUriagesyousaiId = array_keys($getData, '選択');
		if (isset($arrUriagesyousaiId[0])) { //検索から来た時

			session_start();
			$session = $this->request->session();
			$data = $_SESSION['editpass'];
		} elseif (isset($getData["zenkai4"])) { //行追加された場合

			$data = $getData;
			session_start();
			$session = $this->request->session();
			$_SESSION['editpass'] = $data;
		} else { //最初に来た時,「前回」押したとき

			session_start();
			header('Expires:-1');
			header('Cache-Control:');
			header('Pragma:');

			$session = $this->request->session();

			if (isset($getData["id"])) {
				$_SESSION['editpass'] = $getData;
			}

			$data = $_SESSION['editpass'];
		}

		$arrZeiritu = [
			10 => 10,
			8 => 8
		];
		$this->set('arrZeiritu', $arrZeiritu);

		$arrBunrui = [
			'' => '',
			'電気' => '電気',
			'防災' => '防災',
			'管' => '管'
		];
		$this->set('arrBunrui', $arrBunrui);

		$arrTax = [
			0 => '税別',
			1 => '内税'
		];
		$this->set('arrTax', $arrTax);

		$UriagemastersId = $data["id"];
		$this->set('id', $UriagemastersId);

		$Uriagesyousaismoto = $this->Uriagesyousais->find()->where(['uriagemasterId' => $UriagemastersId, 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();

		$totalprice_moto = 0;
		for ($i = 0; $i < count($Uriagesyousaismoto); $i++) {
			if ($Uriagesyousaismoto[$i]["tanka"] > 0 && $Uriagesyousaismoto[$i]["amount"] > 0) {
				$totalprice_moto = $totalprice_moto + $Uriagesyousaismoto[$i]["tanka"] * $Uriagesyousaismoto[$i]["amount"];
			}
		}
		$this->set('totalprice_moto', $totalprice_moto);

		$Uriagemasters = $this->Uriagemasters->find()->where(['id' => $UriagemastersId])->toArray();

		$syutsuryokubi = $Uriagemasters[0]["uriagebi"]->format('Y年m月d日');
		$this->set('syutsuryokubi', $syutsuryokubi);

		$customer = $Uriagemasters[0]["customer"];
		$this->set('customer', $customer);
		$yuubin = $Uriagemasters[0]["yuubin"];
		$this->set('yuubin', $yuubin);
		$address = $Uriagemasters[0]["address"];
		$this->set('address', $address);
		$keisyou = $Uriagemasters[0]["keisyou"];
		$this->set('keisyou', $keisyou);
		$address = $Uriagemasters[0]["address"];
		$this->set('address', $address);
		$bunrui = $data["bunrui"];
		$this->set('bunrui', $bunrui);
		$tax_include_flag = $data["tax_include_flag"];
		$this->set('tax_include_flag', $tax_include_flag);
		$denpyou_num = $Uriagemasters[0]["denpyou_num"];
		$this->set('denpyou_num', $denpyou_num);

		$total_price_moto = $data["Uriagetotalmoto"];
		$this->set('Uriagetotalmoto', $total_price_moto);
		$seikyuubi = $data['syutsuryokubi']['year'] . "-" . $data['syutsuryokubi']['month'] . "-" . $data['syutsuryokubi']['day'];
		$this->set('syutsuryokubisyousai', $seikyuubi);

		for ($i = 0; $i <= $data['num']; $i++) {

			$Uriagesyousais[] = [
				'pro' =>  $data['pro_' . $i], 'amount' =>  $data['amount_' . $i], 'tani' =>  $data['tani_' . $i],
				'tanka' =>  $data['tanka_' . $i], 'zeiritu' =>  $data['zeiritu_' . $i], 'bik' =>  $data['bik_' . $i], 'delete_flag' =>  $data['delete_flag' . $i],
				'id'  => $data['uriagesyousaiId' . $i]
			];
		}

		if (isset($getData["editzenkaikobetu"])) { //選択したとき

			$arreditzenkaikobetu = array();
			for ($i = 0; $i < $getData["num_max"]; $i++) {

				if (strlen($getData["select" . $i]) > 0) {

					$arreditzenkaikobetu[] = [
						"num" => $getData["select" . $i],
						"id" => $getData[$i]
					];
				}
			}

			foreach ((array) $arreditzenkaikobetu as $key => $value) {
				$sort[$key] = $value['num'];
			}
			array_multisort($sort, SORT_ASC, $arreditzenkaikobetu);

			for ($i = 0; $i < count($arreditzenkaikobetu); $i++) {

				$UriagesyousaiId = $arreditzenkaikobetu[$i]["id"];
				$Uriagesyousaiskensaku = $this->Uriagesyousais->find()->where(['id' => $UriagesyousaiId])->toArray();

				$Uriagesyousais[] = [
					'pro' =>  $Uriagesyousaiskensaku[0]["pro"], 'amount' => $Uriagesyousaiskensaku[0]["amount"],
					'tani' =>  $Uriagesyousaiskensaku[0]["tani"],
					'tanka' => $Uriagesyousaiskensaku[0]["tanka"], 'zeiritu' => $Uriagesyousaiskensaku[0]["zeiritu"], 'bik' => $Uriagesyousaiskensaku[0]["bik"], 'delete_flag' => 0,
					'id'  => "new_data"
				];
				$this->set('Uriagesyousais', $Uriagesyousais);

				$tuikanum = $data['num'] + 1;
				$data["num"] = $data['num'] + 1;
				$data["pro_" . $tuikanum] = $Uriagesyousaiskensaku[0]["pro"];
				$data["amount_" . $tuikanum] = $Uriagesyousaiskensaku[0]["amount"];
				$data["tani_" . $tuikanum] = $Uriagesyousaiskensaku[0]["tani"];
				$data["tanka_" . $tuikanum] = $Uriagesyousaiskensaku[0]["tanka"];
				$data["zeiritu_" . $tuikanum] = $Uriagesyousaiskensaku[0]["zeiritu"];
				$data["bik_" . $tuikanum] = $Uriagesyousaiskensaku[0]["bik"];
				$data["delete_flag" . $tuikanum] = 0;
				$data["uriagesyousaiId" . $tuikanum] = "new_data";
			}
			$_SESSION['editpass'] = $data;
		} else {

			$Uriagesyousais[] = [
				'pro' =>  "", 'amount' => "", 'tani' =>  "",
				'tanka' => "", 'zeiritu' => 10, 'bik' => "", 'delete_flag' => "",
				'id'  => "new_data"
			];
			$this->set('Uriagesyousais', $Uriagesyousais);
		}

		if (isset($data["confirm"])) {

			$session = $this->request->session();

			$_SESSION['editpass'] = $data;
			return $this->redirect([
				'action' => 'uriagekensakueditconfirm',
				's' => ['get' => 1]
			]);
		} elseif (isset($getData["zenkai1"])) { //前回検索

			$session = $this->request->session();

			$_SESSION['editpass'] = $data;
			return $this->redirect([
				'action' => 'uriagekensakueditzenkaikensaku',
				's' => ['get' => 1]
			]);
		}

		if (!isset($_SESSION)) {
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function uriagekensakueditzenkaikensaku()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		session_start();
		$session = $this->request->session();

		$data = $_SESSION['editpass'];

		$Uriagemasters = $this->Uriagemasters->find()->where(['id' => $data["id"]])->toArray();

		$customer = $Uriagemasters[0]["customer"];
		$this->set('namehyouji', $customer);
	}

	public function uriagekensakueditzenkaikensakuview()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		session_start();
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');

		$session = $this->request->session();

		$datasession = $_SESSION['editpass'];
		$Uriagemasters = $this->Uriagemasters->find()->where(['id' => $datasession["id"]])->toArray();

		$customer = $Uriagemasters[0]["customer"];
		$this->set('namehyouji', $customer);
		$customerId = $Uriagemasters[0]["customerId"];

		$data = $this->request->getData();

		$date_sta = $data['date_sta']['year'] . "-" . $data['date_sta']['month'] . "-" . $data['date_sta']['day'];
		$date_fin = $data['date_fin']['year'] . "-" . $data['date_fin']['month'] . "-" . $data['date_fin']['day'];

		$proname = $data['proname'];

		if (empty($data['proname'])) { //pronameの入力がないとき

			$Uriagemasters = $this->Uriagemasters->find()->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'customerId' => $customerId, 'delete_flag' => 0])->order(["uriagebi" => "desc"])->toArray();
			$arrUriagesyousais = array();
			for ($i = 0; $i < count($Uriagemasters); $i++) {
				$Uriagesyousais = $this->Uriagesyousais->find()->where(['uriagemasterId' => $Uriagemasters[$i]["id"], 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();
				$arrUriagesyousais = array_merge($arrUriagesyousais, $Uriagesyousais);

				if (count($arrUriagesyousais) > 500) {
					break;
				}
			}
		} else { //pronameの入力があるときpronameとcustomerと日にちで絞り込み

			$Uriagemasters = $this->Uriagemasters->find()->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'customerId' => $customerId, 'delete_flag' => 0])->order(["uriagebi" => "desc"])->toArray();
			$arrUriagesyousais = array();
			for ($i = 0; $i < count($Uriagemasters); $i++) {
				$Uriagesyousais = $this->Uriagesyousais->find()->where(['uriagemasterId' => $Uriagemasters[$i]["id"], 'pro like' => '%' . $proname . '%', 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();
				$arrUriagesyousais = array_merge($arrUriagesyousais, $Uriagesyousais);

				if (count($arrUriagesyousais) > 500) {
					break;
				}
			}
		}

		$this->set('Uriages', $arrUriagesyousais);

		if (!isset($_SESSION)) {
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function uriagekensakueditconfirm()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		session_start();
		$session = $this->request->session();

		$data = $_SESSION['editpass'];

		$UriagemastersId = $data["id"];
		$this->set('id', $UriagemastersId);

		$Uriagesyousaismoto = $this->Uriagesyousais->find()->where(['uriagemasterId' => $UriagemastersId, 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();

		$totalprice_moto = 0;
		for ($i = 0; $i < count($Uriagesyousaismoto); $i++) {
			if ($Uriagesyousaismoto[$i]["tanka"] > 0 && $Uriagesyousaismoto[$i]["amount"] > 0) {
				$totalprice_moto = $totalprice_moto + $Uriagesyousaismoto[$i]["tanka"] * $Uriagesyousaismoto[$i]["amount"];
			}
		}
		$this->set('totalprice_moto', $totalprice_moto);

		$Uriagemastersmoto = $this->Uriagemasters->find()->where(['id' => $data['id']])->toArray();

		$total_price_moto = $data["Uriagetotalmoto"];
		$bunrui = $data["bunrui"];
		$this->set('bunrui', $bunrui);
		$tax_include_flag = $data["tax_include_flag"];

		if ($tax_include_flag == 0) {
			$tax_include = "税別";
		} else {
			$tax_include = "内税";
		}
		$this->set('tax_include', $tax_include);

		$seikyuubi = $data['syutsuryokubi']['year'] . "-" . $data['syutsuryokubi']['month'] . "-" . $data['syutsuryokubi']['day'];

		$Uriagemasters = $this->Uriagemasters->find()->where(['id' => $UriagemastersId])->toArray();
		$syutsuryokubi = $Uriagemasters[0]["uriagebi"]->format('Y年m月d日');
		$this->set('syutsuryokubi', $syutsuryokubi);

		$customer = $Uriagemasters[0]["customer"];
		$this->set('customer', $customer);
		$yuubin = $Uriagemasters[0]["yuubin"];
		$this->set('yuubin', $yuubin);
		$address = $Uriagemasters[0]["address"];
		$this->set('address', $address);
		$keisyou = $Uriagemasters[0]["keisyou"];
		$this->set('keisyou', $keisyou);
		$denpyou_num = $Uriagemasters[0]["denpyou_num"];
		$this->set('denpyou_num', $denpyou_num);

		$totalprice_new = 0;
		$mb_strlen_check = 0;
		$Uriagesyousais = array();
		for ($i = 0; $i <= $data['num']; $i++) {

			if (strlen($data['tanka_' . $i]) > 0 && strlen($data['amount_' . $i]) > 0) {
				${"zeiritu" . $i} = $data['zeiritu_' . $i];
				${"price" . $i} = $data['tanka_' . $i] * $data['amount_' . $i];
				$totalprice_new = $totalprice_new + ${"price" . $i};
			} else {
				${"price" . $i} = "";
				${"zeiritu" . $i} = "";
			}

			if ($data['delete_flag' . $i] == 0) {
				$Uriagesyousais[] = [
					'num' => $data['num_' . $i],
					'pro' => $data['pro_' . $i],
					'amount' => $data['amount_' . $i],
					'tani' => $data['tani_' . $i],
					'tanka' => $data['tanka_' . $i],
					'zeiritu' => ${"zeiritu" . $i},
					'price' => ${"price" . $i},
					'bik' => $data['bik_' . $i],
				];

				if (mb_strwidth($data['pro_' . $i]) > 49) {

					$mb_strlen_check = 1;
				}
			}
		}
		$this->set('totalprice_new', $totalprice_new);

		foreach ($Uriagesyousais as $key => $value) {
			$sort_keys[$key] = $value['num'];
		}

		array_multisort($sort_keys, SORT_ASC, $Uriagesyousais);

		$this->set('mb_strlen_check', $mb_strlen_check);
		$this->set('Uriagesyousais', $Uriagesyousais);

		if ($data["delete_flag_all"] == 0 && $mb_strlen_check == 0) {
			$mess = "以下のように更新します。よろしければ決定ボタンを押してください。";
		} elseif ($mb_strlen_check == 0) {
			$mess = "以下のデータを削除します。よろしければ決定ボタンを押してください。";
		} else {
			$mess = "２５文字以上の品名は登録できません。戻るボタンから登録を修正してください。";
		}
		$this->set('mess', $mess);
	}

	public function uriagekensakueditdo()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		session_start();
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');

		$session = $this->request->session();

		$data = $_SESSION['editpass'];
		$UriagemastersId = $data["id"];
		$this->set('id', $UriagemastersId);

		$Uriagesyousaismoto = $this->Uriagesyousais->find()->where(['uriagemasterId' => $UriagemastersId, 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();

		$totalprice_moto = 0;
		for ($i = 0; $i < count($Uriagesyousaismoto); $i++) {
			if ($Uriagesyousaismoto[$i]["tanka"] > 0 && $Uriagesyousaismoto[$i]["amount"] > 0) {
				$totalprice_moto = $totalprice_moto + $Uriagesyousaismoto[$i]["tanka"] * $Uriagesyousaismoto[$i]["amount"];
			}
		}
		$this->set('totalprice_moto', $totalprice_moto);

		$totalprice_new = 0;
		$mb_strlen_check = 0;
		$Uriagesyousais = array();
		for ($i = 0; $i <= $data['num']; $i++) {

			if (strlen($data['tanka_' . $i]) > 0 && strlen($data['amount_' . $i]) > 0) {
				${"price" . $i} = $data['tanka_' . $i] * $data['amount_' . $i];
				$totalprice_new = $totalprice_new + ${"price" . $i};
			}
		}
		$this->set('totalprice_new', $totalprice_new);

		$Uriagemastersmoto = $this->Uriagemasters->find()->where(['id' => $data['id']])->toArray();

		$total_price_moto = $data["Uriagetotalmoto"];
		$bunrui = $data["bunrui"];
		$this->set('bunrui', $bunrui);
		$tax_include_flag = $data["tax_include_flag"];

		if ($tax_include_flag == 0) {
			$tax_include = "税別";
		} else {
			$tax_include = "内税";
		}
		$this->set('tax_include', $tax_include);

		$seikyuubi = $data['syutsuryokubi']['year'] . "-" . $data['syutsuryokubi']['month'] . "-" . $data['syutsuryokubi']['day'];

		if ($data["delete_flag_all"] == 1) { //alldeleteの場合
			$mess = "以下のデータを削除しました。";
			$this->set('mess', $mess);

			$Uriagemasters = $this->Uriagemasters->patchEntity($this->Uriagemasters->newEntity(), $data);
			$connection = ConnectionManager::get('default'); //トランザクション1
			// トランザクション開始2
			$connection->begin(); //トランザクション3
			try { //トランザクション4
				if ($this->Uriagemasters->updateAll(
					['delete_flag' => 1],
					['id'  => $data['id']]
				)) {

					$this->Seikyuus->updateAll(['delete_flag' => 1], ['id'  => $Uriagemastersmoto[0]["seikyuuId"]]);
					$Uriagemastersupdate = $this->Uriagemasters->find()->where(['seikyuuId'  => $Uriagemastersmoto[0]["seikyuuId"]])->toArray();
					for ($i = 0; $i < count($Uriagemastersupdate); $i++) {
						$this->Uriagemasters->updateAll(['seikyuuId' => 0], ['seikyuuId'  => $Uriagemastersmoto[0]["seikyuuId"]]);
					}

					//seikyuuId=0の場合はmiseikyuuの金額もupdate
					$Uriagemasters = $this->Uriagemasters->find()->where(['id' => $data['id'], 'seikyuuId' => 0])->toArray();

					if (isset($Uriagemasters[0])) {

						$Miseikyuus = $this->Miseikyuus->find('all', ['conditions' => ['customerId' => $Uriagemasters[0]->customerId, 'delete_flag' => 0]])->toArray();
						if (isset($Miseikyuus[0])) {

							$Uriagesyousais = $this->Uriagesyousais->find()
								->where(['uriagemasterId' => $UriagemastersId, 'delete_flag' => 0])->toArray();

							$Uriagetotalmaster = 0;

							for ($i = 0; $i < count($Uriagesyousais); $i++) {

								if (!empty($Uriagesyousais[$i]->price)) {

									$Uriagetotalmaster = $Uriagetotalmaster + $Uriagesyousais[$i]->price;
								}
							}
							$total_price = $Uriagetotalmaster * 1.1;

							$miseikyuugaku = $Miseikyuus[0]->miseikyuugaku - $total_price;
							if ($miseikyuugaku < 0) {
								$miseikyuugaku = 0;
							}

							$this->Miseikyuus->updateAll(
								['miseikyuugaku' => $miseikyuugaku, 'kousinbi' => date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
								['id'  => $Miseikyuus[0]->id]
							);
						}
					}

					$connection->commit(); // コミット5

				} else {

					$mes = "※更新されませんでした";
					$this->set('mes', $mes);
					$this->Flash->error(__('This data could not be saved. Please, try again.'));
					throw new Exception(Configure::read("M.ERROR.INVALID")); //失敗6

				}
			} catch (Exception $e) { //トランザクション7
				//ロールバック8
				$connection->rollback(); //トランザクション9
			} //トランザクション10

		} else { //alldeleteではない場合

			$mess = "以下のように更新しました。";
			$this->set('mess', $mess);

			$Uriagemasters = $this->Uriagemasters->patchEntity($this->Uriagemasters->newEntity(), $data);
			$connection = ConnectionManager::get('default'); //トランザクション1
			// トランザクション開始2
			$connection->begin(); //トランザクション3
			try { //トランザクション4
				if ($this->Uriagemasters->updateAll(
					[
						'uriagebi' => $seikyuubi, 'tax_include_flag' => $tax_include_flag,
						'bunrui' => $bunrui, 'seikyuuId' => 0, 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))
					],
					['id'  => $data['id']]
				)) {

					$this->Seikyuus->updateAll(['delete_flag' => 1], ['id'  => $Uriagemastersmoto[0]["seikyuuId"]]);

					$Uriagemastersupdate = $this->Uriagemasters->find()->where(['seikyuuId'  => $Uriagemastersmoto[0]["seikyuuId"]])->toArray();
					for ($i = 0; $i < count($Uriagemastersupdate); $i++) {
						$this->Uriagemasters->updateAll(['seikyuuId' => 0], ['seikyuuId'  => $Uriagemastersmoto[0]["seikyuuId"]]);
					}

					for ($i = 0; $i <= $data['num']; $i++) {

						if ($data['uriagesyousaiId' . $i] === "new_data") { //新規登録

							if (strlen($data['tanka_' . $i]) > 0 && strlen($data['amount_' . $i]) > 0) {
								${"price" . $i} = $data['tanka_' . $i] * $data['amount_' . $i];
							} else {
								${"price" . $i} = "";
							}

							$arrnew_data = [
								'uriagemasterId' => $data['id'],
								'num' =>  $data['num_' . $i],
								'pro' =>  $data['pro_' . $i],
								'amount' =>  $data['amount_' . $i],
								'tani' =>  $data['tani_' . $i],
								'tanka' =>  $data['tanka_' . $i],
								'zeiritu' =>  $data['zeiritu_' . $i],
								'price' =>  ${"price" . $i},
								'bik' =>  $data['bik_' . $i],
								'uriagebi' =>  $seikyuubi,
								'delete_flag' =>  $data['delete_flag' . $i],
								'created_at' => date('Y-m-d H:i:s', strtotime('+9hour'))
							];
							$Uriagesyousais = $this->Uriagesyousais->patchEntity($this->Uriagesyousais->newEntity(), $arrnew_data);
							$this->Uriagesyousais->save($Uriagesyousais);
						} else { //アップデート

							if (strlen($data['tanka_' . $i]) > 0 && strlen($data['amount_' . $i]) > 0) {
								${"zeiritu" . $i} = $data['zeiritu_' . $i];
								${"price" . $i} = $data['tanka_' . $i] * $data['amount_' . $i];
							} else {
								${"zeiritu" . $i} = "";
								${"price" . $i} = "";
							}

							$this->Uriagesyousais->updateAll(
								[
									'uriagebi' => $seikyuubi, 'num' =>  $data['num_' . $i], 'pro' =>  $data['pro_' . $i], 'amount' =>  $data['amount_' . $i], 'tani' =>  $data['tani_' . $i],
									'tanka' =>  $data['tanka_' . $i], 'zeiritu' =>  ${"zeiritu" . $i}, 'price' =>  ${"price" . $i}, 'bik' =>  $data['bik_' . $i], 'delete_flag' =>  $data['delete_flag' . $i]
								],
								['id'  => $data['uriagesyousaiId' . $i]]
							);
						}
					}

					//seikyuuId=0の場合はmiseikyuuの金額もupdate
					$Uriagemasters = $this->Uriagemasters->find()->where(['id' => $data['id'], 'seikyuuId' => 0])->toArray();

					if (isset($Uriagemasters[0])) {

						$Miseikyuus = $this->Miseikyuus->find('all', ['conditions' => ['customerId' => $Uriagemasters[0]->customerId, 'delete_flag' => 0]])->toArray();
						if (isset($Miseikyuus[0])) {

							$Uriagesyousais = $this->Uriagesyousais->find()
								->where(['uriagemasterId' => $UriagemastersId, 'delete_flag' => 0])->toArray();

							$Uriagetotalmaster = 0;

							for ($i = 0; $i < count($Uriagesyousais); $i++) {

								if (!empty($Uriagesyousais[$i]->price)) {

									$Uriagetotalmaster = $Uriagetotalmaster + $Uriagesyousais[$i]->price;
								}
							}
							$Uriagetotal = $Uriagetotalmaster * 1.1;

							$miseikyuugaku = $Miseikyuus[0]->miseikyuugaku - $data['Uriagetotalmoto'] + $Uriagetotal;

							if ($miseikyuugaku < 0) {
								$miseikyuugaku = 0;
							}

							$this->Miseikyuus->updateAll(
								['miseikyuugaku' => $miseikyuugaku, 'kousinbi' => date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
								['id'  => $Miseikyuus[0]->id]
							);
						}
					}

					$connection->commit(); // コミット5

				} else {

					$mes = "※更新されませんでした";
					$this->set('mes', $mes);
					$this->Flash->error(__('This data could not be saved. Please, try again.'));
					throw new Exception(Configure::read("M.ERROR.INVALID")); //失敗6

				}
			} catch (Exception $e) { //トランザクション7
				//ロールバック8
				$connection->rollback(); //トランザクション9
			} //トランザクション10

		}

		$Uriagemasters = $this->Uriagemasters->find()->where(['id' => $UriagemastersId])->toArray();
		$syutsuryokubi = $Uriagemasters[0]["uriagebi"]->format('Y年m月d日');
		$this->set('syutsuryokubi', $syutsuryokubi);

		$customer = $Uriagemasters[0]["customer"];
		$this->set('customer', $customer);
		$address = $Uriagemasters[0]["address"];
		$this->set('address', $address);
		$yuubin = $Uriagemasters[0]["yuubin"];
		$this->set('yuubin', $yuubin);
		$keisyou = $Uriagemasters[0]["keisyou"];
		$this->set('keisyou', $keisyou);
		$denpyou_num = $Uriagemasters[0]["denpyou_num"];
		$this->set('denpyou_num', $denpyou_num);

		$Uriagesyousais = $this->Uriagesyousais->find()
			->where(['uriagemasterId' => $UriagemastersId, 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();
		$this->set('Uriagesyousais', $Uriagesyousais);
	}

	public function uriagekensakueditcustomerform()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$Data = $this->request->query('s');

		$Uriagemasters = $this->Uriagemasters->find()->where(['id' => $Data["UriagemastersId"]])->toArray();
		$customerId = $Uriagemasters[0]["customerId"];

		$Customers = $this->Customers->find()->where(['id' => $customerId])->toArray();

		$this->set('customername', $Customers[0]["name"]);
		$this->set('yuubin', $Customers[0]["yuubin"]);
		$this->set('address', $Customers[0]["address"]);
		$this->set('UriagemastersId', $Data["UriagemastersId"]);

		$autoCustomers = $this->Customers->find()
			->where(['delete_flag' => 0])->toArray();
		$arrCustomer_list = array();
		for ($j = 0; $j < count($autoCustomers); $j++) {

			if (strlen($autoCustomers[$j]["siten"]) > 0) {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"] . ":" . $autoCustomers[$j]["siten"]);
			} else {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"]);
			}
		}
		$arrCustomer_list = array_unique($arrCustomer_list);
		$arrCustomer_list = array_values($arrCustomer_list);
		$this->set('arrCustomer_list', $arrCustomer_list);

		if (!isset($_SESSION)) {
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function uriagekensakueditcustomerconfirm()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$data = $this->request->getData();

		$Uriagemasters = $this->Uriagemasters->find()->where(['id' => $data["UriagemastersId"]])->toArray();
		$customerId = $Uriagemasters[0]["customerId"];

		$Customers = $this->Customers->find()->where(['id' => $customerId])->toArray();

		$this->set('customername', $Customers[0]["name"]);
		$this->set('yuubin', $Customers[0]["yuubin"]);
		$this->set('address', $Customers[0]["address"]);
		$this->set('UriagemastersId', $data["UriagemastersId"]);

		$Customersnew = $this->Customers->find()->where(['name' => $data["name"], 'delete_flag' => 0])->toArray();
		$this->set('customernamenew', $Customersnew[0]["name"]);
		$this->set('yuubinnew', $Customersnew[0]["yuubin"]);
		$this->set('addressnew', $Customersnew[0]["address"]);
		$this->set('Customersnewid', $Customersnew[0]["id"]);
	}

	public function uriagekensakueditcustomerdo()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$data = $this->request->getData();
		/*
				echo "<pre>";
				print_r($data);
				echo "</pre>";
				*/
		$Uriagemasters = $this->Uriagemasters->find()->where(['id' => $data["UriagemastersId"]])->toArray();
		$customerId = $Uriagemasters[0]["customerId"];

		$Customers = $this->Customers->find()->where(['id' => $customerId])->toArray();

		$this->set('customername', $Customers[0]["name"]);
		$this->set('yuubin', $Customers[0]["yuubin"]);
		$this->set('address', $Customers[0]["address"]);
		$this->set('UriagemastersId', $data["UriagemastersId"]);

		$Customersnew = $this->Customers->find()->where(['id' => $data["Customersnewid"]])->toArray();
		$this->set('customernamenew', $Customersnew[0]["name"]);
		$this->set('yuubinnew', $Customersnew[0]["yuubin"]);
		$this->set('addressnew', $Customersnew[0]["address"]);
		$this->set('Customersnewid', $Customersnew[0]["id"]);

		$keisyou = $Customersnew[0]->keisyou;
		if ($keisyou == 1) {
			$keisyou = '様';
		} elseif ($keisyou == 2) {
			$keisyou = '御中';
		} elseif ($keisyou == 3) {
			$keisyou = "殿";
		} else {
			$keisyou = "御中";
		}
		$furigana = $Customersnew[0]->furigana;

		$connection = ConnectionManager::get('default'); //トランザクション1
		// トランザクション開始2
		$connection->begin(); //トランザクション3
		try { //トランザクション4
			if ($this->Uriagemasters->updateAll(
				[
					'customerId' => $Customersnew[0]["id"],
					'customer' => $Customersnew[0]["name"],
					'furigana' => $Customersnew[0]["furigana"],
					'keisyou' => $keisyou,
					'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))
				],
				['id'  => $data["UriagemastersId"]]
			)) {

				$mess = "※更新されました";
				$this->set('mess', $mess);
				$connection->commit(); // コミット5

			} else {

				$mess = "※更新されませんでした";
				$this->set('mess', $mess);
				$this->Flash->error(__('This data could not be saved. Please, try again.'));
				throw new Exception(Configure::read("M.ERROR.INVALID")); //失敗6

			}
		} catch (Exception $e) { //トランザクション7
			//ロールバック8
			$connection->rollback(); //トランザクション9
		} //トランザクション10

	}

	public function nyuukinformcustomer()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0']])->order(['furigana' => 'ASC']);
		$arrCustomer = array();
		foreach ($arrCustomers as $value) {
			$furigana = $value->furigana;
			$furigana = mb_substr($furigana, 0, 1);;
			$arrCustomer[] = array($value->id => $furigana . " - " . $value->name . ' ' . $value->siten);
		}
		$this->set('arrCustomer', $arrCustomer);

		$autoCustomers = $this->Customers->find()
			->where(['delete_flag' => 0])->toArray();
		$arrCustomer_list = array();
		for ($j = 0; $j < count($autoCustomers); $j++) {

			if (strlen($autoCustomers[$j]["siten"]) > 0) {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"] . ":" . $autoCustomers[$j]["siten"]);
			} else {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"]);
			}
		}
		$arrCustomer_list = array_unique($arrCustomer_list);
		$arrCustomer_list = array_values($arrCustomer_list);
		$this->set('arrCustomer_list', $arrCustomer_list);
	}

	public function nyuukinformcustomerfurigana()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$Data = $this->request->query('s');
		$data = $Data['data'];

		$furigana = $data["nyuryokufurigana"];

		$arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0', 'furigana like' => '%' . $furigana . '%']])->order(['furigana' => 'ASC']);
		$arrCustomer = array();
		foreach ($arrCustomers as $value) {
			$arrCustomer[] = array($value->id => $value->name . ' ' . $value->siten);
		}
		$this->set('arrCustomer', $arrCustomer);
	}

	public function nyuukinform()
	{
		$data = $this->request->getData();
		$mess = "";
		$this->set('mess', $mess);

		$Data = $this->request->query('s');
		if (isset($Data["mess"])) {

			$mess = $Data["mess"];
			$this->set('mess', $mess);

			$data = $Data;
		}

		if (!empty($data["nyuryokufurigana"])) {

			return $this->redirect([
				'action' => 'nyuukinformcustomerfurigana',
				's' => ['data' => $data]
			]);
		}

		if (!empty($data["name1"])) {

			$id = $data["name1"];
			$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["name1"]]])->toArray();
			$name = $Customer[0]->name;
			$siten = $Customer[0]->siten;
			$namehyouji = $name . " " . $siten;
			$this->set('namehyouji', $namehyouji);
			$this->set('id', $data["name1"]);
			$nyuukinyotei = $Customer[0]->nyuukinbi;
			$this->set('nyuukinyotei', $nyuukinyotei);
		} elseif (!empty($data["name2"])) {

			$arrname2 = explode(':', $data["name2"]);
			$name2 = $arrname2[0];

			if (isset($arrname2[1])) {
				$Customer = $this->Customers->find('all', ['conditions' => ['name' => $name2, 'siten' => $arrname2[1]]])->toArray();
			} else {
				$Customer = $this->Customers->find('all', ['conditions' => ['name' => $name2]])->toArray();
			}

			$id = $Customer[0]->id;
			$name = $Customer[0]->name;
			$siten = $Customer[0]->siten;
			$namehyouji = $name . " " . $siten;
			$this->set('namehyouji', $namehyouji);
			$this->set('id', $id);
			$nyuukinyotei = $Customer[0]->nyuukinbi;
			$this->set('nyuukinyotei', $nyuukinyotei);
		} else {
			$name = "";
		}

		$customers = $this->Customers->newEntity();
		$this->set('customers', $customers);

		$arrSyuukinfurikomi = [
			'集金' => '集金',
			'振込' => '振込'
		];
		$this->set('arrSyuukinfurikomi', $arrSyuukinfurikomi);

		$arrSyubetu = [
			'振込' => '振込',
			'相殺' => '相殺',
			'現金' => '現金',
			'小切手' => '小切手',
			'手形' => '手形',
			'調整' => '調整'
		];
		$this->set('arrSyubetu', $arrSyubetu);

		$Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['delete_flag' => '0', 'customerId' => $id]])->order(['created_at' => 'desc'])->toArray();
		if (isset($Seikyuus[0])) {
			$date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y年m月d日');
			$this->set('date_seikyuu', $date_seikyuu);
			$touroku_date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
			$this->set('touroku_date_seikyuu', $touroku_date_seikyuu);
			$totalseikyuu = $Seikyuus[0]->total_price;
			$this->set('totalseikyuu', $totalseikyuu);
		} else {
			$date_seikyuu = "";
			$this->set('date_seikyuu', $date_seikyuu);
			$touroku_date_seikyuu = "";
			$this->set('touroku_date_seikyuu', $touroku_date_seikyuu);
			$totalseikyuu = "";
			$this->set('totalseikyuu', $totalseikyuu);

			echo "<pre>";
			print_r("請求書を発行していない顧客が選択されています。");
			echo "</pre>";
		}

		if (!isset($_SESSION)) {
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function nyuukinconfirm()
	{
		$data = $this->request->getData();

		if (!empty($data['datenyuukinyotei']['year'])) {
			$datenyuukinyoteitouroku = $data['datenyuukinyotei']['year'] . "-" . $data['datenyuukinyotei']['month'] . "-" . $data['datenyuukinyotei']['day'];
			$this->set('datenyuukinyoteitouroku', $datenyuukinyoteitouroku);
		} else {
			$datenyuukinyoteitouroku = "";
			$this->set('datenyuukinyoteitouroku', $datenyuukinyoteitouroku);
		}

		if (!empty($data['dateseikyuu']['year'])) {
			$dateseikyuutouroku = $data['dateseikyuu']['year'] . "-" . $data['dateseikyuu']['month'] . "-" . $data['dateseikyuu']['day'];
			$this->set('dateseikyuutouroku', $dateseikyuutouroku);
		} else {
			$dateseikyuutouroku = "";
			$this->set('dateseikyuutouroku', $dateseikyuutouroku);
		}

		if (!empty($data['datenyuukin']['year'])) {

			if (checkdate($data["datenyuukin"]["month"], $data["datenyuukin"]["day"], $data["datenyuukin"]["year"])) {
			} else {

				$mess = "無効な日付が選択されました。";
				$this->set('mess', $mess);

				return $this->redirect([
					'action' => 'nyuukinform',
					's' => ['name1' => $data["id"], 'mess' => $mess]
				]);
			}

			$datenyuukintouroku = $data['datenyuukin']['year'] . "-" . $data['datenyuukin']['month'] . "-" . $data['datenyuukin']['day'];
			$this->set('datenyuukintouroku', $datenyuukintouroku);
		} else {
			$datenyuukintouroku = "";
			$this->set('datenyuukintouroku', $datenyuukintouroku);
		}

		$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
		$name = $Customer[0]->name;
		$siten = $Customer[0]->siten;
		$namehyouji = $name . " " . $siten;
		$this->set('namehyouji', $namehyouji);
		$this->set('id', $data["id"]);
		$nyuukinyotei = $Customer[0]->nyuukinbi;
		$this->set('nyuukinyotei', $nyuukinyotei);

		$customers = $this->Customers->newEntity();
		$this->set('customers', $customers);

		$Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['delete_flag' => '0', 'customerId' => $data["id"]]])->order(['created_at' => 'desc'])->toArray();
		if (isset($Seikyuus[0])) {
			$date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y年m月d日');
			$this->set('date_seikyuu', $date_seikyuu);
			$touroku_date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
			$this->set('touroku_date_seikyuu', $touroku_date_seikyuu);
			$totalseikyuu = $Seikyuus[0]->total_price;
			$this->set('totalseikyuu', $totalseikyuu);
		} else {
			$date_seikyuu = "";
			$this->set('date_seikyuu', $date_seikyuu);
			$touroku_date_seikyuu = "";
			$this->set('touroku_date_seikyuu', $touroku_date_seikyuu);
			$totalseikyuu = "";
			$this->set('totalseikyuu', $totalseikyuu);

			echo "<pre>";
			print_r("請求書を発行していない顧客が選択されています。");
			echo "</pre>";
		}
	}

	public function nyuukindo()
	{
		$data = $this->request->getData();

		$dateseikyuutouroku = $data['dateseikyuutouroku'];
		$this->set('dateseikyuutouroku', $dateseikyuutouroku);

		$datenyuukintouroku = $data['datenyuukintouroku'];
		$this->set('datenyuukintouroku', $datenyuukintouroku);

		$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
		$name = $Customer[0]->name;
		$furigana = $Customer[0]->furigana;
		$siten = $Customer[0]->siten;
		$namehyouji = $name . " " . $siten;
		$this->set('namehyouji', $namehyouji);
		$nyuukinyotei = $Customer[0]->nyuukinbi;
		$this->set('nyuukinyotei', $nyuukinyotei);

		$Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['delete_flag' => '0', 'customerId' => $data["id"]]])->order(['created_at' => 'desc'])->toArray();
		if (isset($Seikyuus[0])) {
			$date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y年m月d日');
			$this->set('date_seikyuu', $date_seikyuu);
			$touroku_date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
			$this->set('touroku_date_seikyuu', $touroku_date_seikyuu);
			$totalseikyuu = $Seikyuus[0]->total_price;
			$this->set('totalseikyuu', $totalseikyuu);
		} else {
			$date_seikyuu = "";
			$this->set('date_seikyuu', $date_seikyuu);
			$touroku_date_seikyuu = "";
			$this->set('touroku_date_seikyuu', $touroku_date_seikyuu);
			$totalseikyuu = "";
			$this->set('totalseikyuu', $totalseikyuu);

			echo "<pre>";
			print_r("請求書を発行していない顧客が選択されています。");
			echo "</pre>";
		}

		$tourokuArr = array();

		$tourokuArr = array(
			'customerId' => $data["id"], 'customer' => $namehyouji, 'furigana' => $furigana,
			'syuukinfurikomi' => $data["syuukinfurikomi"], 'syubetu' => $data["syubetu"], 'bik' => $data["bik"],
			'nyuukinngaku' => $data["nyuukinngaku"], 'seikyuu' => $totalseikyuu,
			'dateseikyuu' => $touroku_date_seikyuu, 'datenyuukin' => $data["datenyuukintouroku"],
			'delete_flag' => 0, 'created_at' => date('Y-m-d H:i:s', strtotime('+9hour'))
		);

		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$nyuukin = $this->Nyuukins->patchEntity($nyuukins, $tourokuArr);
		$connection = ConnectionManager::get('default'); //トランザクション1
		// トランザクション開始2
		$connection->begin(); //トランザクション3
		try { //トランザクション4
			if ($this->Nyuukins->save($nyuukin)) {

				$Zandakas = $this->Zandakas->find('all', ['conditions' => ['customerId' => $data["id"], 'delete_flag' => 0]])->toArray();
				if (isset($Zandakas[0])) {

					$zandaka = $Zandakas[0]->zandaka - $data["nyuukinngaku"];

					$this->Zandakas->updateAll(
						['zandaka' => $zandaka, 'koushinbi' =>  date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
						['id'  => $Zandakas[0]->id]
					);
				}

				$mes = "※下記のように登録されました";
				$this->set('mes', $mes);
				$connection->commit(); // コミット5

			} else {

				$mes = "※登録されませんでした";
				$this->set('mes', $mes);
				$this->Flash->error(__('This data could not be saved. Please, try again.'));
				throw new Exception(Configure::read("M.ERROR.INVALID")); //失敗6

			}
		} catch (Exception $e) { //トランザクション7
			//ロールバック8
			$connection->rollback(); //トランザクション9
		} //トランザクション10

	}

	public function nyuukinsyoukaimenu()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);
	}

	public function urikakeitiranform()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$year = date('Y');
		$this->set('year', $year);
		for ($n = 2010; $n <= $year; $n++) {

			$arrYear[$n] = $n;
		}
		$this->set('arrYear', $arrYear);


		for ($n = 1; $n <= 12; $n++) {

			$arrMonth[$n] = $n;
		}
		$this->set('arrMonth', $arrMonth);
	}

	public function urikakeitiran()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$data = $this->request->getData();

		if ($data['date_sta_m'] == 12) {
			$date_fin_m = 1;
			$date_fin_y = $data['date_sta_y'] + 1;
		} else {
			$date_fin_m = $data['date_sta_m'] + 1;
			$date_fin_y = $data['date_sta_y'];
		}

		$date_sta = $data['date_sta_y'] . "-" . $data['date_sta_m'] . "-1";
		$date_fin = $date_fin_y . "-" . $date_fin_m . "-1";
		$this->set('date_y', $data['date_sta_y']);
		$this->set('date_m', $data['date_sta_m']);

		$date_sta = strtotime($date_sta);
		$date_fin = strtotime($date_fin);

		$Seikyuus = $this->Seikyuus->find()
			->where(['date_seikyuu >=' => $date_sta, 'date_seikyuu <' => $date_fin, 'delete_flag' => 0])
			->order(["date_seikyuu" => "ASC"])->toArray();

		$Uriagetotalmaster = 0;
		$Uriagetotalkurikosi = 0;
		$arrSeikyuus = array();
		$arrCustomerId = array();

		for ($h = 0; $h < count($Seikyuus); $h++) {

			$Customers = $this->Customers->find('all', ['conditions' => ['id' => $Seikyuus[$h]->customerId]])->toArray();
			$customer = $Customers[0]["name"];

			$nyuukin_flag = $Customers[0]->nyuukin_flag;
			if ($nyuukin_flag == 1 && $Customers[0]->nyuukinbi > 0) {
				$nyuukinmonth = "当月";
				$customernyuukinbi = $nyuukinmonth . $Customers[0]->nyuukinbi . "日";
			} elseif ($nyuukin_flag == 2 && $Customers[0]->nyuukinbi > 0) {
				$nyuukinmonth = "翌月";
				$customernyuukinbi = $nyuukinmonth . $Customers[0]->nyuukinbi . "日";
			} elseif ($nyuukin_flag == 3 && $Customers[0]->nyuukinbi > 0) {
				$nyuukinmonth = "翌々月";
				$customernyuukinbi = $nyuukinmonth . $Customers[0]->nyuukinbi . "日";
			} else {
				$customernyuukinbi = "";
			}

			$arrCustomerId[] = [
				"customerId" => $Seikyuus[$h]->customerId,
				"customer" => $customer,
				"syutsuryokubi" => 0,
				"seikyuugaku" => 0,
				"customernyuukinbi" => $customernyuukinbi,
				"kurikosi" => "",
				"nyuukingaku" => "",
				"nyuukinbi" => "",
				"sousai" => "",
				"tyousei" => "",
				"syubetu" => "",
				"zandaka" => "",
				"bik" => "",
				"kogite" => "",
				"kogiteday" => "",
				"kogitetotal" => "",
			];

			$Uriagemasters = $this->Uriagemasters->find()->where(['seikyuuId' => $Seikyuus[$h]["id"], 'delete_flag' => 0])->toArray();

			if (isset($Uriagemasters[0])) {

				for ($j = 0; $j < count($Uriagemasters); $j++) {

					$Uriagetotalmasterkobetu = 0;

					$uriagebi = $Uriagemasters[$j]->uriagebi->format('Y-m-d');

					$Uriagesyousais = $this->Uriagesyousais->find()
						->where(['uriagemasterId' => $Uriagemasters[$j]->id, 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();

					for ($i = 0; $i < count($Uriagesyousais); $i++) {

						if (!empty($Uriagesyousais[$i]->price)) {

							$Uriagetotalmasterkobetu = $Uriagetotalmasterkobetu + $Uriagesyousais[$i]->price;
							$Uriagetotalmaster = $Uriagetotalmaster + $Uriagesyousais[$i]->price;
						}

						if ($i == count($Uriagesyousais) - 1) {

							$arrSeikyuus[] = array(
								"denpyou_num" => $Uriagemasters[$j]->denpyou_num,
								"customer" => $Uriagemasters[$j]->customer,
								"customerId" => $Seikyuus[$h]->customerId,
								"uriagebi" => $uriagebi,
								"seikyuubi" => $Seikyuus[$h]->date_seikyuu->format('Y-m-d'),
								"kingaku" => $Uriagetotalmasterkobetu,
								"Id" => "master_" . $Uriagemasters[$j]->id,
							);
						}
					}
				}

				if ($Seikyuus[$h]->kurikosi > 0) {

					$arrSeikyuus[] = array(
						"denpyou_num" => "（繰越請求）",
						"customer" => $Uriagemasters[0]->customer,
						"customerId" => $Seikyuus[$h]->customerId,
						"uriagebi" => "-",
						"seikyuubi" => $Seikyuus[$h]->date_seikyuu->format('Y-m-d'),
						"kingaku" => $Seikyuus[$h]->kurikosi,
						"Id" => "kurikosi_" . $Seikyuus[$h]->id,
					);

					$Uriagetotalkurikosi = $Uriagetotalkurikosi + $Seikyuus[$h]->kurikosi;
				}
			} else {

				if ($Seikyuus[$h]->total_price > 0) {

					$Customers = $this->Customers->find('all', ['conditions' => ['id' => $Seikyuus[$h]->customerId]])->toArray();
					$customer = $Customers[0]["name"];

					$arrSeikyuus[] = array(
						"denpyou_num" => "（合計表のみ出力）",
						"customer" => $customer,
						"customerId" => $Seikyuus[$h]->customerId,
						"uriagebi" => "-",
						"seikyuubi" => $Seikyuus[$h]->date_seikyuu->format('Y-m-d'),
						"kingaku" => $Seikyuus[$h]->total_price,
						"Id" => "kurikosi_" . $Seikyuus[$h]->id,
					);

					$Uriagetotalkurikosi = $Uriagetotalkurikosi + $Seikyuus[$h]->kurikosi;
				}
			}
		}

		$this->set('arrSeikyuus', $arrSeikyuus);

		$tmp = array();
		$array_result = array();

		foreach ($arrCustomerId as $key => $value) {

			// 配列に値が見つからなければ$tmpに格納
			if (!in_array($value['customerId'], $tmp)) {
				$tmp[] = $value['customerId'];
				$array_result[] = $value;
			}
		}
		$arrCustomerId = $array_result;

		$totalkingakuall = 0;

		for ($h = 0; $h < count($arrCustomerId); $h++) { //それぞれの顧客に対して合計

			$totalkingaku = 0;
			$nyuukingaku_flag = 0;
			$kurikosi = 0;
			$sousai = 0;
			$tyousei = 0;
			$syubetu = 0;
			$zandaka = 0;
			$kogitetotal = 0;
			$totalkogitte = 0;
			$nyuukinngaku = 0;
			$nyuukin = 0;

			for ($i = 0; $i < count($arrSeikyuus); $i++) {

				if ($arrCustomerId[$h]["customerId"] == $arrSeikyuus[$i]["customerId"]) {

					$totalkingaku = $totalkingaku + $arrSeikyuus[$i]["kingaku"];
					$arrCustomerId[$h]["seikyuugaku"] = $totalkingaku; //請求額を合計

					$totalkingakuall = $totalkingakuall + $arrSeikyuus[$i]["kingaku"]; //月の請求額を合計

					$arrCustomerId[$h]["syutsuryokubi"] = $arrSeikyuus[$i]["seikyuubi"];

					//入金の情報を追加
					$Nyuukins = $this->Nyuukins->find('all', ['conditions' => ['customerId' => $arrCustomerId[$h]["customerId"], 'dateseikyuu' => $arrSeikyuus[$i]["seikyuubi"], 'delete_flag' => 0]])
						->order(["created_at" => "DESC"])->toArray();

					if ($nyuukingaku_flag == 0 && isset($Nyuukins[0])) {

						$nyuukingaku = 0;

						for ($l = 0; $l < count($Nyuukins); $l++) {

							$nyuukinngaku = $nyuukinngaku + $Nyuukins[$l]->nyuukinngaku;
							$nyuukinbi = $Nyuukins[$l]->datenyuukin->format('m/d');

							if ($Nyuukins[$l]->syubetu === "相殺") {

								$sousai = $sousai + $Nyuukins[$l]->nyuukinngaku;
							} elseif ($Nyuukins[$l]->syubetu === "調整") {

								$tyousei = $tyousei + $Nyuukins[$l]->nyuukinngaku;
							} elseif ($Nyuukins[$l]->syubetu === "小切手") {

								$nyuukin = $nyuukin + $Nyuukins[$l]->nyuukinngaku;
								$kogite = "小切手";
								$kogiteday = $Nyuukins[$l]->datenyuukin->format('m/d');
								$nyuukin = $nyuukin - $Nyuukins[$l]->nyuukinngaku;
								$kogitetotal = $kogitetotal + $Nyuukins[$l]->nyuukinngaku;
								$totalkogitte = $totalkogitte + $kogitetotal;
							} else {

								$nyuukin = $nyuukin + $Nyuukins[$l]->nyuukinngaku;
								$syubetu = $syubetu . " " . $Nyuukins[$l]->syubetu;
							}

							$nyuukingaku = $nyuukingaku + $Nyuukins[$l]["nyuukinngaku"];
						}

						$arrCustomerId[$h]["nyuukingaku"] = $nyuukingaku;
						$arrCustomerId[$h]["nyuukinbi"] = $Nyuukins[0]["datenyuukin"]->format('Y-m-d');
						$arrCustomerId[$h]["bik"] = $Nyuukins[0]["bik"];

						$nyuukingaku_flag = 1;
					}
				}
			}

			if ($arrCustomerId[$h]["nyuukingaku"] > 0) {

				$zandaka = $arrCustomerId[$h]["seikyuugaku"] - $arrCustomerId[$h]["nyuukingaku"];

				if ($zandaka > 0) {

					$arrCustomerId[$h]["zandaka"] = $zandaka;
				} else {

					$arrCustomerId[$h]["zandaka"] = 0;
				}
			}
		}

		//以下未収
		$Uriagemasters = $this->Uriagemasters->find()->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'seikyuuId' => 0, 'delete_flag' => 0])->toArray();

		$arrSeikyuumisyuu = array();
		for ($i = 0; $i < count($Uriagemasters); $i++) {

			$Customers = $this->Customers->find('all', ['conditions' => ['id' => $Uriagemasters[$i]->customerId]])->toArray();
			$customer = $Customers[0]["name"];

			$nyuukin_flag = $Customers[0]->nyuukin_flag;
			if ($nyuukin_flag == 1 && $Customers[0]->nyuukinbi > 0) {
				$nyuukinmonth = "当月";
				$customernyuukinbi = $nyuukinmonth . $Customers[0]->nyuukinbi . "日";
			} elseif ($nyuukin_flag == 2 && $Customers[0]->nyuukinbi > 0) {
				$nyuukinmonth = "翌月";
				$customernyuukinbi = $nyuukinmonth . $Customers[0]->nyuukinbi . "日";
			} elseif ($nyuukin_flag == 3 && $Customers[0]->nyuukinbi > 0) {
				$nyuukinmonth = "翌々月";
				$customernyuukinbi = $nyuukinmonth . $Customers[0]->nyuukinbi . "日";
			} else {
				$customernyuukinbi = "";
			}

			$arrSeikyuumisyuu[] = array(
				"customerId" => $Uriagemasters[$i]->customerId,
				"customer" => $customer,
				"customernyuukinbi" => $customernyuukinbi,
				"syutsuryokubi" => "未収",
				"kurikosi" => 0,
				"nyuukingaku" => "",
				"seikyuugaku" => "",
				"nyuukinbi" => "",
				"sousai" => "",
				"tyousei" => "",
				"syubetu" => "",
				"zandaka" => 0,
				"bik" => "",
				"kogite" => "",
				"kogiteday" => "",
				"kogitetotal" => "",
			);
		}

		$tmpmisyu = array();
		$array_resultmisyu = array();

		foreach ($arrSeikyuumisyuu as $key => $value) {

			// 配列に値が見つからなければ$tmpに格納
			if (!in_array($value['customerId'], $tmpmisyu)) {
				$tmpmisyu[] = $value['customerId'];
				$array_resultmisyu[] = $value;
			}
		}
		$arrSeikyuumisyuu = $array_resultmisyu;

		for ($i = 0; $i < count($arrSeikyuumisyuu); $i++) { //すべての未収の顧客に対して

			$totalkingaku = 0;

			for ($j = 0; $j < count($Uriagemasters); $j++) {

				if ($arrSeikyuumisyuu[$i]["customerId"] == $Uriagemasters[$j]["customerId"]) {

					$Uriagesyousais = $this->Uriagesyousais->find()
						->where(['uriagemasterId' => $Uriagemasters[$j]->id, 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();

					for ($k = 0; $k < count($Uriagesyousais); $k++) {

						if (!empty($Uriagesyousais[$k]->price)) {

							$totalkingaku = $totalkingaku + $Uriagesyousais[$k]->price;
						}
					}

					$arrSeikyuumisyuu[$i]["kurikosi"] = $totalkingaku;
					$arrSeikyuumisyuu[$i]["zandaka"] = $totalkingaku;
				}
			}
		}

		array_multisort(array_map("strtotime", array_column($arrCustomerId, "syutsuryokubi")), SORT_ASC, $arrCustomerId); //請求日順

		$arrSeikyuu = array_merge($arrCustomerId, $arrSeikyuumisyuu);
		$this->set('arrSeikyuu', $arrSeikyuu);

		$totalkingaku = 0;
		$totaltyousei = 0;
		$totalkogitte = 0;
		$totalzandaka = 0;
		$totalkurikosi = 0;
		$totalnyuukin = 0;

		for ($i = 0; $i < count($arrSeikyuu); $i++) { //売掛のそれぞれの合計を出す

			if ($arrSeikyuu[$i]["seikyuugaku"] > 0) {
				$totalkingaku = $totalkingaku + $arrSeikyuu[$i]["seikyuugaku"];
			}
			if ($arrSeikyuu[$i]["tyousei"] > 0) {
				$totaltyousei = $totaltyousei + $arrSeikyuu[$i]["tyousei"];
			}
			if ($arrSeikyuu[$i]["kogite"] > 0) {
				$totalkogitte = $totalkogitte + $arrSeikyuu[$i]["kogite"];
			}
			if ($arrSeikyuu[$i]["zandaka"] > 0) {
				$totalzandaka = $totalzandaka + $arrSeikyuu[$i]["zandaka"];
			}
			if ($arrSeikyuu[$i]["kurikosi"] > 0) {
				$totalkurikosi = $totalkurikosi + $arrSeikyuu[$i]["kurikosi"];
			}
			if ($arrSeikyuu[$i]["nyuukingaku"] > 0) {
				$totalnyuukin = $totalnyuukin + $arrSeikyuu[$i]["nyuukingaku"];
			}
		}

		$this->set('totalkingaku', $totalkingaku);
		$this->set('totaltyousei', $totaltyousei);
		$this->set('totalkurikosi', $totalkurikosi);
		$this->set('totalkogitte', $totalkogitte);
		$this->set('totalnyuukin', $totalnyuukin);
		$this->set('totalzandaka', $totalzandaka);

		$mesxlsx = "";
		$this->set('mesxlsx', $mesxlsx);

		if (!empty($data["excel"])) {

			//エクセル出力
			$filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\売掛一覧.xlsx'; //読み込みたいファイルの指定
			$reader = new XlsxReader();
			$spreadsheet = $reader->load($filepath);
			$sheet = $spreadsheet->getSheetByName("Sheet1");

			$num = 2;
			for ($j = 0; $j <= count($arrSeikyuu); $j++) {

				if ($j < count($arrSeikyuu)) {
					$sheet->setCellValue("A" . $num, $arrSeikyuu[$j]["syutsuryokubi"]);
					$sheet->setCellValue("B" . $num, $arrSeikyuu[$j]["customer"]);
					if ($arrSeikyuu[$j]["kurikosi"] > 0) {
						$sheet->setCellValue("C" . $num, $arrSeikyuu[$j]["kurikosi"]);
					}
					$sheet->setCellValue("D" . $num, $arrSeikyuu[$j]["seikyuugaku"]);
					$sheet->setCellValue("E" . $num, $arrSeikyuu[$j]["nyuukinbi"]);
					if ($arrSeikyuu[$j]["sousai"] > 0) {
						$sheet->setCellValue("F" . $num, $arrSeikyuu[$j]["sousai"]);
					}
					if ($arrSeikyuu[$j]["kogitetotal"] > 0) {
						$sheet->setCellValue("G" . $num, $arrSeikyuu[$j]["kogite"]);
						$sheet->setCellValue("H" . $num, $arrSeikyuu[$j]["kogiteday"]);
						$sheet->setCellValue("I" . $num, $arrSeikyuu[$j]["kogitetotal"]);
					}
					$sheet->setCellValue("J" . $num, $arrSeikyuu[$j]["syubetu"]);
					if ($arrSeikyuu[$j]["nyuukingaku"] > 0) {
						$sheet->setCellValue("K" . $num, $arrSeikyuu[$j]["nyuukingaku"]);
					}
					if ($arrSeikyuu[$j]["tyousei"] > 0) {
						$sheet->setCellValue("L" . $num, $arrSeikyuu[$j]["tyousei"]);
					}
					if ($arrSeikyuu[$j]["zandaka"] > 0) {
						$sheet->setCellValue("M" . $num, $arrSeikyuu[$j]["zandaka"]);
					} else {
						$sheet->setCellValue("M" . $num, "－");
					}
					$sheet->setCellValue("N" . $num, $arrSeikyuu[$j]["bik"]);
					$sheet->setCellValue("O" . $num, $arrSeikyuu[$j]["customernyuukinbi"]);

					$num = $num + 1;
				} else {

					$sheet->setCellValue("A" . $num, "合計");
					$sheet->setCellValue("C" . $num, $totalkurikosi);
					$sheet->setCellValue("D" . $num, $totalkingaku);
					$sheet->setCellValue("I" . $num, $totalkogitte);
					$sheet->setCellValue("K" . $num, $totalnyuukin);
					$sheet->setCellValue("M" . $num, $totalzandaka);
				}
			}

			$writer = new XlsxWriter($spreadsheet);

			$datetime = date('H時i分s秒出力', strtotime('+9hour'));
			$year = date('Y', strtotime('+9hour'));
			$month = date('m', strtotime('+9hour'));
			$day = date('d', strtotime('+9hour'));
			$date_m = $data['date_sta_m'];
			$date_y = $data['date_sta_y'];

			if (is_dir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/売掛一覧/$year/$month/$day")) { //ディレクトリが存在すればOK

				$file_name = "売掛_" . $date_y . "年" . $date_m . "月分_" . $datetime . ".xlsx";
				$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/売掛一覧/$year/$month/$day/$file_name"; //出力したいファイルの指定

			} else { //ディレクトリが存在しなければ作成する

				mkdir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/売掛一覧/$year/$month/$day", 0777, true);
				$file_name = "売掛_" . $date_y . "年" . $date_m . "月分_" . $datetime . ".xlsx";
				$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/売掛一覧/$year/$month/$day/$file_name"; //出力したいファイルの指定

			}

			$mesxlsx = "「エクセル出力/売掛一覧/" . $year . "/" . $month . "/" . $day . "」フォルダにエクセルシート「" . $file_name . "」が出力されました。";
			$this->set('mesxlsx', $mesxlsx);

			$writer->save($outfilepath);
		}
	}

	public function nyuukinsyoukaiseikyuuform()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$year = date('Y');
		$this->set('year', $year);
		for ($n = 2010; $n <= $year; $n++) {
			$arrYear[$n] = $n;
		}
		$this->set('arrYear', $arrYear);

		for ($n = 1; $n <= 12; $n++) {
			$arrMonth[$n] = $n;
		}
		$this->set('arrMonth', $arrMonth);
	}

	public function nyuukinsyoukaiseikyuuitiran()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$data = $this->request->getData();

		if ($data['date_sta_m'] == 12) {
			$date_fin_m = 1;
			$date_fin_y = $data['date_sta_y'] + 1;
		} else {
			$date_fin_m = $data['date_sta_m'] + 1;
			$date_fin_y = $data['date_sta_y'];
		}

		$date_sta = $data['date_sta_y'] . "-" . $data['date_sta_m'] . "-1";
		$date_fin = $date_fin_y . "-" . $date_fin_m . "-1";
		$this->set('date_sta', $date_sta);
		$this->set('date_fin', $date_fin);

		$this->set('date_y', $data['date_sta_y']);
		$this->set('date_m', $data['date_sta_m']);

		$date_sta = strtotime($date_sta);
		$date_fin = strtotime($date_fin);

		$Nyuukins = $this->Nyuukins->find()
			->where(['datenyuukin >=' => $date_sta, 'datenyuukin <' => $date_fin, 'delete_flag' => 0])->order(["datenyuukin" => "ASC"])->toArray();
		$this->set('Nyuukins', $Nyuukins);

		$count = count($Nyuukins);

		$totalkingaku = 0;
		for ($k = 0; $k < $count; $k++) {
			$totalkingaku = $totalkingaku + $Nyuukins[$k]->nyuukinngaku;
		}
		$this->set('totalkingaku', $totalkingaku);

		if (!isset($_SESSION)) {
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function nyuukinsyoukainyuukinngakuform()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);
	}

	public function nyuukinsyoukainyuukinngakuitiran()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$data = $this->request->getData();

		$date_sta = $data['date_sta']['year'] . "-" . $data['date_sta']['month'] . "-" . $data['date_sta']['day'];
		$date_fin = $data['date_fin']['year'] . "-" . $data['date_fin']['month'] . "-" . $data['date_fin']['day'];
		$this->set('date_sta', $date_sta);
		$this->set('date_fin', $date_fin);

		$date_fin = strtotime($date_fin);
		$date_fin = date('Y-m-d', strtotime('+1 day', $date_fin));

		$Nyuukins = $this->Nyuukins->find()
			->where(['dateseikyuu >=' => $date_sta, 'dateseikyuu <=' => $date_fin, 'delete_flag' => 0])->order(["furigana" => "ASC"])->toArray();
		$this->set('Nyuukins', $Nyuukins);

		$count = count($Nyuukins);

		$totalkingaku = 0;
		for ($k = 0; $k < $count; $k++) {
			$totalkingaku = $totalkingaku + $Nyuukins[$k]->nyuukinngaku;
		}
		$this->set('totalkingaku', $totalkingaku);
	}

	public function nyuukinsyoukaisyousai()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$data = $this->request->getData();
		$data = array_keys($data, '詳細');
		$id = $data[0];
		$this->set('id', $id);

		$Nyuukin = $this->Nyuukins->find('all', ['conditions' => ['id' => $id]])->toArray();
		$customerId = $Nyuukin[0]->customerId;
		$customer = $Nyuukin[0]->customer;
		$this->set('customer', $customer);
		$datenyuukin = $Nyuukin[0]->datenyuukin;
		$this->set('datenyuukin', $datenyuukin);
		$syuukinfurikomi = $Nyuukin[0]->syuukinfurikomi;
		$this->set('syuukinfurikomi', $syuukinfurikomi);
		$datenyuukinyotei = $Nyuukin[0]->datenyuukinyotei;
		$this->set('datenyuukinyoteitouroku', $datenyuukinyotei);
		if (!empty($datenyuukinyotei)) {
			$datenyuukinyotei = $Nyuukin[0]->datenyuukinyotei->format('Y年m月d日');
		}
		$this->set('datenyuukinyotei', $datenyuukinyotei);
		$dateseikyuu = $Nyuukin[0]->dateseikyuu;
		$this->set('dateseikyuutouroku', $dateseikyuu);
		if (!empty($dateseikyuu)) {
			$dateseikyuu = $Nyuukin[0]->dateseikyuu->format('Y年m月d日');
		}
		$this->set('dateseikyuu', $dateseikyuu);
		$kurikosi = $Nyuukin[0]->kurikosi;
		$this->set('kurikosi', $kurikosi);
		$seikyuu = $Nyuukin[0]->seikyuu;
		$this->set('seikyuu', $seikyuu);
		$datenyuukin = $Nyuukin[0]->datenyuukin;
		$this->set('datenyuukintouroku', $datenyuukin);
		if (!empty($datenyuukin)) {
			$datenyuukin = $Nyuukin[0]->datenyuukin->format('Y年m月d日');
		}
		$this->set('datenyuukin', $datenyuukin);
		$syubetu = $Nyuukin[0]->syubetu;
		$this->set('syubetu', $syubetu);
		$nyuukinngaku = $Nyuukin[0]->nyuukinngaku;
		$this->set('nyuukinngaku', $nyuukinngaku);
		$bik = $Nyuukin[0]->bik;
		$this->set('bik', $bik);

		$Customer = $this->Customers->find('all', ['conditions' => ['id' => $customerId]])->toArray();
		$nyuukinyotei = $Customer[0]->nyuukinbi;
		$this->set('nyuukinyotei', $nyuukinyotei);

		$Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['delete_flag' => '0', 'customerId' => $customerId]])->order(['date_seikyuu' => 'desc'])->toArray();
		$date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y年m月d日');
		$this->set('date_seikyuu', $date_seikyuu);
		$touroku_date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
		$this->set('touroku_date_seikyuu', $touroku_date_seikyuu);
		$totalseikyuu = $Seikyuus[0]->total_price;
		$this->set('totalseikyuu', $totalseikyuu);

		if (!isset($_SESSION)) {
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function nyuukinsyoukaiedit()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);
		$data = $this->request->getData();

		$id = $data["id"];
		$this->set('id', $id);

		$arrSyuukinfurikomi = [
			'集金' => '集金',
			'振込' => '振込'
		];
		$this->set('arrSyuukinfurikomi', $arrSyuukinfurikomi);

		$arrSyubetu = [
			'振込' => '振込',
			'相殺' => '相殺',
			'現金' => '現金',
			'小切手' => '小切手',
			'手形' => '手形',
			'調整' => '調整'
		];
		$this->set('arrSyubetu', $arrSyubetu);

		$Nyuukin = $this->Nyuukins->find('all', ['conditions' => ['id' => $id]])->toArray();
		$customerId = $Nyuukin[0]->customerId;
		$syubetu = $Nyuukin[0]->syubetu;
		$this->set('syubetu', $syubetu);
		$nyuukinngaku = $Nyuukin[0]->nyuukinngaku;
		$this->set('nyuukinngaku', $nyuukinngaku);
		$bik = $Nyuukin[0]->bik;
		$this->set('bik', $bik);
		$datenyuukin = $Nyuukin[0]->datenyuukin;
		$this->set('datenyuukin', $datenyuukin);

		$Customer = $this->Customers->find('all', ['conditions' => ['id' => $customerId]])->toArray();
		$nyuukinyotei = $Customer[0]->nyuukinbi;
		$this->set('nyuukinyotei', $nyuukinyotei);

		$Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['delete_flag' => '0', 'customerId' => $customerId]])->order(['date_seikyuu' => 'desc'])->toArray();
		$date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y年m月d日');
		$this->set('date_seikyuu', $date_seikyuu);
		$touroku_date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
		$this->set('touroku_date_seikyuu', $touroku_date_seikyuu);
		$totalseikyuu = $Seikyuus[0]->total_price;
		$this->set('totalseikyuu', $totalseikyuu);
	}

	public function nyuukinsyoukaieditdo()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);
		$data = $this->request->getData();

		$id = $data["id"];
		$this->set('id', $id);

		$datenyuukin = $data['datenyuukin']['year'] . "-" . $data['datenyuukin']['month'] . "-" . $data['datenyuukin']['day'];
		$this->set('datenyuukin', $datenyuukin);

		$Nyuukin = $this->Nyuukins->find('all', ['conditions' => ['id' => $id]])->toArray();
		$customerId = $Nyuukin[0]->customerId;
		$nyuukinmoto = $Nyuukin[0]->nyuukinngaku;

		$Customer = $this->Customers->find('all', ['conditions' => ['id' => $customerId]])->toArray();
		$nyuukinyotei = $Customer[0]->nyuukinbi;
		$this->set('nyuukinyotei', $nyuukinyotei);

		$Seikyuus = $this->Seikyuus->find('all', ['conditions' => ['delete_flag' => '0', 'customerId' => $customerId]])->order(['date_seikyuu' => 'desc'])->toArray();
		$date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y年m月d日');
		$this->set('date_seikyuu', $date_seikyuu);
		$touroku_date_seikyuu = $Seikyuus[0]->date_seikyuu->format('Y-m-d');
		$this->set('touroku_date_seikyuu', $touroku_date_seikyuu);
		$totalseikyuu = $Seikyuus[0]->total_price;
		$this->set('totalseikyuu', $totalseikyuu);

		if ($data["delete_flag"] == 1) {
			$mess = "以下のデータを削除しました。";
		} else {
			$mess = "以下のように更新しました。";
		}
		$this->set('mess', $mess);

		$nyuukin = $this->Nyuukins->patchEntity($nyuukins, $data);
		$connection = ConnectionManager::get('default'); //トランザクション1
		// トランザクション開始2
		$connection->begin(); //トランザクション3
		try { //トランザクション4
			if ($this->Nyuukins->updateAll(
				[
					'syuukinfurikomi' => $data['syuukinfurikomi'],  'datenyuukin' => $datenyuukin,
					'syubetu' => $data['syubetu'], 'nyuukinngaku' => $data['nyuukinngaku'], 'bik' => $data['bik'],
					'delete_flag' => $data['delete_flag']
				],
				['id'  => $data['id']]
			)) {

				$Zandakas = $this->Zandakas->find('all', ['conditions' => ['customerId' => $customerId, 'delete_flag' => 0]])->toArray();
				if (isset($Zandakas[0])) {

					$zandaka = $Zandakas[0]->zandaka + $nyuukinmoto - $data['nyuukinngaku'];

					$this->Zandakas->updateAll(
						['zandaka' => $zandaka, 'koushinbi' =>  date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
						['id'  => $Zandakas[0]->id]
					);
				}

				$connection->commit(); // コミット5

			} else {

				$mes = "※更新されませんでした";
				$this->set('mes', $mes);
				$this->Flash->error(__('This data could not be saved. Please, try again.'));
				throw new Exception(Configure::read("M.ERROR.INVALID")); //失敗6

			}
		} catch (Exception $e) { //トランザクション7
			//ロールバック8
			$connection->rollback(); //トランザクション9
		} //トランザクション10

	}

	public function seikyuuformcustomer()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0']])->order(['furigana' => 'ASC']);
		$arrCustomer = array();
		foreach ($arrCustomers as $value) {
			$furigana = $value->furigana;
			$furigana = mb_substr($furigana, 0, 1);;
			$arrCustomer[] = array($value->id => $furigana . " - " . $value->name . ' ' . $value->siten);
		}
		$this->set('arrCustomer', $arrCustomer);

		$autoCustomers = $this->Customers->find()
			->where(['delete_flag' => 0])->toArray();
		$arrCustomer_list = array();
		for ($j = 0; $j < count($autoCustomers); $j++) {

			if (strlen($autoCustomers[$j]["siten"]) > 0) {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"] . ":" . $autoCustomers[$j]["siten"]);
			} else {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"]);
			}
		}
		$arrCustomer_list = array_unique($arrCustomer_list);
		$arrCustomer_list = array_values($arrCustomer_list);
		$this->set('arrCustomer_list', $arrCustomer_list);
	}

	public function seikyuuformcustomerfurigana()
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$Data = $this->request->query('s');
		$data = $Data['data'];

		$furigana = $data["nyuryokufurigana"];

		$arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0', 'furigana like' => '%' . $furigana . '%']])->order(['furigana' => 'ASC']);
		$arrCustomer = array();
		foreach ($arrCustomers as $value) {
			$arrCustomer[] = array($value->id => $value->name . ' ' . $value->siten);
		}
		$this->set('arrCustomer', $arrCustomer);
	}

	public function seikyuuform()
	{
		$data = $this->request->getData();

		$mess = "";
		$this->set('mess', $mess);

		$Data = $this->request->query('s');
		if (isset($Data["mess"])) {

			$mess = $Data["mess"];
			$this->set('mess', $mess);

			$data = $Data;
		}

		if (!empty($data["nyuryokufurigana"])) {

			return $this->redirect([
				'action' => 'seikyuuformcustomerfurigana',
				's' => ['data' => $data]
			]);
		}

		$dataId = array_keys($data, '請求処理へ');

		if (!empty($data["name1"])) {

			$name1 = $data["name1"];

			$Customer = $this->Customers->find('all', ['conditions' => ['id' => $name1]])->toArray();

			$name = $Customer[0]->name;
			$siten = $Customer[0]->siten;
			//			 $namehyouji = $name." ".$siten;
			$this->set('siten', $siten);
			$this->set('namehyouji', $name);
			$id = $data["name1"];
			$this->set('id', $data["name1"]);
			$simebi = $Customer[0]->simebi;
			$this->set('simebi', $simebi);
			$hittyakubi = $Customer[0]->hittyakubi;
			$this->set('hittyakubi', $hittyakubi);
			$nyuukinbi = $Customer[0]->nyuukinbi;
			$this->set('nyuukinbi', $nyuukinbi);
			$kaisyuu = $Customer[0]->kaisyuu;
			if ($kaisyuu == 1) {
				$kaisyuu = "振込";
			} else {
				$kaisyuu = "集金";
			}
			$this->set('kaisyuu', $kaisyuu);
		} elseif (!empty($data["name2"])) {

			$arrname2 = explode(':', $data["name2"]);
			$name2 = $arrname2[0];

			if (isset($arrname2[1])) {
				$Customer = $this->Customers->find('all', ['conditions' => ['name' => $name2, 'siten' => $arrname2[1]]])->toArray();
			} else {
				$Customer = $this->Customers->find('all', ['conditions' => ['name' => $name2]])->toArray();
			}
			$id = $Customer[0]->id;
			$name = $Customer[0]->name;
			$siten = $Customer[0]->siten;
			//			 $namehyouji = $name." ".$siten;
			$this->set('siten', $siten);
			$this->set('namehyouji', $name);
			//			 $id = $data["name2"];
			$this->set('id', $id);
			$simebi = $Customer[0]->simebi;
			$this->set('simebi', $simebi);
			$hittyakubi = $Customer[0]->hittyakubi;
			$this->set('hittyakubi', $hittyakubi);
			$nyuukinbi = $Customer[0]->nyuukinbi;
			$this->set('nyuukinbi', $nyuukinbi);
			$kaisyuu = $Customer[0]->kaisyuu;
			if ($kaisyuu == 1) {
				$kaisyuu = "振込";
			} else {
				$kaisyuu = "集金";
			}
			$this->set('kaisyuu', $kaisyuu);
		} elseif (!empty($dataId[0])) {

			$Customer = $this->Customers->find('all', ['conditions' => ['id' => $dataId[0]]])->toArray();
			$name = $Customer[0]->name;
			$siten = $Customer[0]->siten;
			$namehyouji = $name . " " . $siten;
			$this->set('namehyouji', $namehyouji);
			$id = $dataId[0];
			$this->set('id', $dataId[0]);
			$simebi = $Customer[0]->simebi;
			$this->set('simebi', $simebi);
			$hittyakubi = $Customer[0]->hittyakubi;
			$this->set('hittyakubi', $hittyakubi);
			$nyuukinbi = $Customer[0]->nyuukinbi;
			$this->set('nyuukinbi', $nyuukinbi);
			$kaisyuu = $Customer[0]->kaisyuu;
			if ($kaisyuu == 1) {
				$kaisyuu = "振込";
			} else {
				$kaisyuu = "集金";
			}
			$this->set('kaisyuu', $kaisyuu);
		} else {
			$name = "";
		}

		$Uriage = $this->Uriagemasters->find('all', ['conditions' => ['customerId' => $id, 'delete_flag' => 0, 'seikyuuId' => 0]])->order(['customerId' => 'ASC'])->toArray();

		$Customer = $this->Customers->find('all', ['conditions' => ['seikyuusakicustomerId' => $id, 'delete_flag' => 0]])->order(['id' => 'ASC'])->toArray();

		if (isset($Customer[0])) {
			$arrUriage = array();
			for ($k = 0; $k < count($Customer); $k++) {
				$arrUriage = $this->Uriagemasters->find('all', ['conditions' => ['customerId' => $Customer[$k]->id, 'delete_flag' => 0, 'seikyuuId' => 0]])->order(['customerId' => 'ASC'])->toArray();
				$Uriage = array_merge($Uriage, $arrUriage);
			}
		}

		$count = count($Uriage);
		$this->set('count', $count);

		$totalkingaku = 0;
		$totaltax = 0;
		$totalkingakutaxinc = 0;
		$arrPro_1 = array();
		$arrTaxinc = array();
		$arrDenpyou = array();
		$arrMasterId = array();
		$arrCustomername = array();
		$arrSyuturyoku = array();
		$arrTotalprice = array();

		if ($count > 0) {

			$this->set('count', $count);

			for ($k = 0; $k < $count; $k++) {

				$arrMasterId[] = $Uriage[$k]->id;
				$arrTaxinc[] = $Uriage[$k]->tax_include_flag;
				$arrDenpyou[] = $Uriage[$k]->denpyou_num;
				$arrSyuturyoku[] = $Uriage[$k]->uriagebi->format('m/d');
				$arrCustomername[] = $Uriage[$k]->customer;

				$Uriagesyousais = $this->Uriagesyousais->find()
					->where(['uriagemasterId' => $Uriage[$k]->id, 'delete_flag' => 0])->order(['num' => 'asc'])->toArray();

				$arrPro_1[] = $Uriagesyousais[0]->pro;

				${"Totalprice" . $k} = 0;
				${"Totaltax" . $k} = 0;
				for ($i = 0; $i < count($Uriagesyousais); $i++) {

					if (!empty($Uriagesyousais[$i]->pro)) {

						$totalkingaku = $totalkingaku + $Uriagesyousais[$i]->price;
						${"Totalprice" . $k} = ${"Totalprice" . $k} + $Uriagesyousais[$i]->price;
						$zeiritu = 10;
						if (strlen($Uriagesyousais[$i]->zeiritu) > 0) {
							$zeiritu = $Uriagesyousais[$i]->zeiritu;
						}
						${"Totaltax" . $k} = ${"Totaltax" . $k} + $Uriagesyousais[$i]->price * $zeiritu / 100;

						if ($Uriage[$k]->tax_include_flag == 0) { //税別の場合
							$totaltax = $totaltax + $Uriagesyousais[$i]->price * $zeiritu / 100;
							$totalkingakutaxinc = $totalkingakutaxinc + $Uriagesyousais[$i]->price + $Uriagesyousais[$i]->price * $zeiritu / 100;
						} else {
							$totalkingakutaxinc = $totalkingakutaxinc + $Uriagesyousais[$i]->price;
						}

						${"Uriagemasters" . $k} = $this->Uriagemasters->find('all', ['conditions' => ['id' => $Uriagesyousais[$i]->uriagemasterId]])->toArray();
						${"namehyouji" . $k} = ${"Uriagemasters" . $k}[0]->customer;
						$this->set("namehyouji" . $k, ${"namehyouji" . $k});
					}
				}
				$this->set("Totalprice" . $k, ${"Totalprice" . $k});
				$this->set("Totaltax" . $k, ${"Totaltax" . $k});
			}
		}

		$this->set('totalkingaku', $totalkingaku);
		$this->set('totaltax', $totaltax);
		$this->set('totalkingakutaxinc', $totalkingakutaxinc);
		$this->set('arrPro_1', $arrPro_1);
		$this->set('arrMasterId', $arrMasterId);
		$this->set('arrTaxinc', $arrTaxinc);
		$this->set('arrDenpyou', $arrDenpyou);
		$this->set('arrCustomername', $arrCustomername);
		$this->set('arrSyuturyoku', $arrSyuturyoku);

		$customers = $this->Customers->newEntity();
		$this->set('customers', $customers);

		$Today = date('m') . "/" . date('d', strtotime('+9hour'));
		$this->set('Today', $Today);
		$monthSeikyuu = date('Y', strtotime('+9hour')) . "年 " . date('m', strtotime('+9hour')) . "月度";
		$this->set('monthSeikyuu', $monthSeikyuu);

		$Seikyuu = $this->Seikyuus->find('all', ['conditions' => ['customerId' => $id, 'delete_flag' => 0]])->order(['date_seikyuu' => 'desc'])->toArray();

		$nyuukinntotal = 0;
		$tyouseitotal = 0;
		$sousaitotal = 0;

		if (isset($Seikyuu[0])) {

			$Nyuukincheck = $this->Nyuukins->find('all', ['conditions' => ['seikyuuId' => $Seikyuu[0]["id"], 'delete_flag' => 0]])
				->toArray();

			if (!isset($Nyuukincheck[0])) { //入金がまだの場合
				$Zenkai = $Seikyuu[0]->total_price;
			} else {
				$Zenkai = 0;
			}
		} else {
			$Zenkai = 0;
		}

		$nyuukinntotal = 0; //220211入金額のところは表示しない
		$tyouseitotal = 0; //220211入金額のところは表示しない
		$sousaitotal = 0; //220211入金額のところは表示しない

		$this->set('Zenkai', $Zenkai); //220211前回請求のところは、その請求が未入金の場合だけ表示
		$this->set('nyuukinntotal', $nyuukinntotal);
		$this->set('tyouseitotal', $tyouseitotal);
		$this->set('sousaitotal', $sousaitotal);

		if (!isset($_SESSION)) {
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function seikyuuconfirm()
	{
		$data = $this->request->getData();

		//合計表のみの時にエラーがでないように
		$totalkingaku = 0;
		$totaltax = 0;
		$totalkingakutaxinc = 0;
		$this->set('totalkingaku', $totalkingaku);
		$this->set('totaltax', $totaltax);
		$this->set('totalkingakutaxinc', $totalkingakutaxinc);

		if (checkdate($data["date"]["month"], $data["date"]["day"], $data["date"]["year"])) {
		} else {

			$mess = "無効な日付が選択されました。";
			$this->set('mess', $mess);

			return $this->redirect([
				'action' => 'seikyuuform',
				's' => ['name1' => $data["id"], 'mess' => $mess]
			]);
		}

		$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
		$name = $Customer[0]->name;
		$siten = $Customer[0]->siten;
		$this->set('siten', $siten);
		//			 $namehyouji = $name." ".$siten;
		$this->set('namehyouji', $name);
		$id = $data["id"];
		$this->set('id', $data["id"]);
		$simebi = $Customer[0]->simebi;
		$this->set('simebi', $simebi);
		$hittyakubi = $Customer[0]->hittyakubi;
		$this->set('hittyakubi', $hittyakubi);
		$nyuukinbi = $Customer[0]->nyuukinbi;
		$this->set('nyuukinbi', $nyuukinbi);
		$kaisyuu = $Customer[0]->kaisyuu;
		if ($kaisyuu == 1) {
			$kaisyuu = "振込";
		} else {
			$kaisyuu = "集金";
		}
		$this->set('kaisyuu', $kaisyuu);

		$Uriage = array();
		$arrDelId = array();

		if (isset($data["num"])) {

			for ($k = 0; $k <= $data["num"]; $k++) {
				if ($data["delete_flag" . $k] < 1) {
					$arrUriage = $this->Uriagemasters->find('all', ['conditions' => ['id' => $data["id" . $k]]])->toArray();
					$Uriage = array_merge($Uriage, $arrUriage);
				} else {
					$arrDelId[] = $data["id" . $k];
				}
			}
			$count = count($Uriage);
			$this->set('count', $count);
			$this->set('arrDelId', $arrDelId);

			$totalkingaku = 0;
			$totaltax = 0;
			$totalkingakutaxinc = 0;
			$arrPro_1 = array();
			$arrTaxinc = array();
			$arrMasterId = array();
			$arrDenpyou = array();
			$arrCustomername = array();
			$arrSyuturyoku = array();
			$arrTotalprice = array();

			if ($count > 0) {

				$this->set('count', $count);

				for ($k = 0; $k < $count; $k++) {

					$arrMasterId[] = $Uriage[$k]->id;
					$arrDenpyou[] = $Uriage[$k]->denpyou_num;
					$arrSyuturyoku[] = $Uriage[$k]->uriagebi->format('m/d');
					$arrCustomername[] = $Uriage[$k]->customer;
					$arrTaxinc[] = $Uriage[$k]->tax_include_flag;

					$Uriagesyousais = $this->Uriagesyousais->find()
						->where(['uriagemasterId' => $Uriage[$k]->id, 'delete_flag' => 0])->order(['num' => 'asc'])->toArray();

					$arrPro_1[] = $Uriagesyousais[0]->pro;

					${"Totalprice" . $k} = 0;
					${"Totaltax" . $k} = 0;
					for ($i = 0; $i < count($Uriagesyousais); $i++) {

						if (!empty($Uriagesyousais[$i]->pro)) {

							$totalkingaku = $totalkingaku + $Uriagesyousais[$i]->price;
							${"Totalprice" . $k} = ${"Totalprice" . $k} + $Uriagesyousais[$i]->price;
							$zeiritu = 10;
							if (strlen($Uriagesyousais[$i]->zeiritu) > 0) {
								$zeiritu = $Uriagesyousais[$i]->zeiritu;
							}
							${"Totaltax" . $k} = ${"Totaltax" . $k} + $Uriagesyousais[$i]->price * $zeiritu / 100;

							if ($Uriage[$k]->tax_include_flag == 0) { //税別の場合
								$totaltax = $totaltax + $Uriagesyousais[$i]->price * $zeiritu / 100;
								$totalkingakutaxinc = $totalkingakutaxinc + $Uriagesyousais[$i]->price + $Uriagesyousais[$i]->price * $zeiritu / 100;
							} else {
								$totalkingakutaxinc = $totalkingakutaxinc + $Uriagesyousais[$i]->price;
							}

							${"Uriagemasters" . $k} = $this->Uriagemasters->find('all', ['conditions' => ['id' => $Uriagesyousais[$i]->uriagemasterId]])->toArray();
							${"namehyouji" . $k} = ${"Uriagemasters" . $k}[0]->customer;
							$this->set("namehyouji" . $k, ${"namehyouji" . $k});
						}
					}
					$this->set("Totalprice" . $k, ${"Totalprice" . $k});
					$this->set("Totaltax" . $k, ${"Totaltax" . $k});
				}
			}

			$this->set('totalkingaku', $totalkingaku);
			$this->set('totaltax', $totaltax);
			$this->set('totalkingakutaxinc', $totalkingakutaxinc);
			$this->set('arrPro_1', $arrPro_1);
			$this->set('arrMasterId', $arrMasterId);
			$this->set('arrTaxinc', $arrTaxinc);
			$this->set('arrDenpyou', $arrDenpyou);
			$this->set('arrCustomername', $arrCustomername);
			$this->set('arrSyuturyoku', $arrSyuturyoku);
		} else {

			$totalkingaku = 0;
			$this->set('totalkingaku', $totalkingaku);
			$count = 0;
			$this->set('count', $count);
			$this->set('arrDelId', $arrDelId);
		}

		$customers = $this->Customers->newEntity();
		$this->set('customers', $customers);

		//	 $Today = date('m')."/".date('d', strtotime('+9hour'));
		$Today = $data['date']['year'] . "-" . $data['date']['month'] . "-" . $data['date']['day'];
		$this->set('Today', $Today);
		$monthSeikyuu = date('Y', strtotime('+9hour')) . "年 " . date('m', strtotime('+9hour')) . "月度";
		$this->set('monthSeikyuu', $monthSeikyuu);

		$kurikosi = $data["Zenkai"] - $data["nyuukingaku"] - $data["tyousei"] - $data["sousai"];
		$this->set('kurikosi', $kurikosi);

		//			 $totalseikyuu = $totalkingaku*1.1 + $kurikosi;
		$totalseikyuu = $totalkingaku + $kurikosi;
		$this->set('totalseikyuu', $totalseikyuu);
	}

	public function seikyuudo()
	{
		$data = $this->request->getData();

		$siten = "";
		$this->set('siten', $siten);

		//合計表のみの時にエラーがでないように
		$totalkingaku = 0;
		$totaltax = 0;
		$totalkingakutaxinc = 0;
		$this->set('totalkingaku', $totalkingaku);
		$this->set('totaltax', $totaltax);
		$this->set('totalkingakutaxinc', $totalkingakutaxinc);

		$this->set('flag_utizei', 0);

		$Uriage = array();
		for ($k = 0; $k <= $data["num"]; $k++) {
			$arrUriage = $this->Uriagemasters->find('all', ['conditions' => ['id' => $data["id" . $k]]])->toArray();
			$Uriage = array_merge($Uriage, $arrUriage);
		}

		$arrTotal_dempyou = array();
		$count = count($Uriage);
		for ($k = 0; $k < $count; $k++) {
			$totalkingakutaxinc = 0;
			$Uriagesyousais = $this->Uriagesyousais->find()
				->where(['uriagemasterId' => $Uriage[$k]->id, 'delete_flag' => 0])->order(['num' => 'asc'])->toArray();
			for ($i = 0; $i < count($Uriagesyousais); $i++) {

				if (!empty($Uriagesyousais[$i]->pro)) {

					if ($Uriage[$k]->tax_include_flag == 0) { //税別の場合
						$totaltax = $totaltax + $Uriagesyousais[$i]->price * $Uriagesyousais[$i]->zeiritu / 100;
						$totalkingakutaxinc = $totalkingakutaxinc + $Uriagesyousais[$i]->price + $Uriagesyousais[$i]->price * $Uriagesyousais[$i]->zeiritu / 100;
					} else {
						$totalkingakutaxinc = $totalkingakutaxinc + $Uriagesyousais[$i]->price;
					}
				}
			}
			$arrTotal_dempyou[$k]["dempyou"] = $Uriage[$k]->denpyou_num;
			$arrTotal_dempyou[$k]["total_price"] = $totalkingakutaxinc;
		}

		if (!isset($data["num"])) { //合計表のみの出力

			$Uriage = array();
			$this->set('Uriage', $Uriage);
			$this->set('count', 0);
			$customers = $this->Customers->newEntity();
			$this->set('customers', $customers);

			$total_price = $data["totalseikyuu"];

			$this->set('totalkingaku', 0);

			$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
			$name = $Customer[0]->name;
			$furigana = $Customer[0]->furigana;
			$siten = $Customer[0]->siten;
			$this->set('siten', $siten);
			$namehyouji = $name;
			$this->set('namehyouji', $namehyouji);
			$id = $data["id"];
			$this->set('id', $data["id"]);
			$simebi = $Customer[0]->simebi;
			$this->set('simebi', $simebi);
			$hittyakubi = $Customer[0]->hittyakubi;
			$this->set('hittyakubi', $hittyakubi);
			$nyuukinbi = $Customer[0]->nyuukinbi;
			$this->set('nyuukinbi', $nyuukinbi);

			$kaisyuu = $Customer[0]->kaisyuu;
			if ($kaisyuu == 1) {
				$kaisyuu = "振込";
			} else {
				$kaisyuu = "集金";
			}
			$this->set('kaisyuu', $kaisyuu);

			$yuubin = $Customer[0]->yuubin;
			$address = $Customer[0]->address;
			$keisyou = $Customer[0]->keisyou;

			if ($keisyou == 1) {
				$keisyou = '様';
			} elseif ($keisyou == 2) {
				$keisyou = '御中';
			} elseif ($keisyou == 3) {
				$keisyou = "殿";
			} else {
				$keisyou = "御中";
			}

			//エクセル出力
			$filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\請求書.xlsx'; //読み込みたいファイルの指定
			$reader = new XlsxReader();
			$spreadsheet = $reader->load($filepath);

			$sheet = $spreadsheet->getSheetByName("合計表");
			//				 $sheet->setCellValue('H1', "No.".$Uriage[$s]->denpyou_num);//210714
			$sheet->setCellValue('A2', "  　　〒 " . $yuubin);

			$addressarr =  explode("_", $address);

			$sheet->setCellValue('A3', "　　  " . $addressarr[0]);

			if (isset($addressarr[1])) {
				$sheet->setCellValue('A4', "　　　　  " . $addressarr[1]);
			} else {
				$sheet->setCellValue('A4', "　　");
			}

			$namearr =  explode("_", $namehyouji);

			if (isset($namearr[1])) {

				$sheet->unmergeCells('B7:E8');
				$sheet->mergeCells('B7:E7');
				$sheet->mergeCells('B8:E8');

				$sheet->setCellValue('B7', "　" . $namearr[0]);
				$sheet->setCellValue('B8', "　　 " . $namearr[1]);
				$sheet->setCellValue('F7', $keisyou);

				$sheet->setCellValue('M7', "　" . $namearr[0]);
				$sheet->setCellValue('M8', "　　 " . $namearr[1]);
				$sheet->setCellValue('Q7', $keisyou);
			} else {

				$str_mblen_name = mb_strlen($namearr[0]);

				if ($str_mblen_name > 17) {

					$sheet->unmergeCells('B7:E8');
					$sheet->unmergeCells('F7:F8');
					$sheet->mergeCells('B7:F8');

					$sheet->setCellValue('B7', "　" . $namearr[0] . "　" . $keisyou . "　");
					$sheet->getStyle("B7")->getAlignment()->setVertical(Align::VERTICAL_BOTTOM);
					$sheet->getStyle("B7")->getAlignment()->setShrinkToFit(true); //縮小して表示
					$sheet->getStyle("B7")->getAlignment()->setHorizontal(Align::HORIZONTAL_CENTER);

					$sheet->unmergeCells('M7:P7');
					$sheet->unmergeCells('M8:P8');
					$sheet->mergeCells('M7:P8');
					$sheet->setCellValue('M7', "　" . $namearr[0]);
					$sheet->getStyle("M7")->getAlignment()->setShrinkToFit(true); //縮小して表示

					$sheet->setCellValue('Q7', $keisyou);
				} else {

					$sheet->setCellValue('B7', "　" . $namearr[0]);
					$sheet->setCellValue('F7', $keisyou);
					$sheet->unmergeCells('M7:P7');
					$sheet->unmergeCells('M8:P8');
					$sheet->mergeCells('M7:P8');
					$sheet->setCellValue('M7', "　" . $namearr[0]);
					$sheet->setCellValue('Q7', $keisyou);
				}
			}

			$year = (int)(mb_substr($data["seikyuubi"], 0, 4));
			$month = (int)(mb_substr($data["seikyuubi"], 5, 2));
			$day = (int)(mb_substr($data["seikyuubi"], 8, 2));
			$dateexcl = $year . " 年 " . $month . " 月 " . $day . " 日";

			if ($data["datehyouji_flag"] == 1) {
				$sheet->setCellValue('H2', " 年        月        日 締 切 分");
			} else {
				$sheet->setCellValue('H2', $dateexcl . "   締   切   分");
			}

			$sheet->setCellValue('A15', $data["Zenkai"]);
			$sheet->setCellValue('C15', $data["nyuukingaku"]);
			$sheet->setCellValue('D15', $data["tyousei"]);
			$sheet->setCellValue('E15', $data["sousai"]);
			$sheet->setCellValue('F15', $data["kurikosi"]);

			$sheet->setCellValue('H15', 0);
			$sheet->setCellValue('I15', 0);

			$sheet->setCellValue('J15', $data["totalseikyuu"]); //合計表のみの場合は税抜（すでに税込）

			$writer = new XlsxWriter($spreadsheet);

			//Sheet1を削除
			$sheetIndex = $spreadsheet->getIndex(
				$spreadsheet->getSheetByName('Sheet1')
			);
			$spreadsheet->removeSheetByIndex($sheetIndex);

			$datetime = date('H時i分s秒出力', strtotime('+9hour'));
			$year = date('Y', strtotime('+9hour'));
			$month = date('m', strtotime('+9hour'));
			$day = date('d', strtotime('+9hour'));

			if (is_dir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/請求書/$year/$month/$day")) { //ディレクトリが存在すればOK

				$file_name = $namehyouji . $siten . "_" . $datetime . ".xlsx";
				$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/請求書/$year/$month/$day/$file_name"; //出力したいファイルの指定

			} else { //ディレクトリが存在しなければ作成する

				mkdir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/請求書/$year/$month/$day", 0777, true);
				$file_name = $namehyouji . $siten . "_" . $datetime . ".xlsx";
				$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/請求書/$year/$month/$day/$file_name"; //出力したいファイルの指定

			}

			$mesxlsx = "「エクセル出力/請求書/" . $year . "/" . $month . "/" . $day . "」フォルダにエクセルシート「" . $file_name . "」が出力されました。";
			$this->set('mesxlsx', $mesxlsx);

			$writer->save($outfilepath);

			//データベース登録
			$tourokuArr = array();

			$total_price = $data["totalseikyuu"];

			$tourokuArr = array(
				'customerId' => $data["id"], 'furigana' => $furigana,
				'date_seikyuu' => $data["seikyuubi"], 'nyuukingaku' => $data["nyuukingaku"], 'tyousei' => $data["tyousei"],
				'sousai' => $data["sousai"], 'total_price' => $total_price, 'kurikosi' => $data["kurikosi"],
				'nontax_flag' => 0, 'delete_flag' => 0, 'created_at' => date('Y-m-d H:i:s', strtotime('+9hour'))
			);

			$Seikyuus = $this->Seikyuus->patchEntity($this->Seikyuus->newEntity(), $tourokuArr);
			$connection = ConnectionManager::get('default'); //トランザクション1
			// トランザクション開始2
			$connection->begin(); //トランザクション3
			try { //トランザクション4
				if ($this->Seikyuus->save($Seikyuus)) {

					$mes = "※登録されました";
					$this->set('mes', $mes);
					$connection->commit(); // コミット5

				} else {

					$mes = "※登録されませんでした";
					$this->set('mes', $mes);
					$this->Flash->error(__('This data could not be saved. Please, try again.'));
					throw new Exception(Configure::read("M.ERROR.INVALID")); //失敗6

				}
			} catch (Exception $e) { //トランザクション7
				//ロールバック8
				$connection->rollback(); //トランザクション9
			} //トランザクション10

		} else { //合計表以外も出力

			$Uriage = array();
			for ($k = 0; $k <= $data["num"]; $k++) {
				$arrUriage = $this->Uriagemasters->find('all', ['conditions' => ['id' => $data["id" . $k]]])->toArray();
				$Uriage = array_merge($Uriage, $arrUriage);
			}

			$count = count($Uriage);
			$this->set('count', $count);
		}

		$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["id"]]])->toArray();
		$name = $Customer[0]->name;
		$furigana = $Customer[0]->furigana;
		$siten = $Customer[0]->siten;
		$namehyouji = $name . " " . $siten;
		$this->set('namehyouji', $namehyouji);
		$id = $data["id"];
		$this->set('id', $data["id"]);
		$simebi = $Customer[0]->simebi;
		$this->set('simebi', $simebi);
		$hittyakubi = $Customer[0]->hittyakubi;
		$this->set('hittyakubi', $hittyakubi);
		$nyuukinbi = $Customer[0]->nyuukinbi;
		$this->set('nyuukinbi', $nyuukinbi);
		$kaisyuu = $Customer[0]->kaisyuu;
		if ($kaisyuu == 1) {
			$kaisyuu = "振込";
		} else {
			$kaisyuu = "集金";
		}
		$this->set('kaisyuu', $kaisyuu);
		$yuubin = $Customer[0]->yuubin;
		$address = $Customer[0]->address;
		$keisyou = $Customer[0]->keisyou;

		if ($keisyou == 1) {
			$keisyou = '様';
		} elseif ($keisyou == 2) {
			$keisyou = '御中';
		} elseif ($keisyou == 3) {
			$keisyou = "殿";
		} else {
			$keisyou = "御中";
		}

		$countSheet = 0;

		for ($s = 0; $s < count($Uriage); $s++) {

			$totalkingaku = 0;
			$totaltax = 0;
			$totalkingakutaxinc = 0;
			$arrPro_1 = array();
			$arrTaxinc = array();
			$arrDenpyou = array();
			$arrSyuturyoku = array();
			$arrTotalprice = array();

			if ($count > 0) {

				$this->set('count', $count);

				for ($k = 0; $k < $count; $k++) {

					$arrDenpyou[] = $Uriage[$k]->denpyou_num;
					$arrSyuturyoku[] = $Uriage[$k]->uriagebi->format('m/d');
					$arrTaxinc[] = $Uriage[$k]->tax_include_flag;

					$Uriagesyousais = $this->Uriagesyousais->find()
						->where(['uriagemasterId' => $Uriage[$k]->id, 'delete_flag' => 0])->order(['num' => 'asc'])->toArray();

					$arrPro_1[] = $Uriagesyousais[0]->pro;

					${"Totaltax8" . $k} = 0;
					${"Totaltax10" . $k} = 0;
					${"Totalprice" . $k} = 0;
					${"Totaltax" . $k} = 0;
					for ($i = 0; $i < count($Uriagesyousais); $i++) {

						if (!empty($Uriagesyousais[$i]->pro)) {

							$totalkingaku = $totalkingaku + $Uriagesyousais[$i]->price;
							if ($Uriagesyousais[$i]->zeiritu == 8) {
								${"Totaltax8" . $k} = ${"Totaltax8" . $k} + $Uriagesyousais[$i]->price * $Uriagesyousais[$i]->zeiritu / 100;
							} elseif ($Uriagesyousais[$i]->zeiritu == 10) {
								${"Totaltax10" . $k} = ${"Totaltax10" . $k} + $Uriagesyousais[$i]->price * $Uriagesyousais[$i]->zeiritu / 100;
							}
							${"Totalprice" . $k} = ${"Totalprice" . $k} + $Uriagesyousais[$i]->price;
							$zeiritu = 10;
							if (strlen($Uriagesyousais[$i]->zeiritu) > 0) {
								$zeiritu = $Uriagesyousais[$i]->zeiritu;
							}
							${"Totaltax" . $k} = ${"Totaltax" . $k} + $Uriagesyousais[$i]->price * $zeiritu / 100;

							if ($Uriage[$k]->tax_include_flag == 0) { //税別の場合
								$totaltax = $totaltax + $Uriagesyousais[$i]->price * $zeiritu / 100;
								$totalkingakutaxinc = $totalkingakutaxinc + $Uriagesyousais[$i]->price + $Uriagesyousais[$i]->price * $zeiritu / 100;
							} else {
								$totalkingakutaxinc = $totalkingakutaxinc + $Uriagesyousais[$i]->price;
							}

							${"Uriagemasters" . $k} = $this->Uriagemasters->find('all', ['conditions' => ['id' => $Uriagesyousais[$i]->uriagemasterId]])->toArray();
							${"namehyouji" . $k} = ${"Uriagemasters" . $k}[0]->customer;
							$this->set("namehyouji" . $k, ${"namehyouji" . $k});
						}
					}
					$this->set("Totaltax8" . $k, ${"Totaltax8" . $k});
					$this->set("Totaltax10" . $k, ${"Totaltax10" . $k});
					$this->set("Totalprice" . $k, ${"Totalprice" . $k});
					$this->set("Totaltax" . $k, ${"Totaltax" . $k});
				}
			}

			$this->set('totalkingaku', $totalkingaku);
			$this->set('totaltax', $totaltax);
			$this->set('totalkingakutaxinc', $totalkingakutaxinc);
			$this->set('arrPro_1', $arrPro_1);
			$this->set('arrTaxinc', $arrTaxinc);
			$this->set('arrDenpyou', $arrDenpyou);
			$this->set('arrSyuturyoku', $arrSyuturyoku);

			$customers = $this->Customers->newEntity();
			$this->set('customers', $customers);

			$monthSeikyuu = date('Y', strtotime('+9hour')) . "年 " . date('m', strtotime('+9hour')) . "月度";
			$this->set('monthSeikyuu', $monthSeikyuu);

			$tourokuArr = array();

			$total_price = $data["totalkingakutaxinc"] + $data["kurikosi"];

			$tourokuArr = array(
				'customerId' => $data["id"], 'furigana' => $furigana,
				'date_seikyuu' => $data["seikyuubi"], 'nyuukingaku' => $data["nyuukingaku"], 'tyousei' => $data["tyousei"],
				'sousai' => $data["sousai"], 'total_price' => $total_price, 'kurikosi' => $data["kurikosi"],
				'nontax_flag' => 0, 'delete_flag' => 0, 'created_at' => date('Y-m-d H:i:s', strtotime('+9hour'))
			);

			$seikyuus = $this->Seikyuus->newEntity();
			$this->set('seikyuus', $seikyuus);

			$Seikyuu = $this->Seikyuus->find('all')->order(['id' => 'desc'])->toArray();
			if (isset($Seikyuu[0])) {
				$SeikyuuId = $Seikyuu[0]->id + 1;
			} else {
				$SeikyuuId = 1;
			}

			$year = (int)(mb_substr($data["seikyuubi"], 0, 4));
			$month = (int)(mb_substr($data["seikyuubi"], 5, 2));
			$day = (int)(mb_substr($data["seikyuubi"], 8, 2));
			$dateexcl = $year . " 年 " . $month . " 月 " . $day . " 日";

			if ($count > 0) {

				for ($k = 0; $k < 1; $k++) {

					$arrPros = array();
					${"arrPros" . $k} = array();

					$Uriagesyousais = $this->Uriagesyousais->find()
						->where(['uriagemasterId' => $Uriage[$s]->id, 'delete_flag' => 0])->order(['num' => 'asc'])->toArray();

					for ($i = 0; $i < count($Uriagesyousais); $i++) {

						if (!empty($Uriagesyousais[$i]->pro)) {

							$arrPros[] = array(
								'pro' => $Uriagesyousais[$i]->pro, 'amount' => $Uriagesyousais[$i]->amount,
								'tani' => $Uriagesyousais[$i]->tani, 'tanka' => $Uriagesyousais[$i]->tanka,
								'price' => $Uriagesyousais[$i]->price, 'bik' => $Uriagesyousais[$i]->bik
							);

							${"arrPros" . $k}[] = array(
								'pro' => $Uriagesyousais[$i]->pro, 'amount' => $Uriagesyousais[$i]->amount,
								'tani' => $Uriagesyousais[$i]->tani, 'tanka' => $Uriagesyousais[$i]->tanka,
								'price' => $Uriagesyousais[$i]->price, 'bik' => $Uriagesyousais[$i]->bik
							);
						}
					}
				}
			}

			$amari = count($arrPros) % 20;
			if ($amari == 0) {
				$syou = floor(count($arrPros) / 20) - 1;
			} else {
				$syou = floor(count($arrPros) / 20);
			}

			if ($s == 0) { //合計表

				//エクセル出力
				$filepath = 'C:\xampp\htdocs\CakePHPapp\webroot\エクセル原本\請求書.xlsx'; //読み込みたいファイルの指定
				$reader = new XlsxReader();
				$spreadsheet = $reader->load($filepath);

				$sheet = $spreadsheet->getSheetByName("合計表");

				$sheet->setCellValue('A2', "  　　〒 " . $yuubin);

				$addressarr =  explode("_", $address);

				$sheet->setCellValue('A3', "　　  " . $addressarr[0]);

				if (isset($addressarr[1])) {
					$sheet->setCellValue('A4', "　　　　  " . $addressarr[1]);
				} else {
					$sheet->setCellValue('A4', "　　");
				}

				$namearr =  explode("_", $namehyouji);

				if (isset($namearr[1])) {

					$sheet->unmergeCells('B7:E8');
					$sheet->mergeCells('B7:E7');
					$sheet->mergeCells('B8:E8');

					$sheet->setCellValue('B7', "　" . $namearr[0]);
					$sheet->setCellValue('B8', "　　 " . $namearr[1]);
					$sheet->setCellValue('F7', $keisyou);

					$sheet->setCellValue('M7', "　" . $namearr[0]);
					$sheet->setCellValue('M8', "　　 " . $namearr[1]);
					$sheet->setCellValue('Q7', $keisyou);
				} else {

					$str_mblen_name = mb_strlen($namearr[0]);

					if ($str_mblen_name > 17) {

						$sheet->unmergeCells('B7:E8');
						$sheet->unmergeCells('F7:F8');
						$sheet->mergeCells('B7:F8');

						$sheet->setCellValue('B7', "　" . $namearr[0] . "　" . $keisyou . "　");
						$sheet->getStyle("B7")->getAlignment()->setVertical(Align::VERTICAL_BOTTOM);
						$sheet->getStyle("B7")->getAlignment()->setShrinkToFit(true); //縮小して表示
						$sheet->getStyle("B7")->getAlignment()->setHorizontal(Align::HORIZONTAL_CENTER);

						$sheet->unmergeCells('M7:P7');
						$sheet->unmergeCells('M8:P8');
						$sheet->mergeCells('M7:P8');
						$sheet->setCellValue('M7', "　" . $namearr[0]);
						$sheet->getStyle("M7")->getAlignment()->setShrinkToFit(true); //縮小して表示

						$sheet->setCellValue('Q7', $keisyou);
					} else {

						$sheet->setCellValue('B7', "　" . $namearr[0]);
						$sheet->setCellValue('F7', $keisyou);
						$sheet->unmergeCells('M7:P7');
						$sheet->unmergeCells('M8:P8');
						$sheet->mergeCells('M7:P8');
						$sheet->setCellValue('M7', "　" . $namearr[0]);
						$sheet->setCellValue('Q7', $keisyou);
					}
				}

				if ($data["datehyouji_flag"] == 1) {
					$sheet->setCellValue('H2', " 年        月        日 締 切 分");
				} else {
					$sheet->setCellValue('H2', $dateexcl . "   締   切   分");
				}

				$sheet->setCellValue('A15', $data["Zenkai"]);
				$sheet->setCellValue('C15', $data["nyuukingaku"]);
				$sheet->setCellValue('D15', $data["tyousei"]);
				$sheet->setCellValue('E15', $data["sousai"]);
				$sheet->setCellValue('F15', $data["kurikosi"]);

				$sheet->setCellValue('H15', $totalkingaku);
				$sheet->setCellValue('I15', $data["totaltax"]);
				$sheet->setCellValue('J15', $data["totalkingakutaxinc"] + $data["kurikosi"]);

				$Total_all = 0;
				for ($i = 0; $i < count($arrTotal_dempyou); $i++) {
					if ($i < 20) {
						$num = 25 + $i;
						$sheet->setCellValue("B" . $num, $arrTotal_dempyou[$i]["dempyou"]);
						$sheet->setCellValue("D" . $num, $arrTotal_dempyou[$i]["total_price"]);
					} else {
						$num = 5 + $i;
						$sheet->setCellValue("F" . $num, $arrTotal_dempyou[$i]["dempyou"]);
						$sheet->setCellValue("I" . $num, $arrTotal_dempyou[$i]["total_price"]);
					}
					$Total_all = $Total_all +  $arrTotal_dempyou[$i]["total_price"];
				}
				$sheet->setCellValue('F45', $Total_all);

				$writer = new XlsxWriter($spreadsheet);
			}

			//ここから請求明細
			$countSheet = $countSheet + 1;

			$baseSheet = $spreadsheet->getSheet(1);
			$newSheet = $baseSheet->copy();
			$newSheet->setTitle("Sheet_" . $countSheet);
			$spreadsheet->addSheet($newSheet);

			$writer = new XlsxWriter($spreadsheet);

			$sheet = $spreadsheet->getSheetByName("Sheet_" . $countSheet);
			$sheet->setCellValue('J1', "No." . $Uriage[$s]->denpyou_num);
			$sheet->setCellValue('U1', "No." . $Uriage[$s]->denpyou_num);
			$sheet->setCellValue('A3', "〒 " . $yuubin);
			$sheet->setCellValue('L3', "〒 " . $yuubin);

			$addressarr =  explode("_", $Uriage[$s]->address);
			$sheet->setCellValue('A4', "　　" . $addressarr[0]);
			$sheet->setCellValue('L4', "　　" . $addressarr[0]);

			if (isset($addressarr[1])) {
				$sheet->setCellValue('A5', "　　　　" . $addressarr[1]);
				$sheet->setCellValue('L5', "　　　　" . $addressarr[1]);
			} else {
				$sheet->setCellValue('A5', "　　");
				$sheet->setCellValue('L5', "　　");
			}
			$namearr =  explode("_", $Uriage[$s]->customer);

			if (isset($namearr[1])) {
				$sheet->setCellValue('A6', $namearr[0]);
				$sheet->setCellValue('A7', "　　 " . $namearr[1]);
				$sheet->setCellValue('L6', $namearr[0]);
				$sheet->setCellValue('L7', "　　 " . $namearr[1]);
			} else {
				$sheet->unmergeCells('A6:E6');
				$sheet->unmergeCells('A7:E7');
				$sheet->mergeCells('A6:E7');
				$sheet->setCellValue('A6', $namearr[0]);
				$sheet->unmergeCells('L6:P6');
				$sheet->unmergeCells('L7:P7');
				$sheet->mergeCells('L6:P7');
				$sheet->setCellValue('L6', $namearr[0]);
			}

			$sheet->setCellValue('F6', $keisyou);
			$sheet->setCellValue('Q6', $keisyou);

			if ($data["datehyouji_flag"] == 1) {
				$sheet->setCellValue('J3', " 年        月        日");
				$sheet->setCellValue('U3', " 年        月        日");
			} else {
				$sheet->setCellValue('J3', $Uriage[$s]->uriagebi->format('Y 年 n 月 j 日') . "");
				$sheet->setCellValue('U3', $Uriage[$s]->uriagebi->format('Y 年 n 月 j 日') . "");
			}

			$sheet->setCellValue('E15', $data["totalkingakutaxinc"]);
			$sheet->setCellValue('P15', $data["totalkingakutaxinc"]);

			if ($arrTaxinc[$s] > 0) { //内税の場合
				$sheet->setCellValue('E15', ${"Totalprice" . $s});
				$sheet->setCellValue('P15', ${"Totalprice" . $s});
			} else {
				$sheet->setCellValue('E15', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
				$sheet->setCellValue('P15', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
			}

			$pro_check = 0;

			if ($amari == 0) { //以下余白がいらない場合

				for ($i = 0; $i < 20; $i++) {

					if ($i == count($arrPros)) {
						break;
					}

					$num = 18 + $i;

					$sheet->setCellValue("A" . $num, $arrPros[$i]["pro"]);
					$sheet->setCellValue("L" . $num, $arrPros[$i]["pro"]);
					$sheet->setCellValue("F" . $num, $arrPros[$i]["amount"]);
					$sheet->setCellValue("Q" . $num, $arrPros[$i]["amount"]);
					$sheet->setCellValue("G" . $num, $arrPros[$i]["tani"]);
					$sheet->setCellValue("R" . $num, $arrPros[$i]["tani"]);

					if ($arrTaxinc[$s] > 0) { //内税の場合

						$sheet->setCellValue("H" . $num, $arrPros[$i]["tanka"]);
						$sheet->setCellValue("S" . $num, $arrPros[$i]["tanka"]);
						$sheet->setCellValue("I" . $num, $arrPros[$i]["price"]);
						$sheet->setCellValue("T" . $num, $arrPros[$i]["price"]);

						$sheet->setCellValue('I38', "　");
						$sheet->setCellValue('H38', "　");
						$sheet->setCellValue('I39', "　");
						$sheet->setCellValue('H39', "　");
						$sheet->setCellValue('I40', "　");
						$sheet->setCellValue('H40', "　");
						$sheet->setCellValue('I41', "　");
						$sheet->setCellValue('H41', "　");

						$sheet->setCellValue('S38', "　");
						$sheet->setCellValue('T38', "　");
						$sheet->setCellValue('S39', "　");
						$sheet->setCellValue('T39', "　");
						$sheet->setCellValue('S40', "　");
						$sheet->setCellValue('T40', "　");
						$sheet->setCellValue('S41', "　");
						$sheet->setCellValue('T41', "　");

						if ($amari == 0 && $syou == 0) {

							$sheet->setCellValue('H41', "合計");
							$sheet->setCellValue('I41', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
							$sheet->setCellValue('S41', "合計");
							$sheet->setCellValue('T41', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
						}
					} else {

						$sheet->setCellValue("H" . $num, $arrPros[$i]["tanka"]);
						$sheet->setCellValue("I" . $num, $arrPros[$i]["price"]);
						$sheet->setCellValue("S" . $num, $arrPros[$i]["tanka"]);
						$sheet->setCellValue("T" . $num, $arrPros[$i]["price"]);

						if ($amari == 0 && $syou == 0) {

							$sheet->setCellValue('I38', ${"Totalprice" . $s});
							$sheet->setCellValue('T38', ${"Totalprice" . $s});
							$sheet->setCellValue('I39', ${"Totaltax8" . $s});
							$sheet->setCellValue('T39', ${"Totaltax8" . $s});
							$sheet->setCellValue('I40', ${"Totaltax10" . $s});
							$sheet->setCellValue('T40', ${"Totaltax10" . $s});
							$sheet->setCellValue('I41', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
							$sheet->setCellValue('T41', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
						} else {

							$sheet->setCellValue('I38', "　");
							$sheet->setCellValue('H38', "　");
							$sheet->setCellValue('I39', "　");
							$sheet->setCellValue('H39', "　");
							$sheet->setCellValue('I40', "　");
							$sheet->setCellValue('H40', "　");

							$sheet->setCellValue('S38', "　");
							$sheet->setCellValue('T38', "　");
							$sheet->setCellValue('S39', "　");
							$sheet->setCellValue('T39', "　");
							$sheet->setCellValue('S40', "　");
							$sheet->setCellValue('T40', "　");
						}
					}
					$sheet->setCellValue("K" . $num, $arrPros[$i]["bik"]);
					$sheet->setCellValue("V" . $num, $arrPros[$i]["bik"]);
				}
			} else { //以下余白がいる場合

				for ($i = 0; $i < 20; $i++) {

					if ($i == count($arrPros) + 1) {
						break;
					}

					$num = 18 + $i;

					if ($i < count($arrPros)) {

						$sheet->setCellValue("A" . $num, $arrPros[$i]["pro"]);
						$sheet->setCellValue("L" . $num, $arrPros[$i]["pro"]);
						$sheet->setCellValue("F" . $num, $arrPros[$i]["amount"]);
						$sheet->setCellValue("Q" . $num, $arrPros[$i]["amount"]);
						$sheet->setCellValue("G" . $num, $arrPros[$i]["tani"]);
						$sheet->setCellValue("R" . $num, $arrPros[$i]["tani"]);

						if ($arrTaxinc[$s] > 0) { //内税の場合

							$sheet->setCellValue("H" . $num, $arrPros[$i]["tanka"]);
							$sheet->setCellValue("S" . $num, $arrPros[$i]["tanka"]);
							$sheet->setCellValue("I" . $num, $arrPros[$i]["price"]);
							$sheet->setCellValue("T" . $num, $arrPros[$i]["price"]);

							$sheet->setCellValue('I38', "　");
							$sheet->setCellValue('H38', "　");
							$sheet->setCellValue('I39', "　");
							$sheet->setCellValue('H39', "　");
							$sheet->setCellValue('I40', "　");
							$sheet->setCellValue('H40', "　");
							$sheet->setCellValue('I41', "　");
							$sheet->setCellValue('H41', "　");

							$sheet->setCellValue('S38', "　");
							$sheet->setCellValue('T38', "　");
							$sheet->setCellValue('S39', "　");
							$sheet->setCellValue('T39', "　");
							$sheet->setCellValue('S40', "　");
							$sheet->setCellValue('T40', "　");
							$sheet->setCellValue('S41', "　");
							$sheet->setCellValue('T41', "　");

							if ($syou == 0) {

								$sheet->setCellValue('H41', "合計");
								$sheet->setCellValue('I41', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
								$sheet->setCellValue('S41', "合計");
								$sheet->setCellValue('T41', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
							}
						} else {

							$sheet->setCellValue("H" . $num, $arrPros[$i]["tanka"]);
							$sheet->setCellValue("I" . $num, $arrPros[$i]["price"]);
							$sheet->setCellValue("S" . $num, $arrPros[$i]["tanka"]);
							$sheet->setCellValue("T" . $num, $arrPros[$i]["price"]);

							if ($syou == 0) {

								$sheet->setCellValue('I38', ${"Totalprice" . $s});
								$sheet->setCellValue('T38', ${"Totalprice" . $s});
								$sheet->setCellValue('I39', ${"Totaltax8" . $s});
								$sheet->setCellValue('T39', ${"Totaltax8" . $s});
								$sheet->setCellValue('I40', ${"Totaltax10" . $s});
								$sheet->setCellValue('T40', ${"Totaltax10" . $s});
								$sheet->setCellValue('I41', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
								$sheet->setCellValue('T41', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
							} else {

								$sheet->setCellValue('I38', "　");
								$sheet->setCellValue('H38', "　");
								$sheet->setCellValue('I39', "　");
								$sheet->setCellValue('H39', "　");
								$sheet->setCellValue('I40', "　");
								$sheet->setCellValue('H40', "　");
								$sheet->setCellValue('I41', "　");
								$sheet->setCellValue('H41', "　");

								$sheet->setCellValue('S38', "　");
								$sheet->setCellValue('T38', "　");
								$sheet->setCellValue('S39', "　");
								$sheet->setCellValue('T39', "　");
								$sheet->setCellValue('S40', "　");
								$sheet->setCellValue('T40', "　");
								$sheet->setCellValue('S41', "　");
								$sheet->setCellValue('T41', "　");
							}
						}
						$sheet->setCellValue("K" . $num, $arrPros[$i]["bik"]);
						$sheet->setCellValue("V" . $num, $arrPros[$i]["bik"]);
					} else {

						$sheet->setCellValue("A" . $num, "以下余白");
						$sheet->setCellValue("L" . $num, "以下余白");
					}
				}
			}

			$writer = new XlsxWriter($spreadsheet);

			for ($j = 2; $j < 2 + $syou; $j++) {

				$countSheet = $countSheet + 1;

				$baseSheet = $spreadsheet->getSheet(1);
				$newSheet = $baseSheet->copy();
				$newSheet->setTitle("Sheet_" . $countSheet);
				$spreadsheet->addSheet($newSheet);

				$writer = new XlsxWriter($spreadsheet);

				$sheet = $spreadsheet->getSheetByName("Sheet_" . $countSheet);

				$sheet->setCellValue('J1', "No." . $Uriage[$s]->denpyou_num);
				$sheet->setCellValue('U1', "No." . $Uriage[$s]->denpyou_num);
				$sheet->setCellValue('A3', "〒 " . $yuubin);
				$sheet->setCellValue('L3', "〒 " . $yuubin);

				$addressarr =  explode("_", $Uriage[$s]->address);

				$sheet->setCellValue('A4', "　　" . $addressarr[0]);
				$sheet->setCellValue('L4', "　　" . $addressarr[0]);

				if (isset($addressarr[1])) {
					$sheet->setCellValue('A5', "　　　　" . $addressarr[1]);
					$sheet->setCellValue('L5', "　　　　" . $addressarr[1]);
				} else {
					$sheet->setCellValue('A5', "　　");
					$sheet->setCellValue('L5', "　　");
				}

				$namearr =  explode("_", $Uriage[$s]->customer);
				if (isset($namearr[1])) {
					$sheet->setCellValue('A6', $namearr[0]);
					$sheet->setCellValue('A7', "　　 " . $namearr[1]);
					$sheet->setCellValue('L6', $namearr[0]);
					$sheet->setCellValue('L7', "　　 " . $namearr[1]);
				} else {
					$sheet->unmergeCells('A6:E6');
					$sheet->unmergeCells('A7:E7');
					$sheet->mergeCells('A6:E7');
					$sheet->setCellValue('A6', $namearr[0]);
					$sheet->unmergeCells('L6:P6');
					$sheet->unmergeCells('L7:P7');
					$sheet->mergeCells('L6:P7');
					$sheet->setCellValue('L6', $namearr[0]);
				}

				$sheet->setCellValue('F6', $keisyou);
				$sheet->setCellValue('Q6', $keisyou);

				if ($data["datehyouji_flag"] == 1) {
					$sheet->setCellValue('J3', " 年        月        日");
					$sheet->setCellValue('U3', " 年        月        日");
				} else {
					$sheet->setCellValue('J3', $Uriage[$s]->uriagebi->format('Y 年 n 月 j 日') . "");
					$sheet->setCellValue('U3', $Uriage[$s]->uriagebi->format('Y 年 n 月 j 日') . "");
				}

				if ($arrTaxinc[$s] > 0) { //内税の場合
					$sheet->setCellValue('E15', ${"Totalprice" . $s});
					$sheet->setCellValue('P15', ${"Totalprice" . $s});
				} else {
					$sheet->setCellValue('E15', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
					$sheet->setCellValue('P15', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
				}

				for ($i = 20 * ($j - 1); $i < 20 * $j; $i++) {

					if ($i == count($arrPros) + 1) {
						break;
					}

					$num = 18 + $i - 20 * ($j - 1);

					if ($i < count($arrPros)) {

						$sheet->setCellValue("A" . $num, $arrPros[$i]["pro"]);
						$sheet->setCellValue("L" . $num, $arrPros[$i]["pro"]);
						$sheet->setCellValue("F" . $num, $arrPros[$i]["amount"]);
						$sheet->setCellValue("Q" . $num, $arrPros[$i]["amount"]);
						$sheet->setCellValue("G" . $num, $arrPros[$i]["tani"]);
						$sheet->setCellValue("R" . $num, $arrPros[$i]["tani"]);

						if ($arrTaxinc[$s] > 0) { //内税の場合

							$sheet->setCellValue("H" . $num, $arrPros[$i]["tanka"]);
							$sheet->setCellValue("I" . $num, $arrPros[$i]["price"]);
							$sheet->setCellValue("S" . $num, $arrPros[$i]["tanka"]);
							$sheet->setCellValue("T" . $num, $arrPros[$i]["price"]);

							$sheet->setCellValue('I38', "　");
							$sheet->setCellValue('H38', "　");
							$sheet->setCellValue('I39', "　");
							$sheet->setCellValue('H39', "　");
							$sheet->setCellValue('I40', "　");
							$sheet->setCellValue('H40', "　");
							$sheet->setCellValue('I41', "　");
							$sheet->setCellValue('H41', "　");

							$sheet->setCellValue('S38', "　");
							$sheet->setCellValue('T38', "　");
							$sheet->setCellValue('S39', "　");
							$sheet->setCellValue('T39', "　");
							$sheet->setCellValue('S40', "　");
							$sheet->setCellValue('T40', "　");
							$sheet->setCellValue('S41', "　");
							$sheet->setCellValue('T41', "　");

							if ($j == 1 + $syou) {

								$sheet->setCellValue('H41', "合計");
								$sheet->setCellValue('S41', "合計");
								$sheet->setCellValue('I41', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
								$sheet->setCellValue('T41', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
							}
						} else {

							$sheet->setCellValue("H" . $num, $arrPros[$i]["tanka"]);
							$sheet->setCellValue("I" . $num, $arrPros[$i]["price"]);
							$sheet->setCellValue("S" . $num, $arrPros[$i]["tanka"]);
							$sheet->setCellValue("T" . $num, $arrPros[$i]["price"]);

							if ($j == 1 + $syou) {

								$sheet->setCellValue('I38', ${"Totalprice" . $s});
								$sheet->setCellValue('T38', ${"Totalprice" . $s});
								$sheet->setCellValue('I39', ${"Totaltax8" . $s});
								$sheet->setCellValue('T39', ${"Totaltax8" . $s});
								$sheet->setCellValue('I40', ${"Totaltax10" . $s});
								$sheet->setCellValue('T40', ${"Totaltax10" . $s});
								$sheet->setCellValue('I41', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
								$sheet->setCellValue('T41', ${"Totalprice" . $s} + ${"Totaltax8" . $s} + ${"Totaltax10" . $s});
							} else {

								$sheet->setCellValue('I38', "　");
								$sheet->setCellValue('H38', "　");
								$sheet->setCellValue('I39', "　");
								$sheet->setCellValue('H39', "　");
								$sheet->setCellValue('I40', "　");
								$sheet->setCellValue('H40', "　");

								$sheet->setCellValue('S38', "　");
								$sheet->setCellValue('T38', "　");
								$sheet->setCellValue('S39', "　");
								$sheet->setCellValue('T39', "　");
								$sheet->setCellValue('S40', "　");
								$sheet->setCellValue('T40', "　");
							}
						}
						$sheet->setCellValue("K" . $num, $arrPros[$i]["bik"]);
						$sheet->setCellValue("V" . $num, $arrPros[$i]["bik"]);
					} else {

						$sheet->setCellValue("A" . $num, "以下余白");
						$sheet->setCellValue("L" . $num, "以下余白");
					}
				}
			}

			$writer = new XlsxWriter($spreadsheet);

			if ($s == count($Uriage) - 1) { //最後に出力

				for ($j = 1; $j <= $countSheet; $j++) {

					$sheet = $spreadsheet->getSheetByName("Sheet_" . $j);
					$sheet->setCellValue('K15', $j . "/" . $countSheet . "　");
					$sheet->setCellValue('V15', $j . "/" . $countSheet . "　");
				}

				//Sheet1を削除
				$sheetIndex = $spreadsheet->getIndex(
					$spreadsheet->getSheetByName('Sheet1')
				);
				$spreadsheet->removeSheetByIndex($sheetIndex);

				$datetime = date('H時i分s秒出力', strtotime('+9hour'));
				$year = date('Y', strtotime('+9hour'));
				$month = date('m', strtotime('+9hour'));
				$day = date('d', strtotime('+9hour'));

				if (is_dir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/請求書/$year/$month/$day")) { //ディレクトリが存在すればOK

					$file_name = $namehyouji . $siten . "_" . $datetime . ".xlsx";
					$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/請求書/$year/$month/$day/$file_name"; //出力したいファイルの指定

				} else { //ディレクトリが存在しなければ作成する

					mkdir("C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/請求書/$year/$month/$day", 0777, true);
					$file_name = $namehyouji . $siten . "_" . $datetime . ".xlsx";
					$outfilepath = "C:/xampp/htdocs/CakePHPapp/webroot/エクセル出力/請求書/$year/$month/$day/$file_name"; //出力したいファイルの指定

				}

				$mesxlsx = "「エクセル出力/請求書/" . $year . "/" . $month . "/" . $day . "」フォルダにエクセルシート「" . $file_name . "」が出力されました。";
				$this->set('mesxlsx', $mesxlsx);

				$writer->save($outfilepath);

				/*
//共有フォルダに出力
if(is_dir("C:/datashare/エクセル出力（共有用）/請求書/$year/$month/$day")){//ディレクトリが存在すればOK

	$file_name = $namehyouji.$siten."_".$datetime.".xlsx";
	$outfilepath = "C:/datashare/エクセル出力（共有用）/請求書/$year/$month/$day/$file_name"; //出力したいファイルの指定

}else{//ディレクトリが存在しなければ作成する

	mkdir("C:/datashare/エクセル出力（共有用）/請求書/$year/$month/$day", 0777, true);
	$file_name = $namehyouji.$siten."_".$datetime.".xlsx";
	$outfilepath = "C:/datashare/エクセル出力（共有用）/請求書/$year/$month/$day/$file_name"; //出力したいファイルの指定

}

$mesxlsx = "「エクセル出力（共有用）/請求書/".$year."/".$month."/".$day."」フォルダにエクセルシート「".$file_name."」が出力されました。";
$this->set('mesxlsx',$mesxlsx);

$writer->save($outfilepath);
*/
			}

			if ($s == count($Uriage) - 1) { //最後に登録

				$seikyuu = $this->Seikyuus->patchEntity($seikyuus, $tourokuArr);
				$connection = ConnectionManager::get('default'); //トランザクション1
				// トランザクション開始2
				$connection->begin(); //トランザクション3
				try { //トランザクション4
					if ($this->Seikyuus->save($seikyuu)) {

						$Uriage = array();
						for ($k = 0; $k <= $data["num"]; $k++) {
							$arrUriage = $this->Uriagemasters->find('all', ['conditions' => ['id' => $data["id" . $k]]])->toArray();
							$Uriage = array_merge($Uriage, $arrUriage);
						}

						$count = count($Uriage);

						$SeikyuusId = $this->Seikyuus->find('all', ['conditions' => ['customerId' => $data["id"], 'delete_flag' => 0]])->order(['	id' => 'desc'])->toArray();

						for ($k = 0; $k < $count; $k++) {

							$this->Uriagemasters->updateAll(
								['seikyuuId' => $SeikyuusId[0]->id],
								['id'  => $Uriage[$k]->id]
							);
						}

						$Zandakas = $this->Zandakas->find('all', ['conditions' => ['customerId' => $data["id"], 'delete_flag' => 0]])->toArray();
						if (isset($Zandakas[0])) {

							$this->Zandakas->updateAll(
								['zandaka' => $data["totalseikyuu"], 'koushinbi' =>  date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
								['id'  => $Zandakas[0]->id]
							);
						} else {

							$arrZandaka = array(
								'customerId' => $data["id"], 'furigana' => $furigana, 'zandaka' => $data["totalseikyuu"], 'koushinbi' => date('Y-m-d', strtotime('+9hour')),
								'delete_flag' => 0, 'created_at' => date('Y-m-d H:i:s', strtotime('+9hour'))
							);

							$Zandaka = $this->Zandakas->patchEntity($this->Zandakas->newEntity(), $arrZandaka);
							$this->Zandakas->save($Zandaka);
						}

						$Miseikyuus = $this->Miseikyuus->find('all', ['conditions' => ['customerId' => $data["id"], 'delete_flag' => 0]])->toArray();

						if (isset($Miseikyuus[0])) {

							$miseikyuu = 0;
							for ($k = 0; $k < $data["del_num"]; $k++) {

								$Uriagesyousais = $this->Uriagesyousais->find()
									->where(['uriagemasterId' => $data["del_id" . $k]])->order(['num' => 'asc'])->toArray();

								for ($i = 0; $i < count($Uriagesyousais); $i++) {

									if (!empty($Uriagesyousais[$i]->pro)) {

										$miseikyuu = $miseikyuu + $Uriagesyousais[$i]->price;
									}
								}
							}

							$this->Miseikyuus->updateAll(
								['miseikyuugaku' => $miseikyuu, 'kousinbi' => date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
								['id'  => $Miseikyuus[0]->id]
							);
						}

						$Customer = $this->Customers->find('all', ['conditions' => ['seikyuusakicustomerId' => $data["id"], 'delete_flag' => 0]])->toArray();
						if (isset($Customer[0])) {
							for ($k = 0; $k < count($Customer); $k++) {

								$Miseikyuusids = $this->Miseikyuus->find('all', ['conditions' => ['customerId' => $Customer[$k]->id, 'delete_flag' => 0]])->toArray();

								if (isset($Miseikyuusids[0])) {

									$this->Miseikyuus->updateAll(
										['miseikyuugaku' => 0, 'kousinbi' => date('Y-m-d', strtotime('+9hour')), 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))],
										['id'  => $Miseikyuusids[0]->id]
									);
								}
							}
						}

						$mes = "※下記のように登録されました";
						$this->set('mes', $mes);
						$connection->commit(); // コミット5

					} else {

						$mes = "※登録されませんでした";
						$this->set('mes', $mes);
						$this->Flash->error(__('This data could not be saved. Please, try again.'));
						throw new Exception(Configure::read("M.ERROR.INVALID")); //失敗6

					}
				} catch (Exception $e) { //トランザクション7
					//ロールバック8
					$connection->rollback(); //トランザクション9
				} //トランザクション10

			}
		}
	}

	public function seikyuurirekimenu()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);
	}

	public function seikyuurirekiseikyuuzumiform()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$year = date('Y');
		$this->set('year', $year);
		for ($n = 2010; $n <= $year; $n++) {

			$arrYear[$n] = $n;
		}
		$this->set('arrYear', $arrYear);


		for ($n = 1; $n <= 12; $n++) {

			$arrMonth[$n] = $n;
		}
		$this->set('arrMonth', $arrMonth);
	}

	public function seikyuurirekiseikyuuzumiitiran()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$data = $this->request->getData();

		if (isset($data["date_sta"])) {

			$date_sta = $data['date_sta'];
			$date_fin = $data['date_fin'];

			$date_y = substr($date_sta, 0, 4);
			$date_m = substr($date_sta, 5, 2);
			$this->set('date_y', $date_y);
			$this->set('date_m', $date_m);
		} else {

			if ($data['date_sta_m'] == 12) {
				$date_fin_m = 1;
				$date_fin_y = $data['date_sta_y'] + 1;

				$date_sta = $data['date_sta_y'] . "-" . $data['date_sta_m'] . "-1";
				$date_fin = $date_fin_y . "-" . $date_fin_m . "-1";
				$this->set('date_y', $data['date_sta_y']);
				$this->set('date_m', $data['date_sta_m']);
			} else {

				$date_fin_m = $data['date_sta_m'] + 1;
				$date_fin_y = $data['date_sta_y'];

				$date_sta = $data['date_sta_y'] . "-" . $data['date_sta_m'] . "-1";
				$date_fin = $date_fin_y . "-" . $date_fin_m . "-1";
				$this->set('date_y', $data['date_sta_y']);
				$this->set('date_m', $data['date_sta_m']);
			}
		}

		$this->set('date_sta', $date_sta);
		$this->set('date_fin', $date_fin);

		$date_sta = strtotime($date_sta);
		$date_fin = strtotime($date_fin);

		$Seikyuus = $this->Seikyuus->find()
			->where(['date_seikyuu >=' => $date_sta, 'date_seikyuu <' => $date_fin, 'delete_flag' => 0])
			->order(["date_seikyuu" => "ASC"])->toArray();

		$Uriagetotalmaster = 0;
		$Uriagetotalkurikosi = 0;
		$arrSeikyuus = array();
		$arrCustomerId = array();

		for ($h = 0; $h < count($Seikyuus); $h++) {

			$Customers = $this->Customers->find('all', ['conditions' => ['id' => $Seikyuus[$h]->customerId]])->toArray();
			$customer = $Customers[0]["name"];

			$arrCustomerId[] = [
				"customerId" => $Seikyuus[$h]->customerId,
				"customer" => $customer,
				"seikyuubi" => 0,
				"kingaku" => 0,
			];

			$Uriagemasters = $this->Uriagemasters->find()->where(['seikyuuId' => $Seikyuus[$h]["id"], 'delete_flag' => 0])->toArray();

			if (isset($Uriagemasters[0])) {

				for ($j = 0; $j < count($Uriagemasters); $j++) {

					$Uriagetotalmasterkobetu = 0;

					$uriagebi = $Uriagemasters[$j]->uriagebi->format('Y-m-d');

					$Uriagesyousais = $this->Uriagesyousais->find()
						->where(['uriagemasterId' => $Uriagemasters[$j]->id, 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();

					for ($i = 0; $i < count($Uriagesyousais); $i++) {

						if (!empty($Uriagesyousais[$i]->price)) {

							if ($Uriagemasters[$j]->tax_include_flag == 0) {
								$Uriagetotalmasterkobetu = $Uriagetotalmasterkobetu + $Uriagesyousais[$i]->price * 1.1;
								$Uriagetotalmaster = $Uriagetotalmaster + $Uriagesyousais[$i]->price * 1.1;
							} else {
								$Uriagetotalmasterkobetu = $Uriagetotalmasterkobetu + $Uriagesyousais[$i]->price;
								$Uriagetotalmaster = $Uriagetotalmaster + $Uriagesyousais[$i]->price;
							}
						}

						if ($i == count($Uriagesyousais) - 1) {

							$arrSeikyuus[] = array(
								"denpyou_num" => $Uriagemasters[$j]->denpyou_num,
								"customer" => $Uriagemasters[$j]->customer,
								"customerId" => $Seikyuus[$h]->customerId,
								"uriagebi" => $uriagebi,
								"seikyuubi" => $Seikyuus[$h]->date_seikyuu->format('Y-m-d'),
								"kingaku" => $Uriagetotalmasterkobetu,
								"Id" => "master_" . $Uriagemasters[$j]->id,
							);
						}
					}
				}
			}
		}

		$this->set('arrSeikyuus', $arrSeikyuus);

		$tmp = array();
		$array_result = array();

		foreach ($arrCustomerId as $key => $value) {

			// 配列に値が見つからなければ$tmpに格納
			if (!in_array($value['customerId'], $tmp)) {
				$tmp[] = $value['customerId'];
				$array_result[] = $value;
			}
		}
		$arrCustomerId = $array_result;

		$totalkingakuall = 0;
		$totalkingakutax = 0;

		for ($h = 0; $h < count($arrCustomerId); $h++) {

			$totalkingaku = 0;

			for ($i = 0; $i < count($arrSeikyuus); $i++) {

				if ($arrCustomerId[$h]["customerId"] == $arrSeikyuus[$i]["customerId"]) {

					$totalkingaku = $totalkingaku + $arrSeikyuus[$i]["kingaku"];
					$arrCustomerId[$h]["kingaku"] = $totalkingaku;

					$totalkingakuall = $totalkingakuall + $arrSeikyuus[$i]["kingaku"];

					$arrCustomerId[$h]["seikyuubi"] = $arrSeikyuus[$i]["seikyuubi"];

					$totalkingakutax = $totalkingakutax + round($arrSeikyuus[$i]["kingaku"] * 1.1);
				}
			}
		}

		for ($i = 0; $i < count($arrCustomerId); $i++) {
			if ($arrCustomerId[$i]["seikyuubi"] == 0) {
				unset($arrCustomerId[$i]);
			}
		}
		$arrCustomerId = array_values($arrCustomerId);

		$this->set('arrCustomerId', $arrCustomerId);
		$this->set('totalkingakuall', $totalkingakuall);

		session_start();
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function seikyuurirekimiseikyuuform()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);
	}

	public function seikyuurirekimiseikyuuitiran()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$data = $this->request->getData();

		$date_sta = $data['date_sta']['year'] . "-" . $data['date_sta']['month'] . "-" . $data['date_sta']['day'];
		$date_fin = $data['date_fin']['year'] . "-" . $data['date_fin']['month'] . "-" . $data['date_fin']['day'];
		$this->set('date_sta', $date_sta);
		$this->set('date_fin', $date_fin);

		$Uriagemasters = $this->Uriagemasters->find()->where(['uriagebi >=' => $date_sta, 'uriagebi <=' => $date_fin, 'seikyuuId' => 0, 'delete_flag' => 0])->toArray();

		$Uriagetotalmasterkobetu = 0;
		$totalkingaku = 0;
		$arrSeikyuus = array();

		if (isset($Uriagemasters[0])) {

			for ($j = 0; $j < count($Uriagemasters); $j++) {

				$Uriagetotalmasterkobetu = 0;

				$uriagebi = $Uriagemasters[$j]->uriagebi->format('Y-m-d');

				$Uriagesyousais = $this->Uriagesyousais->find()
					->where(['uriagemasterId' => $Uriagemasters[$j]->id, 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();

				for ($i = 0; $i < count($Uriagesyousais); $i++) {

					if (!empty($Uriagesyousais[$i]->price)) {

						$Uriagetotalmasterkobetu = $Uriagetotalmasterkobetu + $Uriagesyousais[$i]->price;
						$totalkingaku = $totalkingaku + $Uriagesyousais[$i]->price;
					}

					if ($i == count($Uriagesyousais) - 1) {

						$arrSeikyuus[] = array(
							"denpyou_num" => $Uriagemasters[$j]->denpyou_num,
							"customer" => $Uriagemasters[$j]->customer,
							"customerId" => $Uriagemasters[$j]->customerId,
							"uriagebi" => $uriagebi,
							"kingaku" => $Uriagetotalmasterkobetu,
						);
					}
				}
			}
		}

		$this->set('arrSeikyuus', $arrSeikyuus);

		$this->set('totalkingaku', $totalkingaku);
	}

	public function nyuukinminyuukinform()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$year = date('Y');
		$this->set('year', $year);
		for ($n = 2010; $n <= $year; $n++) {

			$arrYear[$n] = $n;
		}
		$this->set('arrYear', $arrYear);


		for ($n = 1; $n <= 12; $n++) {

			$arrMonth[$n] = $n;
		}
		$this->set('arrMonth', $arrMonth);
	}

	public function nyuukinminyuukinitiran()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$data = $this->request->getData();

		if ($data['date_sta_m'] == 12) {
			$date_fin_m = 1;
			$date_fin_y = $data['date_sta_y'] + 1;
		} else {
			$date_fin_m = $data['date_sta_m'] + 1;
			$date_fin_y = $data['date_sta_y'];
		}

		$date_sta = $data['date_sta_y'] . "-" . $data['date_sta_m'] . "-1";
		$date_fin = $date_fin_y . "-" . $date_fin_m . "-1";
		$this->set('date_y', $data['date_sta_y']);
		$this->set('date_m', $data['date_sta_m']);

		$date_sta = strtotime($date_sta);
		$date_fin = strtotime($date_fin);

		$Zandakas = $this->Zandakas->find()
			->where(['koushinbi >=' => $date_sta, 'koushinbi <' => $date_fin, 'zandaka >' => 0, 'delete_flag' => 0])->order(["koushinbi" => "ASC"])->toArray();
		$this->set('Zandakas', $Zandakas);

		$count = count($Zandakas);

		$totalkingaku = 0;
		for ($k = 0; $k < $count; $k++) {
			$totalkingaku = $totalkingaku + $Zandakas[$k]->zandaka;
		}
		$this->set('totalkingaku', $totalkingaku);
	}

	public function seikyuusyuuseiview()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$data = $this->request->getData();

		$data = array_keys($data, '詳細');

		$dataarr = explode("_", $data[0]);

		$customerId = $dataarr[0];
		$date_sta = $dataarr[1];
		$date_fin = $dataarr[2];
		$this->set('customerId', $customerId);
		$this->set('date_sta', $date_sta);
		$this->set('date_fin', $date_fin);

		$Customer = $this->Customers->find('all', ['conditions' => ['id' => $customerId]])->toArray();
		$name = $Customer[0]->name;
		$this->set('name', $name);

		$dataymd = explode("-", $date_sta);
		$month = $dataymd[1];
		$this->set('month', $month);

		$Seikyuus = $this->Seikyuus->find()
			->where(['date_seikyuu >=' => $date_sta, 'date_seikyuu <' => $date_fin, 'customerId' => $customerId, 'delete_flag' => 0])
			->order(["date_seikyuu" => "ASC"])->toArray();

		$Uriagetotalmaster = 0;
		$Uriagetotalkurikosi = 0;
		$arrSeikyuus = array();
		$arrCustomerId = array();

		for ($h = 0; $h < count($Seikyuus); $h++) {

			$Uriagemasters = $this->Uriagemasters->find()->where(['seikyuuId' => $Seikyuus[$h]["id"], 'delete_flag' => 0])->toArray();

			if (isset($Uriagemasters[0])) {

				for ($j = 0; $j < count($Uriagemasters); $j++) {

					$Uriagetotalmasterkobetu = 0;

					$uriagebi = $Uriagemasters[$j]->uriagebi->format('Y-m-d');

					$Uriagesyousais = $this->Uriagesyousais->find()
						->where(['uriagemasterId' => $Uriagemasters[$j]->id, 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();

					for ($i = 0; $i < count($Uriagesyousais); $i++) {

						if (!empty($Uriagesyousais[$i]->price)) {

							$Uriagetotalmasterkobetu = $Uriagetotalmasterkobetu + $Uriagesyousais[$i]->price;
							$Uriagetotalmaster = $Uriagetotalmaster + $Uriagesyousais[$i]->price;
						}

						if ($i == count($Uriagesyousais) - 1) {

							$arrSeikyuus[] = array(
								"tax_include_flag" => $Uriagemasters[$j]->tax_include_flag,
								"denpyou_num" => $Uriagemasters[$j]->denpyou_num,
								"customer" => $Uriagemasters[$j]->customer,
								"customerId" => $Seikyuus[$h]->customerId,
								"uriagebi" => $uriagebi,
								"seikyuubi" => $Seikyuus[$h]->date_seikyuu->format('Y-m-d'),
								"kingaku" => $Uriagetotalmasterkobetu,
								"Id" => "master_" . $Uriagemasters[$j]->id,
							);
						}
					}
				}
			}
		}

		$this->set('arrSeikyuus', $arrSeikyuus);
	}

	public function seikyuusyuuseidelete()
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);

		$data = $this->request->getData();

		$customerId = $data["customerId"];
		$date_sta = $data["date_sta"];
		$date_fin = $data["date_fin"];
		$name = $data["name"];
		$month = $data["month"];
		$this->set('customerId', $customerId);
		$this->set('date_sta', $date_sta);
		$this->set('date_fin', $date_fin);
		$this->set('name', $name);
		$this->set('month', $month);

		$Seikyuus = $this->Seikyuus->find()
			->where(['date_seikyuu >=' => $date_sta, 'date_seikyuu <' => $date_fin, 'customerId' => $customerId, 'delete_flag' => 0])
			->order(["date_seikyuu" => "ASC"])->toArray();

		$Uriagetotalmaster = 0;
		$Uriagetotalkurikosi = 0;
		$arrSeikyuus = array();
		$arrCustomerId = array();

		for ($h = 0; $h < count($Seikyuus); $h++) {

			$Uriagemasters = $this->Uriagemasters->find()->where(['seikyuuId' => $Seikyuus[$h]["id"], 'delete_flag' => 0])->toArray();

			if (isset($Uriagemasters[0])) {

				for ($j = 0; $j < count($Uriagemasters); $j++) {

					$Uriagetotalmasterkobetu = 0;

					$uriagebi = $Uriagemasters[$j]->uriagebi->format('Y-m-d');

					$Uriagesyousais = $this->Uriagesyousais->find()
						->where(['uriagemasterId' => $Uriagemasters[$j]->id, 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();

					for ($i = 0; $i < count($Uriagesyousais); $i++) {

						if (!empty($Uriagesyousais[$i]->price)) {

							$Uriagetotalmasterkobetu = $Uriagetotalmasterkobetu + $Uriagesyousais[$i]->price;
							$Uriagetotalmaster = $Uriagetotalmaster + $Uriagesyousais[$i]->price;
						}

						if ($i == count($Uriagesyousais) - 1) {

							$arrSeikyuus[] = array(
								"tax_include_flag" => $Uriagemasters[$j]->tax_include_flag,
								"denpyou_num" => $Uriagemasters[$j]->denpyou_num,
								"customer" => $Uriagemasters[$j]->customer,
								"customerId" => $Seikyuus[$h]->customerId,
								"uriagebi" => $uriagebi,
								"seikyuubi" => $Seikyuus[$h]->date_seikyuu->format('Y-m-d'),
								"kingaku" => $Uriagetotalmasterkobetu,
								"Id" => "master_" . $Uriagemasters[$j]->id,
							);
						}
					}
				}

				if ($Seikyuus[$h]->kurikosi > 0) {

					$arrSeikyuus[] = array(
						"tax_include_flag" => $Uriagemasters[$j]->tax_include_flag,
						"denpyou_num" => "（繰越請求）",
						"customer" => $Uriagemasters[0]->customer,
						"customerId" => $Seikyuus[$h]->customerId,
						"uriagebi" => "-",
						"seikyuubi" => $Seikyuus[$h]->date_seikyuu->format('Y-m-d'),
						"kingaku" => $Seikyuus[$h]->kurikosi,
						"Id" => "kurikosi_" . $Seikyuus[$h]->id,
					);

					$Uriagetotalkurikosi = $Uriagetotalkurikosi + $Seikyuus[$h]->kurikosi;
				}
			} else {

				if ($Seikyuus[$h]->total_price > 0) {

					$Customers = $this->Customers->find('all', ['conditions' => ['id' => $Seikyuus[$h]->customerId]])->toArray();
					$customer = $Customers[0]["name"];

					$arrSeikyuus[] = array(
						"tax_include_flag" => $Uriagemasters[$j]->tax_include_flag,
						"denpyou_num" => "（合計表のみ出力）",
						"customer" => $customer,
						"customerId" => $Seikyuus[$h]->customerId,
						"uriagebi" => "-",
						"seikyuubi" => $Seikyuus[$h]->date_seikyuu->format('Y-m-d'),
						"kingaku" => $Seikyuus[$h]->total_price,
						"Id" => "kurikosi_" . $Seikyuus[$h]->id,
					);

					$Uriagetotalkurikosi = $Uriagetotalkurikosi + $Seikyuus[$h]->kurikosi;
				}
			}
		}

		$this->set('arrSeikyuus', $arrSeikyuus);

		$connection = ConnectionManager::get('default'); //トランザクション1
		// トランザクション開始2
		$connection->begin(); //トランザクション3
		try { //トランザクション4

			for ($h = 0; $h < count($Seikyuus); $h++) {

				$this->Seikyuus->updateAll(['delete_flag' => 1], ['id'  => $Seikyuus[$h]["id"]]);

				$Uriagemasters = $this->Uriagemasters->find()->where(['seikyuuId' => $Seikyuus[$h]["id"], 'delete_flag' => 0])->toArray();

				if (isset($Uriagemasters[0])) {

					for ($j = 0; $j < count($Uriagemasters); $j++) {

						$this->Uriagemasters->updateAll(['seikyuuId' => 0, 'updated_at' => date('Y-m-d H:i:s', strtotime('+9hour'))], ['id'  => $Uriagemasters[$j]["id"]]);
					}
				}
			}

			$connection->commit(); // コミット5

		} catch (Exception $e) { //トランザクション7
			//ロールバック8
			$connection->rollback(); //トランザクション9
		} //トランザクション10

	}

	public function uriagekensakumenu() //売上照会
	{
		$nyuukins = $this->Nyuukins->newEntity();
		$this->set('nyuukins', $nyuukins);
	}

	public function uriagekensakucustomer() //売上照会
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0']])->order(['furigana' => 'ASC']);
		$arrCustomer = array();
		foreach ($arrCustomers as $value) {
			$furigana = $value->furigana;
			$furigana = mb_substr($furigana, 0, 1);;
			$arrCustomer[] = array($value->id => $furigana . " - " . $value->name . ' ' . $value->siten);
		}
		$this->set('arrCustomer', $arrCustomer);

		$autoCustomers = $this->Customers->find()
			->where(['delete_flag' => 0])->toArray();
		$arrCustomer_list = array();
		for ($j = 0; $j < count($autoCustomers); $j++) {

			if (strlen($autoCustomers[$j]["siten"]) > 0) {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"] . ":" . $autoCustomers[$j]["siten"]);
			} else {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"]);
			}
		}
		$arrCustomer_list = array_unique($arrCustomer_list);
		$arrCustomer_list = array_values($arrCustomer_list);
		$this->set('arrCustomer_list', $arrCustomer_list);
	}

	public function uriagekensakucustomerfurigana() //売上照会
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$Data = $this->request->query('s');
		$data = $Data['data'];

		$furigana = $data["nyuryokufurigana"];

		$arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0', 'furigana like' => '%' . $furigana . '%']])->order(['furigana' => 'ASC']);
		$arrCustomer = array();
		foreach ($arrCustomers as $value) {
			$arrCustomer[] = array($value->id => $value->name . ' ' . $value->siten);
		}
		$this->set('arrCustomer', $arrCustomer);
	}

	public function uriagekensakucustomerview() //売上照会
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);
		$data = $this->request->getData();

		if (!empty($data["nyuryokufurigana"])) {

			return $this->redirect([
				'action' => 'uriagekensakucustomerfurigana',
				's' => ['data' => $data]
			]);
		}

		if (!empty($data["name1"])) {
			$id = $data["name1"];
			$Customer = $this->Customers->find('all', ['conditions' => ['id' => $id]])->toArray();

			$Customer_name = $Customer[0]["name"];
		} elseif (!empty($data["name2"])) {

			$arrname2 = explode(':', $data["name2"]);
			$name2 = $arrname2[0];
			$Customer_name = $name2;

			if (isset($arrname2[1])) {
				$Customer = $this->Customers->find('all', ['conditions' => ['name' => $name2, 'siten' => $arrname2[1]]])->toArray();
			} else {
				$Customer = $this->Customers->find('all', ['conditions' => ['name' => $name2]])->toArray();
			}

			$id = $Customer[0]["id"];
		} else {
			$name = "";
		}

		$this->set('Customer_name', $Customer_name);

		//新
		$Uriagemasters = $this->Uriagemasters->find()
			->where(['customerId' => $id, 'delete_flag' => 0])->order(["uriagebi" => "ASC"])->toArray();

		$count = count($Uriagemasters);
		$Uriagetotalhyouji = 0;
		$arrUriages = array();

		for ($j = 0; $j < $count; $j++) {

			$Uriagesyousais = $this->Uriagesyousais->find()
				->where(['uriagemasterId' => $Uriagemasters[$j]->id, 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();
			$this->set("Uriagesyousais" . $j, $Uriagesyousais);

			$Uriagemasters[$j]["delete_flag"] = $Uriagesyousais[0]["pro"];

			$countsyousai = count($Uriagesyousais);

			for ($i = 0; $i < $countsyousai; $i++) {

				if (!empty($Uriagesyousais[$i]->price)) {

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

		$this->set('Uriagemasters', $Uriagemasters);

		//				 $Uriagetotalhyouji = $Uriagetotalhyouji * 1.1;
		$this->set('Uriagetotalhyouji', $Uriagetotalhyouji);

		if (!isset($_SESSION)) {
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function uriagekakocustomer() //売上照会
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0']])->order(['furigana' => 'ASC']);
		$arrCustomer = array();
		foreach ($arrCustomers as $value) {
			$furigana = $value->furigana;
			$furigana = mb_substr($furigana, 0, 1);;
			$arrCustomer[] = array($value->id => $furigana . " - " . $value->name . ' ' . $value->siten);
		}
		$this->set('arrCustomer', $arrCustomer);

		$autoCustomers = $this->Customers->find()
			->where(['delete_flag' => 0])->toArray();
		$arrCustomer_list = array();
		for ($j = 0; $j < count($autoCustomers); $j++) {

			if (strlen($autoCustomers[$j]["siten"]) > 0) {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"] . ":" . $autoCustomers[$j]["siten"]);
			} else {
				array_push($arrCustomer_list, $autoCustomers[$j]["name"]);
			}
		}
		$arrCustomer_list = array_unique($arrCustomer_list);
		$arrCustomer_list = array_values($arrCustomer_list);
		$this->set('arrCustomer_list', $arrCustomer_list);
	}

	public function uriagekakocustomerrfurigana() //売上照会
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

		$Data = $this->request->query('s');
		$data = $Data['data'];

		$furigana = $data["nyuryokufurigana"];

		$arrCustomers = $this->Customers->find('all', ['conditions' => ['delete_flag' => '0', 'furigana like' => '%' . $furigana . '%']])->order(['furigana' => 'ASC']);
		$arrCustomer = array();
		foreach ($arrCustomers as $value) {
			$arrCustomer[] = array($value->id => $value->name . ' ' . $value->siten);
		}
		$this->set('arrCustomer', $arrCustomer);
	}

	public function uriagekakocustomerview() //売上照会
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);
		$data = $this->request->getData();

		if (!empty($data["nyuryokufurigana"])) {

			return $this->redirect([
				'action' => 'uriagekakocustomerrfurigana',
				's' => ['data' => $data]
			]);
		}

		if (!empty($data["name1"])) {
			$id = $data["name1"];
		} elseif (!empty($data["name2"])) {

			$arrname2 = explode(':', $data["name2"]);
			$name2 = $arrname2[0];

			if (isset($arrname2[1])) {
				$Customer = $this->Customers->find('all', ['conditions' => ['name' => $name2, 'siten' => $arrname2[1]]])->toArray();
			} else {
				$Customer = $this->Customers->find('all', ['conditions' => ['name' => $name2]])->toArray();
			}

			$id = $Customer[0]["id"];
		} else {
			$name = "";
		}
		//新
		$Uriagemasters = $this->Uriagemasters->find()
			->where(['customerId' => $id, 'delete_flag' => 0])->order(["uriagebi" => "ASC"])->toArray();

		$count = count($Uriagemasters);
		$Uriagetotalhyouji = 0;
		$arrUriages = array();

		for ($j = 0; $j < $count; $j++) {

			$Uriagesyousais = $this->Uriagesyousais->find()
				->where(['uriagemasterId' => $Uriagemasters[$j]->id, 'delete_flag' => 0])->order(["num" => "ASC"])->toArray();

			$countsyousai = count($Uriagesyousais);

			for ($i = 0; $i < $countsyousai; $i++) {

				if (!empty($Uriagesyousais[$i]->price)) {

					$arrUriages[] = array(
						"denpyou_num" => $Uriagemasters[$j]->denpyou_num,
						"syutsuryokubi" => $Uriagemasters[$j]->uriagebi->format('Y-m-d'),
						"genba" => $Uriagesyousais[0]->pro,
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
		//			$Uriagetotalhyouji = $Uriagetotalhyouji * 1.1;
		$this->set('Uriagetotalhyouji', $Uriagetotalhyouji);

		$this->set('arrUriages', $arrUriages);

		$Uriages = $this->Uriagemasters->find()
			->where(['customerId' => $id, 'delete_flag' => 0])->order(["uriagebi" => "ASC"])->toArray();
		$this->set('Uriages', $Uriages);

		$Customers = $this->Customers->find()->where(['id' => $id])->order(["furigana" => "ASC"])->toArray();
		$this->set('customer', $Customers[0]->name . " " . $Customers[0]->siten);
	}

	public function test() //エクセルテスト
	{
		$uriages = $this->Uriages->newEntity();
		$this->set('uriages', $uriages);

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
	}
}
