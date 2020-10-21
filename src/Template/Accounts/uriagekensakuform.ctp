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

        <?= $this->Form->create($uriages, ['url' => ['action' => 'uriagekensakuview']]) ?>
        <br>
        <legend align="center"><strong style="font-size: 11pt"><?= __("日時以外は空欄のままでも検索できます。") ?></strong></legend>
        <legend align="center"><strong style="font-size: 11pt"><?= __("フリガナはカタカナで入力してください。") ?></strong></legend>
        <br>

<table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
  <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: 0px;border-width: 1px">
    <tr style="border-bottom: 0px;border-width: 0px">
      <td style='border-bottom: 0px;border-width: 1px'  width="250" height="40" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 15pt;">顧客</strong></div></td>
      <td style='border-bottom: 0px;border-width: 1px'  width="250" height="40" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 15pt;">フリガナ</strong></div></td>
      <td style='border-bottom: 0px;border-width: 1px' colspan="40" nowrap="nowrap"><div align="center"><strong style="font-size: 15pt">納品書出力日</strong></div></td>
      <td style='border-bottom: 0px;border-width: 1px'  width="250" height="40" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt;">現場名・品番</strong></div></td>
    </tr>

<?php
      $dateYMD = date('Y-m-d');
      $dateYMD1 = strtotime($dateYMD);
      $dayye = date('Y-m-d', strtotime('-1 day', $dateYMD1));

      echo "<tr style='border-bottom: 0px;border-width: 0px'>\n";
      echo "<td style='border-bottom: 0px;border-width: 1px' rowspan='2'  height='40' colspan='20'  align='center'>\n";
      echo "<input type='text' name=customer />\n";
      echo "</td>\n";
      echo "<td style='border-bottom: 0px;border-width: 1px' rowspan='2'  height='40' colspan='20'  align='center'>\n";
      echo "<input type='text' name=furigana />\n";
      echo "</td>\n";
      echo "<td width='50' colspan='3' style='border-bottom: 0px'><div align='center'><strong style='font-size: 11pt; color:blue'>\n";
      echo "開始";
      echo "</strong></div></td>\n";
  //    echo "<td width='350' colspan='37' style='border-bottom: 0px'><div align='center'>\n";
  //    echo "<input type='date' value=$dayye name=date_sta empty=Please select size='6'/>\n";
  //    echo "</div></td>\n";

  ?>
  <td width="250" colspan="37" style="border-bottom: 0px;border-width: 1px"><div align="center"><?= $this->Form->input("date_sta", array('type' => 'date', 'value' => $dayye, 'monthNames' => false, 'label'=>false)); ?></div></td>
  <?php

      echo "<td  style='border-bottom: 0px;border-width: 1px' rowspan='2' height='40' colspan='20' align='center'>\n";
      echo "<input type='text' name=proname />\n";
      echo "</td>\n";
      echo "</tr>\n";
      echo "<tr style='border-bottom: 0px;border-width: 0px'>\n";
      echo "<td colspan='3' style='border-bottom: 0px;border-width: 1px'><div align='center'><strong style='font-size: 11pt; color:blue'>\n";
      echo "終了";
      echo "</strong></div></td>\n";
//      echo "<td colspan='37'><div align='center'>\n";
//      echo "<input type='date' value=$dateYMD name=date_fin size='6'/>\n";
//      echo "</div></td>\n";

?>
<td colspan="37" style="border-bottom: 0px;border-width: 1px"><div align="center"><?= $this->Form->input("date_fin", array('type' => 'date', 'value' => $dateYMD, 'monthNames' => false, 'label'=>false)); ?></div></td>
<?php
      echo "</tr>\n";
 ?>
</table>
<br>
<table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
  <tr bgcolor="#E6FFFF" >
    <td align="center" rowspan="2"  colspan="20" width="250" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('検索'), array('name' => 'kensaku')); ?></div></td>
  </tr>
</table>
</fieldset>

<?=$this->Form->end() ?>
