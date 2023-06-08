<?php
$this->layout = '';

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

use Cake\ORM\TableRegistry;//独立したテーブルを扱う
$this->Customers = TableRegistry::get('customers');

?>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#E6FFFF">
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

        <table style="margin-bottom:0px" width="750" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/seikyuuitiran.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <table style="margin-bottom:0px" width="750" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/miseikyuu.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($nyuukins, ['url' => ['action' => 'seikyuuform']]) ?>


        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">期間</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">未請求額合計</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($date_sta." ～ ".$date_fin) ?></div></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h(number_format($totalkingaku)." 円") ?></div></td>
          </tr>
        </table>

<br><br>
<table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
  <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: 0px;border-width: 1px">
        <thead>
            <tr border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
              <td width="300" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">取引先</strong></div></td>
              <td width="200" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">売上日</strong></div></td>
              <td width="200" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">未請求額</strong></div></td>
            </tr>
        </thead>
        <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
          <?php for ($i=0;$i<count($arrSeikyuus);$i++): ?>
          <tr>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($arrSeikyuus[$i]["customer"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($arrSeikyuus[$i]["uriagebi"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h(number_format($arrSeikyuus[$i]["kingaku"])." 円") ?></font></td>
            <?php
    //        echo "<td colspan='20' nowrap='nowrap'><div align='center'>";
    //        echo $this->Form->submit("請求処理へ" , ['action'=>'seikyuuform', 'name' => $arrSeikyuus[$i]["customerId"]]) ;
    //        echo "</div></td>";
            ?>
          </tr>
        <?php endfor;?>
        </tbody>
    </table>
<br><br>
