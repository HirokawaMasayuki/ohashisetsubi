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

        <?php if($customercheck == 1): ?>

        <?= $this->Form->create($uriages, ['url' => ['action' => 'uriageformsyousai']]) ?>
        <br>
        <legend align="center"><strong style="font-size: 14pt"><?= __("詳細入力") ?></strong></legend>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">宛先</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">日付</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($namehyouji) ?></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= $this->Form->input("date", array('type' => 'date', 'monthNames' => false, 'label'=>false, 'value'=>date('Y-m-d H:i:s', strtotime('+9hour')))); ?></div></td>
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
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('yuubin', array('type'=>'text', 'label'=>false, 'value'=>$yuubin)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('address', array('type'=>'text', 'label'=>false, 'value'=>$address, 'size'=>38)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('keisyou', array('type'=>'text', 'label'=>false, 'value'=>$keisyou, 'size'=>3)) ?></td>
          </tr>
        </table>
        <br>
        <br>
        <legend align="center"><strong style="font-size: 10pt"><?= "単価は税抜きで入力してください。" ?></strong></legend>
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
          <tr>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('pro_1', array('type'=>'text', 'label'=>false)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('amount_1', array('type'=>'text', 'label'=>false, 'size'=>3)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('tani_1', array('type'=>'text', 'label'=>false, 'size'=>3)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('tanka_1', array('type'=>'text', 'label'=>false, 'size'=>3)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><strong style="font-size: 9pt"><?= h("自動計算") ?></strong></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('bik_1', array('type'=>'text', 'label'=>false)) ?></td>
          </tr>
        </table>
        <?= $this->Form->control('num', array('type'=>'hidden', 'value'=>1, 'label'=>false)) ?>

      <?php for ($i=2;$i<=$tuika;$i++): ?>

        <table align="center">
          <tr>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('pro_'.$i, array('type'=>'text', 'label'=>false)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('amount_'.$i, array('type'=>'text', 'label'=>false, 'size'=>3)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('tani_'.$i, array('type'=>'text', 'label'=>false, 'size'=>3)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('tanka_'.$i, array('type'=>'text', 'label'=>false, 'size'=>3)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><strong style="font-size: 9pt"><?= h("自動計算") ?></strong></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('bik_'.$i, array('type'=>'text', 'label'=>false)) ?></td>
          </tr>
        </table>
        <?= $this->Form->control('num', array('type'=>'hidden', 'value'=>$i, 'label'=>false)) ?>

      <?php endfor;?>

      <table align="right" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
      <tr bgcolor="#E6FFFF" >
        <td align="right" rowspan="2" width="50" bgcolor="#E6FFFF" style="border: none"><div align="right"><?= $this->Form->submit(__('行追加'), array('name' => 'tuika')); ?></div></td>
        <td width="200"  style="border-style: none;"></td>
      </tr>
      </table>
      <br>
      <br>

        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
        <tr bgcolor="#E6FFFF" >
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td width="30"  style="border-style: none;"></td>
          <td align="left" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('確認'), array('name' => 'confirm')); ?></div></td>
        </tr>
        </table>
        <br>
        <br>

        <?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$namehyouji, 'label'=>false)) ?>
        <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
        <?= $this->Form->control('furigana', array('type'=>'hidden', 'value'=>$furigana, 'label'=>false)) ?>

      <?php else: ?>

        <br>
        <legend align="center"><strong style="font-size: 11pt; color:red"><?= "正しい顧客名が入力されていません。前の画面からやりなおしてください。" ?></strong></legend>
        <br>
        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
        <tr bgcolor="#E6FFFF" >
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        </tr>
        </table>
        <br>
        <br>

      <?php endif; ?>
