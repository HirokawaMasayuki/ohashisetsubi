<?php
$this->layout = '';

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

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
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/nyuukinsyoukai.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <?= $this->Form->create($nyuukins, ['url' => ['action' => 'nyuukinsyoukaiedit']]) ?>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">取引先名</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">集金・振込</strong></td>
          </tr>
          <tr>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($customer) ?></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($syuukinfurikomi) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金予定日</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">請求年月日</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($datenyuukinyotei) ?></div></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($dateseikyuu) ?></div></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">繰越額</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">請求額</strong></td>
          </tr>
          <tr>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($kurikosi." 円") ?></td>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($seikyuu." 円") ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金日</strong></td>
            <td align="center" width="280" colspan="3" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">種別</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($datenyuukin) ?></div></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($syubetu) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">残高</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">備考</strong></td>
          </tr>
          <tr>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($zandaka." 円") ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($bik) ?></td>
          </tr>
        </table>
        <br>

            <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
            <tr bgcolor="#E6FFFF" >
              <td style="border-style: none;"><div align="center"><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
              <td width="30"  style="border-style: none;"></td>
              <td align="left" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('編集・削除'), array('name' => 'confirm')); ?></div></td>
            </tr>
            </table>
            <br>
    </td>
  </tr>
</table>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
<?= $this->Form->control('customer', array('type'=>'hidden', 'value'=>$customer, 'label'=>false)) ?>
<?= $this->Form->control('syuukinfurikomi', array('type'=>'hidden', 'value'=>$syuukinfurikomi, 'label'=>false)) ?>
<?= $this->Form->control('datenyuukinyoteitouroku', array('type'=>'hidden', 'value'=>$datenyuukinyoteitouroku, 'label'=>false)) ?>
<?= $this->Form->control('dateseikyuutouroku', array('type'=>'hidden', 'value'=>$dateseikyuutouroku, 'label'=>false)) ?>
<?= $this->Form->control('datenyuukintouroku', array('type'=>'hidden', 'value'=>$datenyuukintouroku, 'label'=>false)) ?>
<?= $this->Form->control('dateseikyuu', array('type'=>'hidden', 'value'=>$dateseikyuu, 'label'=>false)) ?>
<?= $this->Form->control('kurikosi', array('type'=>'hidden', 'value'=>$kurikosi, 'label'=>false)) ?>
<?= $this->Form->control('seikyuu', array('type'=>'hidden', 'value'=>$seikyuu, 'label'=>false)) ?>
<?= $this->Form->control('syubetu', array('type'=>'hidden', 'value'=>$syubetu, 'label'=>false)) ?>
<?= $this->Form->control('zandaka', array('type'=>'hidden', 'value'=>$zandaka, 'label'=>false)) ?>
<?= $this->Form->control('bik', array('type'=>'hidden', 'value'=>$bik, 'label'=>false)) ?>
