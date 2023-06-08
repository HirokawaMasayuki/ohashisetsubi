<?php
$this->layout = '';
?>
<table width="1300" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#E6FFFF">
  <tr style="background-color: #E6FFFF">
    <td>
        <table width="1300" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td bgcolor="#E6FFFF">
            </body>
            </td>
          </tr>
        </table>
        <br>
        <table style="margin-bottom:0px" width="1300" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/top.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'index')));?></td>
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/shinki.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Shinkies','action'=>'index')));?></td>
        </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">
        <table style="margin-bottom:0px" width="1300" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/seikyuusyori.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <?= $this->Form->create($customers, ['url' => ['action' => 'seikyuuformcustomer']]) ?>
        <br>
        <legend align="center"><strong style="font-size: 11pt"><?= __($mesxlsx) ?></strong></legend>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">取引先名</strong></td>
            <td align="center" width="60" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">締め日</strong></td>
            <td align="center" width="60" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">必着日</strong></td>
            <td align="center" width="60" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金日</strong></td>
            <td align="center" width="80" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">回収方法</strong></td>
          </tr>
          <tr>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($namehyouji." ".$siten) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($simebi."日") ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($hittyakubi."日") ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($nyuukinbi."日") ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($kaisyuu) ?></td>
          </tr>
        </table>
        <br>

        <?php if($flag_utizei != 1): ?>

        <table align="center">
          <tr>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">請求日</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">前回請求</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金額</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">調整</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">相殺</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">繰越</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">当月売上</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">消費税</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">税込売上</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">今月請求</strong></td>
          </tr>
          <tr>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($this->request->getData('seikyuubi')) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($this->request->getData('Zenkai'))) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($this->request->getData('nyuukingaku'))) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($this->request->getData('tyousei'))) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($this->request->getData('sousai'))) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($this->request->getData('kurikosi'))) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($totalkingaku)) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($totaltax)) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($totalkingakutaxinc)) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($this->request->getData('kurikosi') + $totalkingakutaxinc)) ?></td>
          </tr>
        </table>

        <br>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 9pt">No.</strong></td>
            <td align="center" width="70" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 9pt">伝票番号</strong></td>
            <td align="center" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">売上先</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">売上日</strong></td>
            <td align="center" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">品名１行目</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">売上額</strong></td>
            <td align="center" width="50" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">税率</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">消費税</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">税込売上額</strong></td>
          </tr>

      <?php for ($i=0;$i<$count;$i++): ?>

          <tr>
            <td align="center"  bgcolor="#FFFFCC"  style="padding: 0.2rem"><?= h($i+1) ?></td>
            <td align="center"  bgcolor="#FFFFCC"  style="padding: 0.2rem"><?= h($arrDenpyou[$i]) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(${"namehyouji".$i}) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($arrSyuturyoku[$i]) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($arrPro_1[$i]) ?></td>
            <?php if($arrTaxinc[$i] == 0): ?>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format(${"Totalprice".$i})) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format(${"Totalprice".$i}*0.1)) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format(${"Totalprice".$i}*1.1)) ?></td>
          <?php else: ?>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format(${"Totalprice".$i})) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h("内税") ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format(${"Totalprice".$i})) ?></td>
          <?php endif; ?>
          </tr>

      <?php endfor;?>

    </table>
    <br>

      <?php else: ?>

        <table align="center">
          <tr>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">請求日</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">前回請求</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金額</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">調整</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">相殺</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">繰越</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">当月売上</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">今月請求</strong></td>
          </tr>
          <tr>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($this->request->getData('seikyuubi')) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($this->request->getData('Zenkai'))) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($this->request->getData('nyuukingaku'))) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($this->request->getData('tyousei'))) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($this->request->getData('sousai'))) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($this->request->getData('kurikosi'))) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($totalkingaku)) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($totalkingaku + $this->request->getData('kurikosi'))) ?></td>
          </tr>
        </table>

        <br>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 9pt">No.</strong></td>
            <td align="center" width="70" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 9pt">伝票番号</strong></td>
            <td align="center" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">売上先</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">売上日</strong></td>
            <td align="center" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">品名１行目</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">売上額</strong></td>
          </tr>

      <?php for ($i=0;$i<$count;$i++): ?>

          <tr>
            <td align="center"  bgcolor="#FFFFCC"  style="padding: 0.2rem"><?= h($i+1) ?></td>
            <td align="center"  bgcolor="#FFFFCC"  style="padding: 0.2rem"><?= h($arrDenpyou[$i]) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(${"namehyouji".$i}) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($arrSyuturyoku[$i]) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($arrPro_1[$i]) ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format(${"Totalprice".$i})) ?></td>
          </tr>

      <?php endfor;?>

    </table>
    <br>

      <?php endif; ?>


    <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
    <tr bgcolor="#E6FFFF" >
      <td align="left" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('続けて入力'), array('name' => 'confirm')); ?></div></td>
    </tr>
    </table>

    </td>
  </tr>
</table>
