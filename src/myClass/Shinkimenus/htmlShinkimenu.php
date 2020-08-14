<?php
namespace App\myClass\Shinkimenus;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

class htmlShinkimenu extends AppController
{

	public function Shinkimenus()
	{
        $html =
        "<tr style='border-style: none; background-color: #E6FFFF'>\n".
        "<td style='padding: 0.1rem 0.1rem;text-align : center'><a href='/Customers/index'>\n".
        "<img src='/img/menu/kokyaku.png' width=105 height=36>\n".
        "</a>\n".
				"<td style='padding: 0.1rem 0.1rem;text-align : center'><a href='/shinkies/materialsform'>\n".
				"<img src='/menu/shinki.png' width=105 height=36>\n".
				"</a>\n".
      "</tr>\n";

		return $html;
		$this->html = $html;
		$this->data = $shinkimenus;
	}

}

?>
