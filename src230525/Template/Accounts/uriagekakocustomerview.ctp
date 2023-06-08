<?php
$this->layout = '';
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

        <table style="margin-bottom:0px" width="1600" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/uriagesyoukai.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <table style="margin-bottom:0px" width="750" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/uriagekako.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($uriages, ['url' => ['action' => 'uriagekensakusyousai']]) ?>
<br><br>

<table align="center">
  <tr>
    <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">顧客</strong></td>
    <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">売上合計</strong></td>
  </tr>
  <tr>
    <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($customer) ?></div></td>
    <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h(number_format($Uriagetotalhyouji)." 円") ?></div></td>
  </tr>
</table>

<br>
<br>
    <legend align="center"><strong style="font-size: 11pt"><?= __("以下、個別売上詳細") ?></strong></legend>
    <br>

<table align="center">
  <tr>
    <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">伝票番号</strong></td>
    <td align="center" width="160" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">売上日</strong></td>
    <td align="center" width="250" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">現場名</strong></td>
    <td align="center" width="250" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">品名</strong></td>
    <td align="center" width="70" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">数量</strong></td>
    <td align="center" width="70" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">単位</strong></td>
    <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">単価</strong></td>
    <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">金額</strong></td>
    <td align="center" width="180" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">備考</strong></td>
  </tr>

          <?php for($i=0; $i<count($arrUriages); $i++): ?>
          <tr>
              <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($arrUriages[$i]["denpyou_num"]) ?></td>
              <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($arrUriages[$i]["syutsuryokubi"]) ?></td>
              <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($arrUriages[$i]["genba"]) ?></td>
              <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($arrUriages[$i]["pro"]) ?></td>
              <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($arrUriages[$i]["amount"]) ?></td>
              <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($arrUriages[$i]["tani"]) ?></td>
              <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h(number_format($arrUriages[$i]["tanka"])."円") ?></td>
              <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h(number_format($arrUriages[$i]["price"])."円") ?></td>
              <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($arrUriages[$i]["bik"]) ?></td>
          </tr>
        <?php endfor;?>

      </table>
      <br>
