<?php
$this->layout = '';

use Cake\ORM\TableRegistry;//独立したテーブルを扱う

$this->Uriagemasters = TableRegistry::get('uriagemasters');
$this->Uriagesyousais = TableRegistry::get('uriagesyousais');
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

        <?= $this->Form->create($uriages, ['url' => ['action' => 'uriagekensakuedit']]) ?>
<br><br>

<?php if($pronamecheck != 1): ?>

  <?php
  $Uriagetotal = 0;
  ?>

<table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
  <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: 0px;border-width: 1px">
        <thead>
            <tr border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
              <td width="150" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">伝票番号</strong></div></td>
              <td width="300" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">顧客</strong></div></td>
              <td width="100" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">分類</strong></div></td>
              <td width="150" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">出力日</strong></div></td>
              <td width="200" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">売上</strong></div></td>
              <td width="50" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt"></strong></div></td>
            </tr>
        </thead>
        <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
          <?php foreach ($Uriages as $Uriages): ?>

            <?php

            $Uriagesyousais = $this->Uriagesyousais->find()
            ->where([ 'uriagemasterId' => $Uriages->id, 'delete_flag' => 0])->toArray();

            $Uriagetotalmaster = 0;

            for($i=0; $i<count($Uriagesyousais); $i++){

              if(!empty($Uriagesyousais[$i]->price)){

                $Uriagetotalmaster = $Uriagetotalmaster + $Uriagesyousais[$i]->price;

              }

            }
      //      $Uriagetotalmaster = $Uriagetotalmaster * 1.1;
            $Uriagetotalmaster = $Uriagetotalmaster;

            $Uriagetotal = $Uriagetotal + $Uriagetotalmaster;

            ?>
          <tr>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriages->denpyou_num) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriages->customer) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriages->bunrui) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriages->uriagebi->format('Y年m月d日')) ?></font></td>
            <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriagetotalmaster." 円") ?></font></td>
            <?php
            echo "<td colspan='20' nowrap='nowrap'><div align='center'>";
            echo $this->Form->submit("修正" , ['action'=>'syousai', 'name' => $Uriages->id]) ;
            echo "</div></td>";
            ?>
          </tr>

          <?= $this->Form->control('Uriagetotalmoto', array('type'=>'hidden', 'value'=>$Uriagetotalmaster, 'label'=>false)) ?>

          <?php endforeach; ?>
        </tbody>
    </table>
<br>
<table align="center">
  <tr>
    <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">売上合計</strong></td>
  </tr>
  <tr>
    <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($Uriagetotal." 円") ?></div></td>
  </tr>
</table>
<br>
<br>

<?php else: ?>

  <legend align="center"><strong style="font-size: 18pt"><?= "売上品詳細" ?></strong></legend>
  <br>

  <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
    <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: 0px;border-width: 1px">
          <thead>
              <tr border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
                <td width="200" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 11pt">売上日</strong></div></td>
                <td width="150" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 11pt">品名（現場名）</strong></div></td>
                <td width="100" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">数量</strong></div></td>
                <td width="100" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">単位</strong></div></td>
                <td width="100" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">単価</strong></div></td>
                <td width="100" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">金額</strong></div></td>
                <td width="150" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 11pt">日付（備考）</strong></div></td>
              </tr>
          </thead>
          <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
            <?php foreach ($Uriages as $Uriages): ?>
            <tr>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriages->uriagebi->format('Y-m-d')) ?></font></td>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriages->pro) ?></font></td>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriages->amount) ?></font></td>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriages->tani) ?></font></td>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriages->tanka) ?></font></td>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriages->price) ?></font></td>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriages->bik) ?></font></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
      </table>
  <br><br>

<?php endif; ?>
