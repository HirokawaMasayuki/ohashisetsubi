<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class NyuukinsController extends AppController
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

	public function nyuukinformdate()
	{
		$Nyuukins = $this->Nyuukins->newEntity();
		$this->set('Nyuukins',$Nyuukins);

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

		if(!isset($_SESSION)){
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');
	}

	public function nyuukinformcustomer()
	{
		$Nyuukins = $this->Nyuukins->newEntity();
		$this->set('Nyuukins',$Nyuukins);

		$Data = $this->request->query('s');
		if(isset($Data["date_sta"])){

			$date_sta = $Data["date_sta"];
			$date_fin = $Data["date_fin"];

		}else{
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
		}

		$this->set('date_sta',$date_sta);
		$this->set('date_fin',$date_fin);

		$Seikyuus = $this->Seikyuus->find()
		->where(['date_seikyuu >=' => $date_sta, 'date_seikyuu <' => $date_fin, 'delete_flag' => 0])
		->order(["furigana"=>"ASC"])->toArray();

		$arrCustomerId = array();
		for($h=0; $h<count($Seikyuus); $h++){

			$arrCustomerId[] = $Seikyuus[$h]->customerId;

		}

		$arrCustomerId = array_unique($arrCustomerId);
		$arrCustomerId = array_values($arrCustomerId);

		$arrCustomernames = array();
		for($h=0; $h<count($arrCustomerId); $h++){

			$Customers = $this->Customers->find('all', ['conditions' => ['id' => $arrCustomerId[$h]]])->toArray();
			$arrCustomernames[$arrCustomerId[$h]] = $Customers[0]["name"];

		}

		$this->set('arrCustomernames',$arrCustomernames);

		$autoCustomers = $this->Customers->find()
		->where(['delete_flag' => 0])->toArray();
		$arrCustomer_list = array();
		for($j=0; $j<count($autoCustomers); $j++){

			if(strlen($autoCustomers[$j]["siten"]) > 0){
				array_push($arrCustomer_list,$autoCustomers[$j]["name"].":".$autoCustomers[$j]["siten"]);
			}else{
				array_push($arrCustomer_list,$autoCustomers[$j]["name"]);
			}

		}
		$arrCustomer_list = array_unique($arrCustomer_list);
		$arrCustomer_list = array_values($arrCustomer_list);
		$this->set('arrCustomer_list', $arrCustomer_list);

		if(!isset($_SESSION)){
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');

	}

	public function nyuukinform()
	{
		$Nyuukins = $this->Nyuukins->newEntity();
		$this->set('Nyuukins',$Nyuukins);

		$data = $this->request->getData();

		$Data = $this->request->query('s');
		if(isset($Data["customer_id"])){
			$data = $Data;
		}

		$date_sta = $data["date_sta"];
		$date_fin = $data["date_fin"];
		$this->set('date_sta',$date_sta);
		$this->set('date_fin',$date_fin);

		if(strlen($data["customer_id"]) > 0){
			$customer_id = $data["customer_id"];
			if(isset($data["check"])){
				$check = $data["check"];
				$this->set('check',$check);
			}else{
				$check = 0;
				$this->set('check',$check);
			}
		}else{
			if(isset($data["check"])){
				$check = $data["check"];
				$this->set('check',$check);
			}else{
				$check = 1;
				$this->set('check',$check);
			}
			$Customer = $this->Customers->find('all', ['conditions' => ['name' => $data["customer_name"]]])->toArray();
			if(isset($Customer[0])){
				$customer_id = $Customer[0]["id"];
			}else{
				return $this->redirect(['action' => 'nyuukinformdate']);
			}
		}

		if(isset($data["kensaku"])){

			$Seikyuus = $this->Seikyuus->find('all', ['conditions' =>
			['date_seikyuu >=' => $date_sta, 'date_seikyuu <' => $date_fin, 'delete_flag' => '0', 'customerId' => $customer_id]])->toArray();
			if(isset($Seikyuus[0])){

				$NyuukinData = array();
				for($k=0; $k<count($Seikyuus); $k++){
					$seikyuuId = $Seikyuus[$k]->id;
					$NyuukinData1 = $this->Nyuukins->find('all', ['conditions' => ['seikyuuId' => $seikyuuId, 'delete_flag' => '0']])->toArray();
					$NyuukinData = $NyuukinData + $NyuukinData1;
				}

			}else{
				$NyuukinData = $this->Nyuukins->find('all', ['conditions' => ['datenyuukin >=' => $date_sta, 'datenyuukin <' => $date_fin, 'customerId' => $customer_id, 'delete_flag' => '0']])->toArray();
			}

			if(isset($NyuukinData[0])){
				$tuika = count($NyuukinData);

				for($k=0; $k<$tuika; $k++){

					$n = $k + 1;

					${"datenyuukin".$n} = $NyuukinData[$k]->datenyuukin->format('Y-m-d');
					$this->set('datenyuukin'.$n,${"datenyuukin".$n});
					${"nyuukinngaku".$n} = $NyuukinData[$k]["nyuukinngaku"];
					$this->set('nyuukinngaku'.$n,${"nyuukinngaku".$n});
					${"syubetu".$n} = $NyuukinData[$k]["syubetu"];
					$this->set('syubetu'.$n,${"syubetu".$n});
					${"bik".$n} = $NyuukinData[$k]["bik"];
					$this->set('bik'.$n,${"bik".$n});

				}

			}else{
				$tuika = 1;

				${"datenyuukin".$tuika} = date('Y-m-d', strtotime('+9hour'));
				$this->set('datenyuukin'.$tuika,${"datenyuukin".$tuika});
				${"nyuukinngaku".$tuika} = "";
				$this->set('nyuukinngaku'.$tuika,${"nyuukinngaku".$tuika});
				${"syubetu".$tuika} = "";
				$this->set('syubetu'.$tuika,${"syubetu".$tuika});
				${"bik".$tuika} = "";
				$this->set('bik'.$tuika,${"bik".$tuika});

			}

			if($check == 1){
				$date_seikyuu_input = date('Y-m-d', strtotime('+9hour'));
				$this->set('date_seikyuu_input',$date_seikyuu_input);
				$totalseikyuu_input = "";
				$this->set('totalseikyuu_input',$totalseikyuu_input);
			}

			$this->set('tuika',$tuika);

		}
		if(isset($data["tuika"])){

			$tuika = $data["num"] + 1;
			$this->set('tuika',$tuika);

			if($check == 1){
				$date_seikyuu_input = $data["date_seikyuu_input"];
				$this->set('date_seikyuu_input',$date_seikyuu_input);
				$totalseikyuu_input = $data["totalseikyuu_input"];
				$this->set('totalseikyuu_input',$totalseikyuu_input);
			}

			for($n=1; $n<$tuika; $n++){

				${"datenyuukin".$n} = $data["datenyuukin".$n]["year"]."-".$data["datenyuukin".$n]["month"]."-".$data["datenyuukin".$n]["day"];
				$this->set('datenyuukin'.$n,${"datenyuukin".$n});
				${"nyuukinngaku".$n} = $data["nyuukinngaku".$n];
				$this->set('nyuukinngaku'.$n,${"nyuukinngaku".$n});
				${"syubetu".$n} = $data["syubetu".$n];
				$this->set('syubetu'.$n,${"syubetu".$n});
				${"bik".$n} = $data["bik".$n];
				$this->set('bik'.$n,${"bik".$n});

			}

			${"datenyuukin".$tuika} = date('Y-m-d', strtotime('+9hour'));
			$this->set('datenyuukin'.$tuika,${"datenyuukin".$tuika});
			${"nyuukinngaku".$tuika} = "";
			$this->set('nyuukinngaku'.$tuika,${"nyuukinngaku".$tuika});
			${"syubetu".$tuika} = "";
			$this->set('syubetu'.$tuika,${"syubetu".$tuika});
			${"bik".$tuika} = "";
			$this->set('bik'.$tuika,${"bik".$tuika});

		}

		if(isset($data["sakujo"])){

			$tuika = $data["num"] - 1;
			$this->set('tuika',$tuika);

			if($check == 1){
				$date_seikyuu_input = $data["date_seikyuu_input"];
				$this->set('date_seikyuu_input',$date_seikyuu_input);
				$totalseikyuu_input = $data["totalseikyuu_input"];
				$this->set('totalseikyuu_input',$totalseikyuu_input);
			}

			for($n=1; $n<=$tuika; $n++){

				${"datenyuukin".$n} = $data["datenyuukin".$n]["year"]."-".$data["datenyuukin".$n]["month"]."-".$data["datenyuukin".$n]["day"];
				$this->set('datenyuukin'.$n,${"datenyuukin".$n});
				${"nyuukinngaku".$n} = $data["nyuukinngaku".$n];
				$this->set('nyuukinngaku'.$n,${"nyuukinngaku".$n});
				${"syubetu".$n} = $data["syubetu".$n];
				$this->set('syubetu'.$n,${"syubetu".$n});
				${"bik".$n} = $data["bik".$n];
				$this->set('bik'.$n,${"bik".$n});

			}

		}

		if(isset($data["confirm"])){

			if(!isset($_SESSION)){
				session_start();
			}
			$_SESSION['nyuukinform'] = $data;
			return $this->redirect(['action' => 'nyuukinconfirm']);

		}

		$Customer = $this->Customers->find('all', ['conditions' => ['id' => $customer_id]])->toArray();
		$name = $Customer[0]->name;
		$siten = $Customer[0]->siten;
		$namehyouji = $name." ".$siten;
		$this->set('namehyouji',$namehyouji);
		$this->set('id',$customer_id);
		$nyuukinyotei = $Customer[0]->nyuukinbi;
		$this->set('nyuukinyotei',$nyuukinyotei);

		$Seikyuus = $this->Seikyuus->find('all', ['conditions' =>
		['date_seikyuu >=' => $date_sta, 'date_seikyuu <' => $date_fin, 'delete_flag' => '0', 'customerId' => $data["customer_id"]]])->toArray();
		if(isset($Seikyuus[0])){
			$date_seikyuu = $Seikyuus[count($Seikyuus) - 1]->date_seikyuu->format('Y年m月d日');
			$this->set('date_seikyuu',$date_seikyuu);
			$touroku_date_seikyuu = $Seikyuus[count($Seikyuus) - 1]->date_seikyuu->format('Y-m-d');
			$this->set('touroku_date_seikyuu',$touroku_date_seikyuu);

			$totalseikyuu = 0;
			for($h=0; $h<count($Seikyuus); $h++){
				$totalseikyuu = $totalseikyuu + $Seikyuus[$h]->total_price - $Seikyuus[$h]->kurikosi;
			}
			$this->set('totalseikyuu',$totalseikyuu);
		}

		$arrSyubetu = [
			'振込' => '振込',
			'相殺' => '相殺',
			'現金' => '現金',
			'小切手' => '小切手',
			'手形' => '手形',
			'調整' => '調整'
		];
		$this->set('arrSyubetu',$arrSyubetu);

		if(!isset($_SESSION)){
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');

	}

	public function nyuukinconfirm()
	{
		$Nyuukins = $this->Nyuukins->newEntity();
		$this->set('Nyuukins',$Nyuukins);

		session_start();
		$session = $this->request->session();

		$data = $_SESSION['nyuukinform'];

		$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["customer_id"]]])->toArray();
		$name = $Customer[0]->name;
		$siten = $Customer[0]->siten;
		$namehyouji = $name." ".$siten;
		$this->set('namehyouji',$namehyouji);
		$this->set('id',$data["customer_id"]);
		$nyuukinyotei = $Customer[0]->nyuukinbi;
		$this->set('nyuukinyotei',$nyuukinyotei);

		$date_sta = $data["date_sta"];
		$date_fin = $data["date_fin"];
		$this->set('date_sta',$date_sta);
		$this->set('date_fin',$date_fin);

		if($data["check"] == 0){

			$Seikyuus = $this->Seikyuus->find('all', ['conditions' =>
			['date_seikyuu >=' => $date_sta, 'date_seikyuu <' => $date_fin, 'delete_flag' => '0', 'customerId' => $data["customer_id"]]])->toArray();
			if(isset($Seikyuus[0])){
				$date_seikyuu = $Seikyuus[count($Seikyuus) - 1]->date_seikyuu->format('Y年m月d日');
				$this->set('date_seikyuu',$date_seikyuu);
				$touroku_date_seikyuu = $Seikyuus[count($Seikyuus) - 1]->date_seikyuu->format('Y-m-d');
				$this->set('touroku_date_seikyuu',$touroku_date_seikyuu);

				$totalseikyuu = 0;
				for($h=0; $h<count($Seikyuus); $h++){
					$totalseikyuu = $totalseikyuu + $Seikyuus[$h]->total_price - $Seikyuus[$h]->kurikosi;
				}
				$this->set('totalseikyuu',$totalseikyuu);
			}

		}else{
			$date_seikyuu = $data["date_seikyuu_input"]["year"]."-".$data["date_seikyuu_input"]["month"]."-".$data["date_seikyuu_input"]["day"];
			$this->set('date_seikyuu',$date_seikyuu);
			$this->set('totalseikyuu',$data["totalseikyuu_input"]);
		}

		$tuika = $data["num"];
		$this->set('tuika',$tuika);

		for($h=1; $h<=$tuika; $h++){

			${"syubetu".$h} = $data["syubetu".$h];
			$this->set('syubetu'.$h,${"syubetu".$h});
			${"nyuukinngaku".$h} = $data["nyuukinngaku".$h];
			$this->set('nyuukinngaku'.$h,${"nyuukinngaku".$h});
			${"bik".$h} = $data["bik".$h];
			$this->set('bik'.$h,${"bik".$h});

			${"datenyuukin".$h} = $data["datenyuukin".$h]["year"]."-".$data["datenyuukin".$h]["month"]."-".$data["datenyuukin".$h]["day"];
			$this->set('datenyuukin'.$h,${"datenyuukin".$h});

		}

	}

	public function nyuukindo()
	{
		$Nyuukins = $this->Nyuukins->newEntity();
		$this->set('Nyuukins',$Nyuukins);

		$data = $this->request->getData();

		if(isset($data["onaji"])){

			return $this->redirect(['action' => 'nyuukinformcustomer',
			's' => ['date_sta' => $data["date_sta"], 'date_fin' => $data["date_fin"]]]);

		}elseif(isset($data["tigau"])){

			return $this->redirect(['action' => 'nyuukinformdate']);

		}else{

			session_start();
			$session = $this->request->session();

			$data = $_SESSION['nyuukinform'];

			$Customer = $this->Customers->find('all', ['conditions' => ['id' => $data["customer_id"]]])->toArray();
			$name = $Customer[0]->name;
			$furigana = $Customer[0]->furigana;
			$siten = $Customer[0]->siten;
			$namehyouji = $name." ".$siten;
			$this->set('namehyouji',$namehyouji);
			$this->set('id',$data["customer_id"]);
			$nyuukinyotei = $Customer[0]->nyuukinbi;
			$this->set('nyuukinyotei',$nyuukinyotei);

			$date_sta = $data["date_sta"];
			$date_fin = $data["date_fin"];
			$this->set('date_sta',$date_sta);
			$this->set('date_fin',$date_fin);

			if($data["check"] == 0){

				$Seikyuus = $this->Seikyuus->find('all', ['conditions' =>
				['date_seikyuu >=' => $date_sta, 'date_seikyuu <' => $date_fin, 'delete_flag' => '0', 'customerId' => $data["customer_id"]]])->toArray();
				if(isset($Seikyuus[0])){
					$date_seikyuu = $Seikyuus[count($Seikyuus) - 1]->date_seikyuu->format('Y年m月d日');
					$this->set('date_seikyuu',$date_seikyuu);
					$touroku_date_seikyuu = $Seikyuus[count($Seikyuus) - 1]->date_seikyuu->format('Y-m-d');
					$this->set('touroku_date_seikyuu',$touroku_date_seikyuu);

					$totalseikyuu = 0;
					for($h=0; $h<count($Seikyuus); $h++){
						$totalseikyuu = $totalseikyuu + $Seikyuus[$h]->total_price - $Seikyuus[$h]->kurikosi;
					}
					$this->set('totalseikyuu',$totalseikyuu);
					$seikyuuId = $Seikyuus[count($Seikyuus) - 1]->id;
					$date_seikyuu_touroku = $Seikyuus[count($Seikyuus) - 1]->date_seikyuu->format('Y-m-d');
				}

			}else{

				$date_seikyuu = $data["date_seikyuu_input"]["year"]."-".$data["date_seikyuu_input"]["month"]."-".$data["date_seikyuu_input"]["day"];
				$this->set('date_seikyuu',$date_seikyuu);
				$this->set('totalseikyuu',$data["totalseikyuu_input"]);
				$totalseikyuu = $data["totalseikyuu_input"];
				$date_seikyuu_touroku = $date_seikyuu;
				$seikyuuId = 3;

			}

			$tuika = $data["num"];
			$this->set('tuika',$tuika);

			$arrayNyukintouroku = array();

			for($h=1; $h<=$tuika; $h++){

				${"syubetu".$h} = $data["syubetu".$h];
				$this->set('syubetu'.$h,${"syubetu".$h});
				${"nyuukinngaku".$h} = $data["nyuukinngaku".$h];
				$this->set('nyuukinngaku'.$h,${"nyuukinngaku".$h});
				${"bik".$h} = $data["bik".$h];
				$this->set('bik'.$h,${"bik".$h});

				${"datenyuukin".$h} = $data["datenyuukin".$h]["year"]."-".$data["datenyuukin".$h]["month"]."-".$data["datenyuukin".$h]["day"];
				$this->set('datenyuukin'.$h,${"datenyuukin".$h});

				$arrayNyukintouroku[] = [
					"customerId" => $data["customer_id"],
					"customer" => $name,
					"furigana" => $furigana,
					"dateseikyuu" => $date_seikyuu_touroku,
					"seikyuuId" => $seikyuuId,
					"seikyuugaku" => $totalseikyuu,
					"syubetu" => ${"syubetu".$h},
					"nyuukinngaku" => ${"nyuukinngaku".$h},
					"datenyuukin" => ${"datenyuukin".$h},
					"bik" => ${"bik".$h},
					'delete_flag' => 0,
					'created_at' => date('Y-m-d H:i:s', strtotime('+9hour')),
				];

			}

			$connection = ConnectionManager::get('default');//トランザクション1
			// トランザクション開始2
			$connection->begin();//トランザクション3
			try {//トランザクション4

				if($data["check"] == 0){
					$this->Nyuukins->updateAll(
						['delete_flag' => 1],
						['seikyuuId'  => $seikyuuId]
					);
				}else{

					$NyuukinDatas = $this->Nyuukins->find()
					->where(['seikyuuId' => $seikyuuId, 'customerId' => $data["customer_id"], 'delete_flag' => 0])->toArray();

					$countupdate = count($NyuukinDatas);
					for($h=0; $h<$countupdate; $h++){
						$this->Nyuukins->updateAll(
							['delete_flag' => 1],
							['id'  => $NyuukinDatas[$h]["id"]]
						);
					}

				}

				$Nyuukins = $this->Nyuukins->patchEntities($Nyuukins, $arrayNyukintouroku);
				if ($this->Nyuukins->saveMany($Nyuukins)) {

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

	}

	public function nyuukinzandaka()
	{
		$Nyuukins = $this->Nyuukins->newEntity();
		$this->set('Nyuukins',$Nyuukins);

		$data = $this->request->getData();

		$date_y = $data["date_sta_y"];
		$date_m = $data["date_sta_m"];
		$this->set('date_y',$date_y);
		$this->set('date_m',$date_m);

		$date_m_nezt = date("Y-m", strtotime("+1 month", strtotime($data["date_sta_y"]."-".$data["date_sta_m"])));

		$date_sta = $data["date_sta_y"]."-".$data["date_sta_m"]."-01";
		$date_fin = $date_m_nezt."-01";
		$this->set('date_sta',$date_sta);
		$this->set('date_fin',$date_fin);

		$Seikyuus = $this->Seikyuus->find()
		->where(['date_seikyuu >=' => $date_sta, 'date_seikyuu <=' => $date_fin, 'delete_flag' => 0])->order(["customerId"=>"ASC"])->toArray();

		$arrcustomerId = array();
		for($n=0; $n<count($Seikyuus); $n++){
			$arrcustomerId[]	= $Seikyuus[$n]["customerId"];
		}
		$arrcustomerId = array_unique($arrcustomerId);
		$arrcustomerId = array_values($arrcustomerId);

		$arrayNyukins = array();
		$totalzanndaka = 0;
		for($n=0; $n<count($arrcustomerId); $n++){

			$Seikyuus = $this->Seikyuus->find()
			->where(['date_seikyuu >=' => $date_sta, 'date_seikyuu <=' => $date_fin, 'customerId' => $arrcustomerId[$n], 'delete_flag' => 0, 'zandaka_flag' => 0])
			->toArray();

			$total_price = 0;
			for($m=0; $m<count($Seikyuus); $m++){
				$total_price = $total_price + round($Seikyuus[$m]["total_price"]) - round($Seikyuus[$m]["kurikosi"]);
			}

			$totalkingaku = 0;
			if(isset($Seikyuus[0])){

				$NyuukinDatas = $this->Nyuukins->find()
				->where(['seikyuuId' => $Seikyuus[count($Seikyuus) - 1]["id"], 'delete_flag' => 0])->order(["furigana"=>"ASC"])->toArray();

				$count = count($NyuukinDatas);
				for ($k=0; $k<$count; $k++){
					$totalkingaku = $totalkingaku + $NyuukinDatas[$k]->nyuukinngaku;
				}

			}

			$zanndaka = $total_price - $totalkingaku;

			$totalzanndaka = $totalzanndaka + $zanndaka;

			$Customer = $this->Customers->find('all', ['conditions' => ['id' => $arrcustomerId[$n]]])->toArray();

			if($total_price > 0 || $totalkingaku > 0){
				$arrayNyukins[]	= [
					"customerId" => $arrcustomerId[$n],
					"customer_name" => $Customer[0]["name"],
					"date_seikyuu" => $Seikyuus[count($Seikyuus) - 1]["date_seikyuu"]->format('Y-m-d'),
					"total_price" => $total_price,
					"nyuukinbi" => $Customer[0]["nyuukinbi"],
					"nyuukinngaku" => round($totalkingaku),
					"zanndaka" => round($zanndaka),
				];
			}

		}
		$this->set('totalzanndaka',$totalzanndaka);
		$this->set('arrayNyukins',$arrayNyukins);

		if(!isset($_SESSION)){
			session_start();
		}
		header('Expires:-1');
		header('Cache-Control:');
		header('Pragma:');

	}

	public function nyuukinzandakadel()
	{
		$Nyuukins = $this->Nyuukins->newEntity();
		$this->set('Nyuukins',$Nyuukins);

		$data = $this->request->getData();

		$dataarredit1 = array_keys($data, '修正');
		if(isset($dataarredit1[0])){
			$dataarredit2 = explode("_",$dataarredit1[0]);

			return $this->redirect(['action' => 'nyuukinform',
			's' => ['customer_id' => $dataarredit2[0], 'date_sta' => $dataarredit2[1],
			'date_fin' => $dataarredit2[2], 'kensaku' => 1]]);

		}

		$dataarr1 = array_keys($data, '非表示');
		$dataarr2 = explode("_",$dataarr1[0]);

		$customerId = $dataarr2[0];
		$this->set('customerId',$customerId);
		$date_sta = $dataarr2[1];
		$date_fin = $dataarr2[2];
		$this->set('date_sta',$date_sta);
		$this->set('date_fin',$date_fin);

		$arrayNyukins = array();
		$totalzanndaka = 0;

		$Seikyuus = $this->Seikyuus->find()
		->where(['date_seikyuu >=' => $date_sta, 'date_seikyuu <=' => $date_fin, 'customerId' => $customerId, 'delete_flag' => 0, 'zandaka_flag' => 0])
		->toArray();

		$total_price = 0;
		for($m=0; $m<count($Seikyuus); $m++){
			$total_price = $total_price + round($Seikyuus[$m]["total_price"]) - round($Seikyuus[$m]["kurikosi"]);
		}

		$NyuukinDatas = $this->Nyuukins->find()
		->where(['seikyuuId' => $Seikyuus[count($Seikyuus) - 1]["id"], 'delete_flag' => 0])->order(["furigana"=>"ASC"])->toArray();

		$count = count($NyuukinDatas);
		$totalkingaku = 0;
		for ($k=0; $k<$count; $k++){
			$totalkingaku = $totalkingaku + $NyuukinDatas[$k]->nyuukinngaku;
		}

		$zanndaka = $total_price - $totalkingaku;

		$totalzanndaka = $totalzanndaka + $zanndaka;

		$Customer = $this->Customers->find('all', ['conditions' => ['id' => $customerId]])->toArray();

		$arrayNyukin = [
			"customerId" => $customerId,
			"customer_name" => $Customer[0]["name"],
			"date_seikyuu" => $Seikyuus[count($Seikyuus) - 1]["date_seikyuu"]->format('Y-m-d'),
			"total_price" => $total_price,
			"nyuukinbi" => $Customer[0]["nyuukinbi"],
			"nyuukinngaku" => round($totalkingaku),
			"zanndaka" => round($zanndaka),
		];
		$this->set('arrayNyukin',$arrayNyukin);

	}

	public function nyuukinzandakadeldo()
	{
		$Nyuukins = $this->Nyuukins->newEntity();
		$this->set('Nyuukins',$Nyuukins);

		$data = $this->request->getData();

		$customerId = $data["customerId"];
		$this->set('customerId',$customerId);
		$date_sta = $data["date_sta"];
		$date_fin = $data["date_fin"];
		$this->set('date_sta',$date_sta);
		$this->set('date_fin',$date_fin);

		$date_sta_y = substr($date_sta, 0, 4);
		$date_sta_m = substr($date_sta, 5, 2);
		$this->set('date_sta_y',$date_sta_y);
		$this->set('date_sta_m',$date_sta_m);

		$arrayNyukins = array();
		$totalzanndaka = 0;

		$Seikyuus = $this->Seikyuus->find()
		->where(['date_seikyuu >=' => $date_sta, 'date_seikyuu <=' => $date_fin, 'customerId' => $customerId, 'delete_flag' => 0, 'zandaka_flag' => 0])
		->toArray();

		$total_price = 0;
		for($m=0; $m<count($Seikyuus); $m++){
			$total_price = $total_price + round($Seikyuus[$m]["total_price"]) - round($Seikyuus[$m]["kurikosi"]);
		}

		$NyuukinDatas = $this->Nyuukins->find()
		->where(['seikyuuId' => $Seikyuus[count($Seikyuus) - 1]["id"], 'delete_flag' => 0])->order(["furigana"=>"ASC"])->toArray();

		$count = count($NyuukinDatas);
		$totalkingaku = 0;
		for ($k=0; $k<$count; $k++){
			$totalkingaku = $totalkingaku + $NyuukinDatas[$k]->nyuukinngaku;
		}

		$zanndaka = $total_price - $totalkingaku;

		$totalzanndaka = $totalzanndaka + $zanndaka;

		$Customer = $this->Customers->find('all', ['conditions' => ['id' => $customerId]])->toArray();

		$arrayNyukin = [
			"customerId" => $customerId,
			"customer_name" => $Customer[0]["name"],
			"date_seikyuu" => $Seikyuus[count($Seikyuus) - 1]["date_seikyuu"]->format('Y-m-d'),
			"total_price" => $total_price,
			"nyuukinbi" => $Customer[0]["nyuukinbi"],
			"nyuukinngaku" => round($totalkingaku),
			"zanndaka" => round($zanndaka),
		];
		$this->set('arrayNyukin',$arrayNyukin);

		$connection = ConnectionManager::get('default');//トランザクション1
		// トランザクション開始2
		$connection->begin();//トランザクション3
		try {//トランザクション4

			$Seikyuus = $this->Seikyuus->find()
			->where(['date_seikyuu >=' => $date_sta, 'date_seikyuu <=' => $date_fin, 'customerId' => $customerId, 'delete_flag' => 0, 'zandaka_flag' => 0])
			->toArray();

			$countseikyuus = count($Seikyuus);
			for ($k=0; $k<$countseikyuus; $k++){

				$this->Seikyuus->updateAll(
					['zandaka_flag' => 1],
					['id'  => $Seikyuus[$k]["id"]]
				);

				if($k == $countseikyuus - 1){
					$mes = "※以下の残高データを非表示にしました。";
					$this->set('mes',$mes);
					$connection->commit();// コミット5

				}

			}

		} catch (Exception $e) {//トランザクション7
			//ロールバック8
			$connection->rollback();//トランザクション9
		}//トランザクション10

	}

}
