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

        <hr size="1" style="margin: 0.5rem">
        <table style="margin-bottom:0px" width="750" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/nyuukinnyuuryoku.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <?= $this->Form->create($Nyuukins, ['url' => ['action' => 'nyuukindo']]) ?>
        <br>
        <legend align="center"><strong style="font-size: 12pt;color: black"><?= __($mes) ?></strong></legend>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">取引先名</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金予定日</strong></td>
          </tr>
          <tr>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($namehyouji) ?></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($nyuukinyotei."日") ?></div></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">請求年月日</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">請求額（円）</strong></td>
          </tr>
          <tr>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($date_seikyuu) ?></div></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h(number_format($totalseikyuu)) ?></div></td>
          </tr>
        </table>
        <br>


        <table align="center">
          <tr>
            <td align="center" width="160" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">種別</strong></td>
            <td align="center" width="160" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金額（円）</strong></td>
            <td align="center" width="160" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金日</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">備考</strong></td>
          </tr>

          <?php
          $total_nyuukinngaku = 0;
          ?>

          <?php for ($i=1;$i<=$tuika;$i++): ?>

          <tr>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(${"syubetu".$i}) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format(${"nyuukinngaku".$i})) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(${"datenyuukin".$i}) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(${"bik".$i}) ?></td>
          </tr>

          <?= $this->Form->control('syubetu'.$i, array('type'=>'hidden', 'value'=>${"syubetu".$i}, 'label'=>false)) ?>
          <?= $this->Form->control('nyuukinngaku'.$i, array('type'=>'hidden', 'value'=>${"nyuukinngaku".$i}, 'label'=>false)) ?>
          <?= $this->Form->control('datenyuukin'.$i, array('type'=>'hidden', 'value'=>${"datenyuukin".$i}, 'label'=>false)) ?>
          <?= $this->Form->control('bik'.$i, array('type'=>'hidden', 'value'=>${"bik".$i}, 'label'=>false)) ?>

        <?= $this->Form->control('num', array('type'=>'hidden', 'value'=>$i, 'label'=>false)) ?>
        <?php
        $total_nyuukinngaku = $total_nyuukinngaku + ${"nyuukinngaku".$i};
        ?>

      <?php endfor;?>

    </table>

    <br>
    <table align="center">
      <tr>
        <td align="center" width="200" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">請求額（円）</strong></td>
        <td align="center" width="200" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金額合計（円）</strong></td>
        <td align="center" width="200" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">差額（円）</strong></td>
      </tr>
      <tr>
        <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h(number_format($totalseikyuu)) ?></div></td>
        <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h(number_format($total_nyuukinngaku)) ?></div></td>
        <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h(number_format(round($totalseikyuu) - round($total_nyuukinngaku))) ?></div></td>
      </tr>
    </table>
    <br>

      <br>
        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
        <tr bgcolor="#E6FFFF" >
          <td align="center" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('同じ月で続けて入力'), array('name' => 'onaji')); ?></div></td>
          <td width="20"  style="border-style: none;"></td>
          <td align="center" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('違う月で続けて入力'), array('name' => 'tigau')); ?></div></td>
        </tr>
        </table>
        <br>
        <br>
        <br>

    </td>
  </tr>
</table>

<?= $this->Form->control('customer_id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
<?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
<?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>
