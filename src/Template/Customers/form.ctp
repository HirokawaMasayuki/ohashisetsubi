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
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/kokyaku.png',array('width'=>'105','height'=>'36','url'=>array('controller'=>'Customers','action'=>'menu')));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <table style="margin-bottom:0px" width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
                  <tr style="border-style: none; background-color: #E6FFFF">
                    <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/touroku.png',array('width'=>'105','height'=>'36'));?></td>
                  </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($customers, ['url' => ['action' => 'confirm']]) ?>
        <br>
        <legend align="center"><strong style="font-size: 14pt"><?= __("顧客登録") ?></strong></legend>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">顧客名</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">フリガナ（カタカナで入力）</strong></td>
          </tr>
          <tr>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('name', array('type'=>'text', 'label'=>false, 'size'=>38, 'required'=>true)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('furigana', array('type'=>'text', 'label'=>false, 'size'=>38, 'required'=>true)) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">支店</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">住所</strong></td>
          </tr>
          <tr>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('siten', array('type'=>'text', 'label'=>false, 'size'=>38)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('address', array('type'=>'text', 'label'=>false, 'size'=>38)) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">電話</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">FAX</strong></td>
          </tr>
          <tr>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('tel', array('type'=>'text', 'label'=>false, 'size'=>38)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('fax', array('type'=>'text', 'label'=>false, 'size'=>38)) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">郵便番号</strong></td>
            <td align="center" width="280" colspan="3" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">締め日</strong></td>
          </tr>
          <tr>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('yuubin', array('type'=>'text', 'label'=>false, 'size'=>38)) ?></td>
            <td width="40" bgcolor="#FFFFCC" style="border-right-style: none;padding: 0.2rem"><strong style="font-size: 11pt"></strong></td>
            <td width="120" bgcolor="#FFFFCC" style="border-right-style: none;border-left-style: none;padding: 0.2rem"><?= $this->Form->control('simebi', array('type'=>'text', 'label'=>false, 'size'=>20)) ?></td>
            <td align="center" width="40" bgcolor="#FFFFCC" style="border-left-style: none;padding: 0.2rem"><strong style="font-size: 11pt">日</strong></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="560" colspan="4" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">必着日</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input("hittyaku_flag", ["type"=>"select","empty"=>"選択してください", "options"=>$arrMonth, 'label'=>false]) ?></td>
            <td width="40" bgcolor="#FFFFCC" style="border-right-style: none;padding: 0.2rem"><strong style="font-size: 11pt"></strong></td>
            <td width="120" bgcolor="#FFFFCC" style="border-right-style: none;border-left-style: none;padding: 0.2rem"><?= $this->Form->control('hittyakubi', array('type'=>'text', 'label'=>false, 'size'=>20)) ?></td>
            <td align="center" width="40" bgcolor="#FFFFCC" style="border-left-style: none;padding: 0.2rem"><strong style="font-size: 11pt">日</strong></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="560" colspan="4" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金日</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input("nyuukin_flag", ["type"=>"select","empty"=>"選択してください", "options"=>$arrMonth, 'label'=>false]) ?></td>
            <td width="40" bgcolor="#FFFFCC" style="border-right-style: none;padding: 0.2rem"><strong style="font-size: 11pt"></strong></td>
            <td width="120" bgcolor="#FFFFCC" style="border-right-style: none;border-left-style: none;padding: 0.2rem"><?= $this->Form->control('nyuukinbi', array('type'=>'text', 'label'=>false, 'size'=>20)) ?></td>
            <td align="center" width="40" bgcolor="#FFFFCC" style="border-left-style: none;padding: 0.2rem"><strong style="font-size: 11pt">日</strong></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">敬称</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">回収方法</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input("keisyou", ["type"=>"select","empty"=>"選択してください", "options"=>$arrKeisyou, 'label'=>false]) ?></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input("kaisyuu", ["type"=>"select","empty"=>"選択してください", "options"=>$arrKaisyuu, 'label'=>false]) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">担当者</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">担当者連絡先</strong></td>
          </tr>
          <tr>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('tantou', array('type'=>'text', 'label'=>false, 'size'=>38)) ?></td>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('tantou_tel', array('type'=>'text', 'label'=>false, 'size'=>38)) ?></td>
          </tr>
        </table>
        <br>

        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
        <tr bgcolor="#E6FFFF" >
          <td align="left" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('入力内容確認'), array('name' => 'confirm')); ?></div></td>
        </tr>
        </table>

                <br>
                <legend align="center"><strong style="font-size: 9pt;color: red"><?= __("※顧客名とフリガナ以外の項目は空白のまま登録できます。") ?></strong></legend>
                <br>


    </td>
  </tr>
</table>
