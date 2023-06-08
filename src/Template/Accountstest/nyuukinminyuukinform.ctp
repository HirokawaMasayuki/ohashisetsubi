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
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/nyuukinsyoukai.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <table style="margin-bottom:0px" width="750" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/zandaka.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($nyuukins, ['url' => ['controller' => 'nyuukins', 'action' => 'nyuukinzandaka']]) ?>
        <br>

<table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
  <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: 0px;border-width: 1px">
    <tr style="border-bottom: 0px;border-width: 0px">
      <td style='border-bottom: 0px;border-width: 1px'  bgcolor="#FFFFCC" colspan="80"  height='50'  nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">請求日絞り込み</strong></div></td>
    </tr>

    <td align="center"  width="200"  hight="160" colspan="30" style="border-bottom: 0px;border-width: 1px"><?= $this->Form->input("date_sta_y", ["type"=>"select","empty"=>"選択してください", "options"=>$arrYear,'value'=>date('Y'),'label'=>false]) ?>年</td>
    <td align="center"  width="200"  hight="160" colspan="30" style="border-bottom: 0px;border-width: 1px"><?= $this->Form->input("date_sta_m", ["type"=>"select","empty"=>"選択してください", "options"=>$arrMonth,'value'=>date('n'), 'label'=>false]) ?>月</td>
  </table>
  <br>



  <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
    <tr bgcolor="#E6FFFF" >
      <td align="center" rowspan="2"  colspan="20" width="250" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('検索'), array('name' => 'kensaku')); ?></div></td>
    </tr>
  </table>
  </fieldset>

  <?=$this->Form->end() ?>
