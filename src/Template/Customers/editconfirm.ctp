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
                    <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/syuusei.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Customers','action'=>'editichiran')));?></td>
                  </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($Customer, ['url' => ['action' => 'editdo']]) ?>
        <br>
        <legend align="center"><strong style="font-size: 14pt;"><?= __($mess) ?></strong></legend>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">顧客名</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">フリガナ（カタカナで入力）</strong></td>
          </tr>
          <tr>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($this->request->getData('name')) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($this->request->getData('furigana')) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">支店</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">住所</strong></td>
          </tr>
          <tr>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($this->request->getData('siten')) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($this->request->getData('address')) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">電話</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">FAX</strong></td>
          </tr>
          <tr>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($this->request->getData('tel')) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($this->request->getData('fax')) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">郵便番号</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">締め日</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($this->request->getData('yuubin')) ?></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="border-right-style: none;border-left-style: none;padding: 0.2rem"><?= h($this->request->getData('simebi')."日") ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="560" colspan="2" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">必着日</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($hittyaku_flag) ?></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="border-right-style: none;border-left-style: none;padding: 0.2rem"><?= h($this->request->getData('hittyakubi')."日") ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="560" colspan="2" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金日</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($nyuukin_flag) ?></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="border-right-style: none;border-left-style: none;padding: 0.2rem"><?= h($this->request->getData('nyuukinbi')."日") ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">敬称</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">回収方法</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($keisyou) ?></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($kaisyuu) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">担当者</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">担当者連絡先</strong></td>
          </tr>
          <tr>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($this->request->getData('tantou')) ?></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($this->request->getData('tantou_tel')) ?></td>
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

                <br>
                <legend align="center"><strong style="font-size: 9pt;color: red"><?= __("※顧客名とフリガナ以外の項目は空白のまま登録できます。") ?></strong></legend>
                <br>

    </td>
  </tr>
</table>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$this->request->getData('id'), 'label'=>false)) ?>
<?= $this->Form->control('delete_flag', array('type'=>'hidden', 'value'=>$this->request->getData('delete_flag'), 'label'=>false)) ?>
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$this->request->getData('name'), 'label'=>false)) ?>
<?= $this->Form->control('furigana', array('type'=>'hidden', 'value'=>$this->request->getData('furigana'), 'label'=>false)) ?>
<?= $this->Form->control('siten', array('type'=>'hidden', 'value'=>$this->request->getData('siten'), 'label'=>false)) ?>
<?= $this->Form->control('address', array('type'=>'hidden', 'value'=>$this->request->getData('address'), 'label'=>false)) ?>
<?= $this->Form->control('yuubin', array('type'=>'hidden', 'value'=>$this->request->getData('yuubin'), 'label'=>false)) ?>
<?= $this->Form->control('tel', array('type'=>'hidden', 'value'=>$this->request->getData('tel'), 'label'=>false)) ?>
<?= $this->Form->control('fax', array('type'=>'hidden', 'value'=>$this->request->getData('fax'), 'label'=>false)) ?>
<?= $this->Form->control('simebi', array('type'=>'hidden', 'value'=>$this->request->getData('simebi'), 'label'=>false)) ?>
<?= $this->Form->control('hittyakubi', array('type'=>'hidden', 'value'=>$this->request->getData('hittyakubi'), 'label'=>false)) ?>
<?= $this->Form->control('nyuukinbi', array('type'=>'hidden', 'value'=>$this->request->getData('nyuukinbi'), 'label'=>false)) ?>
<?= $this->Form->control('tantou', array('type'=>'hidden', 'value'=>$this->request->getData('tantou'), 'label'=>false)) ?>
<?= $this->Form->control('tantou_tel', array('type'=>'hidden', 'value'=>$this->request->getData('tantou_tel'), 'label'=>false)) ?>
<?= $this->Form->control('keisyou', array('type'=>'hidden', 'value'=>$this->request->getData('keisyou'), 'label'=>false)) ?>
<?= $this->Form->control('hittyaku_flag', array('type'=>'hidden', 'value'=>$this->request->getData('hittyaku_flag'), 'label'=>false)) ?>
<?= $this->Form->control('nyuukin_flag', array('type'=>'hidden', 'value'=>$this->request->getData('nyuukin_flag'), 'label'=>false)) ?>
<?= $this->Form->control('kaisyuu', array('type'=>'hidden', 'value'=>$this->request->getData('kaisyuu'), 'label'=>false)) ?>
<?= $this->Form->control('created_at', array('type'=>'hidden', 'value'=>date('Y-m-d H:i:s', strtotime('+9hour')), 'label'=>false)) ?>
