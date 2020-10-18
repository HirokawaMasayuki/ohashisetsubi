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
        <?= $this->Form->create($uriages, ['url' => ['action' => 'uriagekensakueditdo']]) ?>

        <br>
        <legend align="center"><strong style="font-size: 14pt"><?= __("編集") ?></strong></legend>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">宛先</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">日付</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($customer) ?></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($syutsuryokubi) ?></div></td>
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
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->control('delete_flag', array('type'=>'checkbox', 'label'=>false)) ?></td>
            <td align="center" width="320" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">削除する場合はチェックを入れてください</strong></td>
          </tr>
        </table>

        <br>

        <table align="center">
          <tr>
            <td align="center" width="150" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">品名（現場名）</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">数量</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">単位</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">単価</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">日付（備考）</strong></td>
          </tr>

      <?php for ($i=1;$i<=$count;$i++): ?>

              <tr>
                <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('pro_'.$i, array('type'=>'text', 'label'=>false, 'value'=>${"pro_".$i})) ?></td>
                <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('amount_'.$i, array('type'=>'text', 'label'=>false, 'size'=>3, 'value'=>${"amount_".$i})) ?></td>
                <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('tani_'.$i, array('type'=>'text', 'label'=>false, 'size'=>3, 'value'=>${"tani_".$i})) ?></td>
                <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('tanka_'.$i, array('type'=>'text', 'label'=>false, 'size'=>3, 'value'=>${"tanka_".$i})) ?></td>
                <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('bik_'.$i, array('type'=>'text', 'label'=>false, 'value'=>${"bik_".$i})) ?></td>
              </tr>

      <?php endfor;?>

    </table>

    <br>

    <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
    <tr bgcolor="#E6FFFF" >
      <td align="left" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('編集確定'), array('name' => 'confirm')); ?></div></td>
    </tr>
    </table>
    <br>
    <br>
    <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
