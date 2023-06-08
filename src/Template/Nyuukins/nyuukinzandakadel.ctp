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
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/zandaka.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($Nyuukins, ['url' => ['action' => 'nyuukinzandakadeldo']]) ?>
        <br>
        <legend align="center"><strong style="font-size: 12pt;color: black"><?= __("以下の残高データを非表示にします。よろしければ決定ボタンを押してください。") ?></strong></legend>
        <br>

        <br>
        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
      <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
        <tr>
          <td width="150" height="30"><div align="center"><strong style="font-size: 12pt">取引先</strong></div></td>
          <td width="150" height="30"><div align="center"><strong style="font-size: 12pt">請求年月日</strong></div></td>
          <td width="120" height="30"><div align="center"><strong style="font-size: 12pt">請求額</strong></div></td>
          <td width="150" height="30"><div align="center"><strong style="font-size: 12pt">入金予定日</strong></div></td>
          <td width="120" height="30"><div align="center"><strong style="font-size: 12pt">入金額</strong></div></td>
          <td width="120" height="30"><div align="center"><strong style="font-size: 12pt">残高</strong></div></td>
        </tr>
      </tbody>
      <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
        <tr>
          <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($arrayNyukin["customer_name"]) ?></div></td>
          <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($arrayNyukin["date_seikyuu"]) ?></div></td>
          <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h(number_format($arrayNyukin["total_price"])." 円") ?></div></td>
          <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($arrayNyukin["nyuukinbi"]) ?></div></td>
          <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h(number_format($arrayNyukin["nyuukinngaku"])." 円") ?></div></td>
          <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h(number_format($arrayNyukin["zanndaka"])." 円") ?></div></td>
        </tr>
      </tbody>
    </table>

        <br>
          <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
          <tr bgcolor="#E6FFFF" >
            <td style="border-style: none;"><div align="center"><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
            <td width="20"  style="border-style: none;"></td>
            <td align="center" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('決定'), array('name' => 'confirm')); ?></div></td>
          </tr>
          </table>
          <br>
          <br>
          <br>

      </td>
    </tr>
  </table>

  <?= $this->Form->control('customerId', array('type'=>'hidden', 'value'=>$customerId, 'label'=>false)) ?>
  <?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
  <?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>
