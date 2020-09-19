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
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/uriagesyori.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Accounts','action'=>'uriageformcustomer')));?></td>
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

        <?= $this->Form->create($customers, ['url' => ['action' => 'index']]) ?>
        <br>
        <legend align="center"><strong style="font-size: 14pt"><?= __("顧客登録") ?></strong></legend>
        <br>
        <legend align="center"><strong style="font-size: 9pt;color: red"><?= __($mes) ?></strong></legend>
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

    </td>
  </tr>
</table>
