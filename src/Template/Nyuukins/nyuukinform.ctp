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

        <?= $this->Form->create($Nyuukins, ['url' => ['action' => 'nyuukinform']]) ?>
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
        <?php if($check == 0): ?>

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

        <?php else: ?>

        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">請求年月日</strong></td>
            <td align="center" width="160" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">請求額（円）</strong></td>
          </tr>
          <tr>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= $this->Form->input("date_seikyuu_input", array('type' => 'date', 'monthNames' => false, 'label'=>false, 'value'=>$date_seikyuu_input)); ?></div></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('totalseikyuu_input', array('type'=>'text', 'label'=>false, 'value'=>$totalseikyuu_input, 'size'=>18, 'autocomplete'=>'off', 'pattern'=>"^[0-9.]+$", 'title'=>"半角数字で入力して下さい。")) ?></td>
          </tr>
        </table>

      <?php endif; ?>

        <br>

        <table align="center">
          <tr>
            <td align="center" width="160" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">種別</strong></td>
            <td align="center" width="160" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金額（円）</strong></td>
            <td align="center" width="160" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金日</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">備考</strong></td>
          </tr>

          <?php for ($i=1;$i<=$tuika;$i++): ?>

          <tr>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input("syubetu".$i, ["type"=>"select", "empty"=>"選択してください", "options"=>$arrSyubetu, 'value'=>${"syubetu".$i}, 'label'=>false]) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('nyuukinngaku'.$i, array('type'=>'text', 'label'=>false, 'value'=>${"nyuukinngaku".$i}, 'size'=>18, 'autocomplete'=>'off', 'pattern'=>"^[0-9.]+$", 'title'=>"半角数字で入力して下さい。")) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= $this->Form->input("datenyuukin".$i, array('type' => 'date', 'monthNames' => false, 'label'=>false, 'value'=>${"datenyuukin".$i})); ?></div></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('bik'.$i, array('type'=>'text', 'label'=>false, 'size'=>38, 'value'=>${"bik".$i}, 'autocomplete'=>'off')) ?></td>
          </tr>

        <?= $this->Form->control('num', array('type'=>'hidden', 'value'=>$i, 'label'=>false)) ?>

      <?php endfor;?>

    </table>

      <br>
        <table align="right" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
        <tr bgcolor="#E6FFFF" >
          <td align="right" rowspan="2" width="50" bgcolor="#E6FFFF" style="border: none"><div align="right"><?= $this->Form->submit(__('１行削除'), array('name' => 'sakujo')); ?></div></td>
          <td width="20"  style="border-style: none;"></td>
          <td align="right" rowspan="2" width="50" bgcolor="#E6FFFF" style="border: none"><div align="right"><?= $this->Form->submit(__('１行追加'), array('name' => 'tuika')); ?></div></td>
          <td width="20"  style="border-style: none;"></td>
          <td align="right" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('入力内容確認'), array('name' => 'confirm')); ?></div></td>
          <td width="80"  style="border-style: none;"></td>
        </tr>
        </table>
        <br>
        <br>
        <br>

    </td>
  </tr>
</table>

<?= $this->Form->control('check', array('type'=>'hidden', 'value'=>$check, 'label'=>false)) ?>
<?= $this->Form->control('customer_id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
<?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
<?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>
