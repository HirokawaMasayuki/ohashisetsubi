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
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/uriagesyori.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($uriages, ['url' => ['action' => 'uriagesyuturyoku']]) ?>
        <br>
        <legend align="center"><strong style="font-size: 14pt"><?= __("詳細確認") ?></strong></legend>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">宛先</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">日付</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($name) ?></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($dateexcl) ?></div></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="200" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">郵便番号</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">住所</strong></td>
            <td align="center" width="50" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">敬称</strong></td>
          </tr>
          <tr>
            <td  align="center" width="200"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($yuubin) ?></td>
            <td  align="center" width="280" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($address) ?></td>
            <td  align="center" width="30" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($keisyou) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="150" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">品名（現場名）</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">数量</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">単位</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">単価</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">金額</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">日付（備考）</strong></td>
          </tr>

      <?php for ($i=1;$i<=$tuika;$i++): ?>

          <tr>
            <td bgcolor="#FFFFCC" width="150"  style="padding: 0.2rem"><?= h(${"pro_".$i}) ?></td>
            <td bgcolor="#FFFFCC" width="30"  style="padding: 0.2rem"><?= h(${"amount_".$i}) ?></td>
            <td bgcolor="#FFFFCC" width="30"  style="padding: 0.2rem"><?= h(${"tani_".$i}) ?></td>
            <td bgcolor="#FFFFCC" width="30"  style="padding: 0.2rem"><?= h(${"tanka_".$i}) ?></td>
            <td bgcolor="#FFFFCC" width="30"  style="padding: 0.2rem"><?= h(${"price_".$i}) ?></td>
            <td bgcolor="#FFFFCC" width="100"  style="padding: 0.2rem"><?= h(${"bik_".$i}) ?></td>
          </tr>

          <?= $this->Form->control('pro_'.$i, array('type'=>'hidden', 'value'=>${"pro_".$i}, 'label'=>false)) ?>
          <?= $this->Form->control('amount_'.$i, array('type'=>'hidden', 'value'=>${"amount_".$i}, 'label'=>false)) ?>
          <?= $this->Form->control('tani_'.$i, array('type'=>'hidden', 'value'=>${"tani_".$i}, 'label'=>false)) ?>
          <?= $this->Form->control('tanka_'.$i, array('type'=>'hidden', 'value'=>${"tanka_".$i}, 'label'=>false)) ?>
          <?= $this->Form->control('price_'.$i, array('type'=>'hidden', 'value'=>${"price_".$i}, 'label'=>false)) ?>
          <?= $this->Form->control('bik_'.$i, array('type'=>'hidden', 'value'=>${"bik_".$i}, 'label'=>false)) ?>

      <?php endfor;?>

    </table>

        <br>

        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
        <tr bgcolor="#E6FFFF" >
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td width="30"  style="border-style: none;"></td>
          <td align="left" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('登録'), array('name' => 'confirm')); ?></div></td>
        </tr>
        </table>
        <br>
        <br>

        <?= $this->Form->control('tuika', array('type'=>'hidden', 'value'=>$tuika, 'label'=>false)) ?>
        <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
        <?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$name, 'label'=>false)) ?>
        <?= $this->Form->control('furigana', array('type'=>'hidden', 'value'=>$furigana, 'label'=>false)) ?>
        <?= $this->Form->control('dateexcl', array('type'=>'hidden', 'value'=>$dateexcl, 'label'=>false)) ?>
        <?= $this->Form->control('datetouroku', array('type'=>'hidden', 'value'=>$datetouroku, 'label'=>false)) ?>
        <?= $this->Form->control('yuubin', array('type'=>'hidden', 'value'=>$yuubin, 'label'=>false)) ?>
        <?= $this->Form->control('address', array('type'=>'hidden', 'value'=>$address, 'label'=>false)) ?>
        <?= $this->Form->control('keisyou', array('type'=>'hidden', 'value'=>$keisyou, 'label'=>false)) ?>
