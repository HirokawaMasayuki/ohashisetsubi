<?php
$this->layout = '';

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

use Cake\ORM\TableRegistry;//独立したテーブルを扱う
$this->Nyuukins = TableRegistry::get('nyuukins');
$this->Customers = TableRegistry::get('customers');

?>
<table width="1300" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#E6FFFF">
  <tr style="background-color: #E6FFFF">
    <td>
        <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td bgcolor="#E6FFFF">
            </body>
            </td>
          </tr>
        </table>
        <br>
        <table style="margin-bottom:0px" width="800" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/top.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'index')));?></td>
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/shinki.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Shinkies','action'=>'index')));?></td>
        </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <table style="margin-bottom:0px" width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/nyuukinsyoukai.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <table style="margin-bottom:0px" width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/nyuukinngaku.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($nyuukins, ['url' => ['controller' => 'Nyuukins', 'action' => 'nyuukinzandakadel']]) ?>

        <br>
        <table align="center">
          <tr>
            <td align="center" width="200" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">期間</strong></td>
            <td align="center" width="200" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金額合計</strong></td>
          </tr>
          <tr>
            <td align="center" width="200"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($date_y."年".$date_m."月分") ?></div></td>
            <td align="center" width="200"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($totalkingaku." 円") ?></div></td>
          </tr>
        </table>

<br><br>
<table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
  <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: 0px;border-width: 1px">
        <thead>
            <tr border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
              <td width="200" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">取引先</strong></div></td>
              <td width="150" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">入金日</strong></div></td>
              <td width="120" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">入金額</strong></div></td>
              <td width="80" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">種別</strong></div></td>
              <td height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 12pt"></strong></div></td>
            </tr>
        </thead>
        <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
          <?php foreach ($Nyuukins as $Nyuukins): ?>
          <tr>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Nyuukins->customer) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Nyuukins->datenyuukin->format('Y-m-d')) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h(number_format($Nyuukins->nyuukinngaku)." 円") ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Nyuukins->syubetu) ?></font></td>
            <?php
            $date_sta = $Nyuukins->dateseikyuu->format('Y-m')."-1";
            $date_m_nezt = date("Y-m", strtotime("+1 month", strtotime($Nyuukins->dateseikyuu->format('Y-m'))));
    				$date_fin = $date_m_nezt."-01";

            echo "<td colspan='20' nowrap='nowrap'><div align='center'>";
            echo $this->Form->submit("修正" , ['action'=>'edit', 'name' => $Nyuukins->customerId."_".$date_sta."_".$date_fin]) ;
            echo "</div></td>";
            ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
    </table>
<br><br>
