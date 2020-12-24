<?php
$this->layout = '';

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

use Cake\ORM\TableRegistry;//独立したテーブルを扱う
$this->Customers = TableRegistry::get('customers');

?>

<table width="1400" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#E6FFFF">
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
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/urikakeitiran.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($nyuukins, ['url' => ['action' => 'urikakeitiran']]) ?>


        <br>
        <legend align="center"><strong style="font-size: 13pt"><?= __($mesxlsx) ?></strong></legend>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="250" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">期間</strong></td>
            <td align="center" width="200" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">請求額合計</strong></td>
            <td align="center" width="200" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金額合計</strong></td>
            <td align="center" width="200" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">残金合計</strong></td>
            <td align="center" width="200" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">繰越額合計</strong></td>
          </tr>
          <tr>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($date_y."年".$date_m."月分") ?></div></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($totalkingaku." 円") ?></div></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($totalnyuukin." 円") ?></div></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($totalzandaka." 円") ?></div></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($totalkurikosi." 円") ?></div></td>
          </tr>
        </table>

<br><br>
<table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
  <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: 0px;border-width: 1px">
        <thead>
            <tr border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
              <td width="70" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">請求日</strong></div></td>
              <td width="270" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">取引先</strong></div></td>
              <td width="70" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">繰越額</strong></div></td>
              <td width="70" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">請求額</strong></div></td>
              <td width="70" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">入金日</strong></div></td>
              <td width="70" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">相殺</strong></div></td>
              <td width="70" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">種類</strong></div></td>
              <td width="70" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">日付</strong></div></td>
              <td width="70" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">小切手</strong></div></td>
              <td width="70" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">種別</strong></div></td>
              <td width="70" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">入金額</strong></div></td>
              <td width="70" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">調整</strong></div></td>
              <td width="70" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">残金</strong></div></td>
              <td width="130" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">備考</strong></div></td>
              <td width="90" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 10pt">入金予定日</strong></div></td>
            </tr>
        </thead>
        <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
          <?php for ($i=0;$i<count($arrSeikyuu);$i++): ?>
          <tr>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["syutsuryokubi"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["customer"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["kurikosi"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["seikyuugaku"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["nyuukinbi"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["sousai"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["kogite"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["kogiteday"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["kogitetotal"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["syubetu"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["nyuukingaku"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["tyousei"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["zandaka"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["bik"]) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font style="font-size: 10pt"><?= h($arrSeikyuu[$i]["customernyuukinbi"]) ?></font></td>
          </tr>
        <?php endfor;?>
        </tbody>
    </table>
<br>
<table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
<tr bgcolor="#E6FFFF" >
  <td align="left" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('エクセル出力'), array('name' => 'excel')); ?></div></td>
</tr>
</table>
<br>
<?= $this->Form->control('date_sta_y', array('type'=>'hidden', 'value'=>$this->request->getData('date_sta_y'), 'label'=>false)) ?>
<?= $this->Form->control('date_sta_y', array('type'=>'hidden', 'value'=>$this->request->getData('date_sta_y'), 'label'=>false)) ?>
<?= $this->Form->control('date_sta_m', array('type'=>'hidden', 'value'=>$this->request->getData('date_sta_m'), 'label'=>false)) ?>
