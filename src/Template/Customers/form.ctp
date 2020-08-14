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
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/uriagesyori.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'uriagemenu')));?></td>
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/uriagesyoukai.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'uriagemenu')));?></td>
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/nyuukinnyuuryoku.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'uriagemenu')));?></td>
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/nyuukinsyoukai.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'uriagemenu')));?></td>
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/seikyuusyori.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'uriagemenu')));?></td>
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/seikyuuitiran.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'uriagemenu')));?></td>
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/seikyuurireki.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'uriagemenu')));?></td>
          </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">
        <table style="margin-bottom:0px" width="750" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/shinki.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Shinkies','action'=>'index')));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <table style="margin-bottom:0px" width="750" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/kokyaku.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Customers','action'=>'menu')));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <table style="margin-bottom:0px" width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
                  <tr style="border-style: none; background-color: #E6FFFF">
                    <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/touroku.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Customers','action'=>'form')));?></td>
                    <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/ichiran.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Customers','action'=>'veiw')));?></td>
                    <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/syuusei.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Customers','action'=>'edit')));?></td>
                  </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($customers, ['url' => ['action' => 'confirm']]) ?>
        <br>
        <legend align="center"><strong style="font-size: 14pt; color:blue"><?= __("顧客登録") ?></strong></legend>
        <br>

        <table align="center" bordercolor="#000000">
                  <tbody border="2" bgcolor="#FFFFCC">
                  <td bordercolor="#000000"><div align="center"><strong style="font-size: 10pt; color:blue">顧客名</strong></div></td>
                  <tbody border="2" bgcolor="#FFFFCC">
                  <td colspan="2"><?= $this->Form->input("address", array('type' => 'value', 'label'=>false, 'size' => '50')); ?></td>
                  <tbody border="2" bgcolor="#FFFFCC">
                  <td colspan="2"><div align="center"><strong style="font-size: 10pt; color:blue">フリガナ（カタカナで入力してください）</strong></div></td>
                  <tbody border="2" bgcolor="#FFFFCC">
                  <td colspan="2"><?= $this->Form->input("address", array('type' => 'value', 'label'=>false, 'size' => '50')); ?></td>
                  <tbody border="2" bgcolor="#FFFFCC">
                  <td colspan="2"><div align="center"><strong style="font-size: 10pt; color:blue">住所</strong></div></td>
                  <tbody border="2" bgcolor="#FFFFCC">
                  <td colspan="2"><?= $this->Form->input("address", array('type' => 'value', 'label'=>false, 'size' => '50')); ?></td>
                  <tbody border="2" bgcolor="#FFFFCC">
                  <td colspan="2"><div align="center"><strong style="font-size: 10pt; color:blue">電話番号</strong></div></td>
                  <tbody border="2" bgcolor="#FFFFCC">
                  <td colspan="2"><?= $this->Form->input("address", array('type' => 'value', 'label'=>false, 'size' => '50')); ?></td>
                  <tbody border="2" bgcolor="#FFFFCC">
                  <td colspan="2"><div align="center"><strong style="font-size: 10pt; color:blue">FAX</strong></div></td>
                  <tbody border="2" bgcolor="#FFFFCC">
                  <td colspan="2"><?= $this->Form->input("address", array('type' => 'value', 'label'=>false, 'size' => '50')); ?></td>
                  <tbody border="2" bgcolor="#FFFFCC">
                  <td colspan="2"><div align="center"><strong style="font-size: 10pt; color:blue">担当者</strong></div></td>
                  <tbody border="2" bgcolor="#FFFFCC">
                  <td colspan="2"><?= $this->Form->input("address", array('type' => 'value', 'label'=>false, 'size' => '50')); ?></td>
        </table>
        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
          <br>
        <tr bgcolor="#E6FFFF" >
          <td align="left" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('入力内容確認'), array('name' => 'confirm')); ?></div></td>
        </tr>
        </table>

    </td>
  </tr>
</table>
