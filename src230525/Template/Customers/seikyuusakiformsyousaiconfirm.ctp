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
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/seikyuusaki.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($Customer, ['url' => ['action' => 'seikyuusakiformsyousaido']]) ?>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">顧客名 支店</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($customeroya) ?></td>
          </tr>
        </table>
          <br>
          <table align="center">
            <tr>
              <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">請求先</strong></td>
            </tr>
            <tr>
              <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($namehyouji) ?></td>
            </tr>
          </table>
            <br>

            <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
            <tr bgcolor="#E6FFFF" >
              <td style="border-style: none;"><div align="center"><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
              <td width="40"></td>
              <td style="border-style: none;"><div align="center"><?= $this->Form->submit('決定', array('name' => 'kakunin')); ?></div></td>
            </tr>
            </table>

  <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$this->request->getData('id'), 'label'=>false)) ?>
  <?= $this->Form->control('customeroya', array('type'=>'hidden', 'value'=>$customeroya, 'label'=>false)) ?>
  <?= $this->Form->control('seikyuusakicustomerId', array('type'=>'hidden', 'value'=>$seikyuusakicustomerId, 'label'=>false)) ?>
  <?= $this->Form->control('customerkodomo', array('type'=>'hidden', 'value'=>$namehyouji, 'label'=>false)) ?>
