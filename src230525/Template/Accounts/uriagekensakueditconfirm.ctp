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

        <table style="margin-bottom:0px" width="750" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/uriagesyoukai.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">
        <?= $this->Form->create($uriages, ['url' => ['action' => 'uriagekensakueditdo']]) ?>

        <br>
        <?php if($mb_strlen_check == 1): ?>
          <legend align="center"><strong style="font-size: 11pt;color: red"><?= __($mess) ?></strong></legend>
        <?php else: ?>
          <legend align="center"><strong style="font-size: 11pt"><?= __($mess) ?></strong></legend>
        <?php endif; ?>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">宛先</strong></td>
            <td align="center" width="180" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">日付</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">分類</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">税別・内税</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($customer) ?></td>
            <td align="center" width="180"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($syutsuryokubi) ?></div></td>
            <td align="center" width="100"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($bunrui) ?></div></td>
            <td align="center" width="100"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($tax_include) ?></div></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="200" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">郵便番号</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">住所</strong></td>
            <td align="center" width="50" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">敬称</strong></td>
          </tr>
          <tr>
            <td  align="center" width="200"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($yuubin) ?></td>
            <td  align="center" width="280" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($address) ?></td>
            <td  align="center" width="30" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($keisyou) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="50" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">伝票番号</strong></td>
          </tr>
          <tr>
            <td  align="center" width="280" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($denpyou_num) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="50" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">修正前合計金額（円）</strong></td>
            <td align="center" width="50" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">修正後合計金額（円）</strong></td>
          </tr>
          <tr>
            <td  align="center" width="280" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($totalprice_moto)) ?></td>
            <td  align="center" width="280" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($totalprice_new)) ?></td>
          </tr>
        </table>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="300" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">品名（現場名）</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">数量</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">単位</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">単価</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">金額</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">日付（備考）</strong></td>
          </tr>

      <?php for ($i=0;$i<count($Uriagesyousais);$i++): ?>

          <tr>
            <td  align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($Uriagesyousais[$i]["pro"]) ?></td>
            <td  align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($Uriagesyousais[$i]["amount"]) ?></td>
            <td  align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($Uriagesyousais[$i]["tani"]) ?></td>
            <?php if($Uriagesyousais[$i]["tanka"] > 0): ?>
            <td bgcolor="#FFFFCC" width="30"  style="padding: 0.2rem"><?= h(number_format($Uriagesyousais[$i]["tanka"])) ?></td>
          <?php else: ?>
            <td bgcolor="#FFFFCC" width="30"  style="padding: 0.2rem"><?= h($Uriagesyousais[$i]["tanka"]) ?></td>
          <?php endif; ?>
          <?php if($Uriagesyousais[$i]["price"] > 0): ?>
          <td bgcolor="#FFFFCC" width="30"  style="padding: 0.2rem"><?= h(number_format($Uriagesyousais[$i]["price"])) ?></td>
        <?php else: ?>
          <td bgcolor="#FFFFCC" width="30"  style="padding: 0.2rem"><?= h($Uriagesyousais[$i]["price"]) ?></td>
        <?php endif; ?>
            <td  align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($Uriagesyousais[$i]["bik"]) ?></td>
          </tr>

      <?php endfor;?>

    </table>
    <br>

    <?php if($mb_strlen_check == 1): ?>

    <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
      <tr bgcolor="#E6FFFF" >
        <td style="border-style: none;"><div align="center"><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      </tr>
    </table>

  <?php else: ?>

    <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
      <tr bgcolor="#E6FFFF" >
        <td style="border-style: none;"><div align="center"><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        <td width="40"></td>
        <td style="border-style: none;"><div align="center"><?= $this->Form->submit('決定', array('name' => 'output')); ?></div></td>
      </tr>
    </table>

  <?php endif; ?>

    <br>
    <br>
