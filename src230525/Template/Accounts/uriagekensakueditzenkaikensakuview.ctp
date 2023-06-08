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

        <?= $this->Form->create($uriages, ['url' => ['action' => 'uriagekensakuedittuika']]) ?>
        <br>
        <legend align="center"><strong style="font-size: 14pt"><?= __("前回情報個別選択") ?></strong></legend>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">宛先</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($namehyouji) ?></td>
          </tr>
        </table>
        <br>
        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
          <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: 0px;border-width: 1px">
                <thead>
                    <tr border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
                      <td align="center" width="150" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">売上日</strong></td>
                      <td align="center" width="300" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">品名（現場名）</strong></td>
                      <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">数量</strong></td>
                      <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">単位</strong></td>
                      <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">単価</strong></td>
                      <td width="50" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">順番</strong></div></td>
                    </tr>
                </thead>
                <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">

                  <?php
                  $i = 0;
                  ?>

                  <?php foreach ($Uriages as $Uriages): ?>

                  <tr>
                    <td style="border-bottom: 0px;border-width: 1px" align="center"><font><?= h($Uriages->uriagebi->format('Y-m-d')) ?></font></td>
                    <td style="border-bottom: 0px;border-width: 1px" align="center"><font><?= h($Uriages->pro) ?></font></td>
                    <td style="border-bottom: 0px;border-width: 1px" align="center"><font><?= h($Uriages->amount) ?></font></td>
                    <td style="border-bottom: 0px;border-width: 1px" align="center"><font><?= h($Uriages->tani) ?></font></td>
                    <td style="border-bottom: 0px;border-width: 1px" align="center"><font><?= h($Uriages->tanka) ?></font></td>
                    <td style="border-bottom: 0px;border-width: 1px" align="center">
                      <?= $this->Form->input('select'.$i, array('type'=>'text', 'label'=>false,  'size'=>1, 'pattern'=>"^[0-9.]+$", 'title'=>"半角数字で入力して下さい。")) ?>
                      </td>
                  </tr>

                    <?= $this->Form->control($i, array('type'=>'hidden', 'value'=>$Uriages->id, 'label'=>false)) ?>

                    <?= $this->Form->control("num_max", array('type'=>'hidden', 'value'=>$i, 'label'=>false)) ?>

                    <?php
                    $i = $i + 1;
                    ?>
                            <?php endforeach; ?>
                          </tbody>
                      </table>

                      <br>
                      <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
                      <tr bgcolor="#E6FFFF" >
                        <td style="border-style: none;"><div align="center"><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
                        <td align="right" rowspan="2" width="20" bgcolor="#E6FFFF" style="border: none">　　</td>
                        <td style="border: none"><div align="center"><?= $this->Form->submit(__('決定'), array('name' => 'editzenkaikobetu')); ?></div></td>
                      </tr>
                      </table>
                      <br>
                      <br>
