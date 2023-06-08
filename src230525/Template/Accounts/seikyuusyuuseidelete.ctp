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
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/seikyuuitiran.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <table style="margin-bottom:0px" width="750" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/seikyuuzumi.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">
        <?= $this->Form->create($nyuukins, ['url' => ['action' => 'seikyuurirekiseikyuuzumiitiran']]) ?>
  <br>
  <legend align="center"><strong style="font-size: 11pt"><?= __("以下の請求を取り消しました。") ?></strong></legend>
  <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">宛先</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">期間</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($name) ?></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($month."月分") ?></td>
          </tr>
        </table>
        <br>

        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
          <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: 0px;border-width: 1px">
                <thead>
                    <tr border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
                      <td width="150" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">伝票番号</strong></div></td>
                      <td width="150" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">売上日</strong></div></td>
                      <td width="150" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">請求日</strong></div></td>
                      <td width="150" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">請求額</strong></div></td>
                      <td width="150" height="30" colspan="20" nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">税込</strong></div></td>
                    </tr>
                </thead>
                <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
                  <?php for ($i=0;$i<count($arrSeikyuus);$i++): ?>

                    <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($arrSeikyuus[$i]["denpyou_num"]) ?></font></td>
                    <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($arrSeikyuus[$i]["uriagebi"]) ?></font></td>
                    <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h($arrSeikyuus[$i]["seikyuubi"]) ?></font></td>
                    <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h(number_format($arrSeikyuus[$i]["kingaku"])." 円") ?></font></td>
                    <?php if($arrSeikyuus[$i]["tax_include_flag"] == 1): ?>
                      <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h("内税") ?></font></td>
                    <?php else: ?>
                      <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap"><font><?= h(number_format($arrSeikyuus[$i]["kingaku"]*1.1)." 円") ?></font></td>
                    <?php endif; ?>
                  </tr>
                <?php endfor;?>
                </tbody>
            </table>
            <br>
            <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
            <tr bgcolor="#E6FFFF" >
              <td align="left" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('請求済一覧へ'), array('name' => 'confirm')); ?></div></td>
            </tr>
            </table>
            <br>
            <?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
            <?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>

    <br>
