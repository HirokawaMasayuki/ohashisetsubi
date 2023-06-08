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
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/uriagesyori.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($uriages, ['url' => ['action' => 'uriageformsyousai']]) ?>
        <br>
        <legend align="center"><strong style="font-size: 14pt"><?= __("前回情報選択") ?></strong></legend>
        <br>

        <?php
        $Uriagetotal = 0;
        ?>

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
                      <td width="150" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">伝票番号</strong></div></td>
                      <td width="300" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">品番１行目</strong></div></td>
                      <td width="200" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">出力日</strong></div></td>
                      <td width="200" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt">売上</strong></div></td>
                      <td width="50" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 13pt"></strong></div></td>
                    </tr>
                </thead>
                <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
                  <?php foreach ($Uriages as $Uriages): ?>

                    <?php

                    $Uriagesyousais = $this->Uriagesyousais->find()
                    ->where([ 'uriagemasterId' => $Uriages->id, 'delete_flag' => 0])->order(["num"=>"asc"])->toArray();

                    $Uriagetotalmaster = 0;

                    for($i=0; $i<count($Uriagesyousais); $i++){

                      if(!empty($Uriagesyousais[$i]->price)){

                        $Uriagetotalmaster = $Uriagetotalmaster + $Uriagesyousais[$i]->price;

                      }

                    }
        //            $Uriagetotalmaster = $Uriagetotalmaster * 1.1;

                    $Uriagetotal = $Uriagetotal + $Uriagetotalmaster;

                    if(isset($Uriagesyousais[0])){
                      $pro = $Uriagesyousais[0]->pro;
                    }else{
                      $pro = "";
                    }

                    ?>
                  <tr>
                    <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriages->denpyou_num) ?></font></td>
                    <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($pro) ?></font></td>
                    <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($Uriages->uriagebi->format('Y年m月d日')) ?></font></td>
                    <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h(number_format($Uriagetotalmaster)." 円") ?></font></td>
                    <?php
                    echo "<td colspan='20' nowrap='nowrap'><div align='center'>";
                    echo $this->Form->submit("選択" , ['action'=>'syousai', 'name' => $Uriages->id]) ;
                    echo "</div></td>";
                    ?>
                  </tr>

                            <?= $this->Form->control('Uriagetotalmoto', array('type'=>'hidden', 'value'=>$Uriagetotalmaster, 'label'=>false)) ?>

                            <?php endforeach; ?>
                          </tbody>
                      </table>
        <br>
        <br>

        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
        <tr bgcolor="#E6FFFF" >
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        </tr>
        </table>

        <?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$namehyouji, 'label'=>false)) ?>
        <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
        <?= $this->Form->control('zenkaiikkatu', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
