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

        <hr size="1" style="margin: 0.5rem">
        <table style="margin-bottom:0px" width="750" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/shinki.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Shinkies','action'=>'index')));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <table style="margin-bottom:0px" width="750" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr style="border-style: none; background-color: #E6FFFF">
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/gaityuu.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Customers','action'=>'gaityumenu')));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <table style="margin-bottom:0px" width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
                  <tr style="border-style: none; background-color: #E6FFFF">
                    <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/syuusei.png',array('width'=>'105','height'=>'36'));?></td>
                  </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($suppliers, ['url' => ['action' => 'gaityueditsyuusei']]) ?>
      <br>
        <legend align="center"><strong style="font-size: 14pt"><?= __("外注先情報修正") ?></strong></legend>
        <br>

<table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
  <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: solid;border-width: 1px">
        <thead>
          <tr>
            <td align="center" width="450" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">外注先</strong></td>
            <td align="center" width="150" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">支店</strong></td>
            <td align="center" width="50" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt"></strong></td>
          </tr>
        </thead>
        <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
          <?php foreach ($Suppliers as $Suppliers): ?>
          <tr>
            <td align="center" width="450" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><?= h($Suppliers->name) ?></td>
            <td align="center" width="150" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><?= h($Suppliers->siten) ?></td>
            <?php
            echo "<td colspan='20' nowrap='nowrap'><div align='center'>";
            echo $this->Form->submit("修正" , ['action'=>'hensyu', 'name' => $Suppliers->id]) ;
            echo "</div></td>";
            ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
    </table>
<br><br>
