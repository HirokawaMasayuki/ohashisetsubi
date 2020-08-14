<?php
$this->layout = '';
?>
<?php
use App\myClass\Shinkimenus\htmlShinkimenu;//myClassフォルダに配置したクラスを使用
$htmlShinkimenu = new htmlShinkimenu();
$htmlShinkis = $htmlShinkimenu->Shinkimenus();
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
        <br><br>
        <table style="margin-bottom:0px" width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
                  <tr style="border-style: none; background-color: #E6FFFF">
                    <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/uriagesyori.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'uriagemenu')));?></td>
                    <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/shinki.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Shinkies','action'=>'menu')));?></td>
                    <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/kokyaku.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'form')));?></td>
                  </tr>
        </table>
            <br><br>

          <legend align="center"><strong style="font-size: 14pt; color:blue"><?= __("顧客登録") ?></strong></legend>

      <?= $this->Form->create($customer, ['url' => ['action' => 'confirm']]) ?>
      <fieldset>
        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0" style="border-bottom: solid;border-width: 1px">
                <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
                  <td><div align="center"><strong style="font-size: 12pt; color:blue">顧客コード</strong></div></td>
                  <td style="border-left-style: none;"><div align="center"><strong style="font-size: 12pt; color:blue">顧客名</strong></div></td>
                <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
                  <td><?= $this->Form->input("customer_code", array('type' => 'value', 'label'=>false, 'autofocus'=>true)); ?></td>
              		<td style="border-left-style: none;"><?= $this->Form->input("name", array('type' => 'value', 'label'=>false)); ?></td>
                <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
                  <td colspan="2"><div align="center"><strong style="font-size: 12pt; color:blue">住所</strong></div></td>
                <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
                  <td colspan="2"><?= $this->Form->input("address", array('type' => 'value', 'label'=>false, 'size' => '50')); ?></td>
                <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
                  <td><div align="center"><strong style="font-size: 12pt; color:blue">電話番号</strong></div></td>
                  <td style="border-left-style: none;"><div align="center"><strong style="font-size: 12pt; color:blue">FAX</strong></div></td>
                <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
                  <td><?= $this->Form->input("tel", array('type' => 'value', 'label'=>false)); ?></td>
        		      <td style="border-left-style: none;"><?= $this->Form->input("fax", array('type' => 'value', 'label'=>false)); ?></td>
                <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
                  <td><div align="center"><strong style="font-size: 12pt; color:blue">納入先ID</strong></div></td>
                  <td style="border-left-style: none;"><div align="center"><strong style="font-size: 12pt; color:blue">ハンディ表示名</strong></div></td>
                <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
                  <td><?= $this->Form->input("place_deliver_code", array('type' => 'value', 'label'=>false)); ?></td>
                  <td style="border-left-style: none;"><?= $this->Form->input("name_handy", array('type' => 'value', 'label'=>false)); ?></td>
        </table>

      </fieldset>

      <legend align="center"><font color="red"><?= __("※住所、電話番号、FAXは空のままでも登録できます。") ?></font></legend>
      <br>

      <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
      <tr>
        <td style="border-style: none;"><div align="center"><?= $this->Form->submit(__('確認'), array('name' => 'kakunin')); ?></div></td>
      </tr>
    </table>
  <br>

</td>
</tr>
</table>
