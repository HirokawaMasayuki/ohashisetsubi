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
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/nyuukinsyoukai.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="2" style="margin: 0.5rem">

        <?= $this->Form->create($nyuukins, ['url' => ['action' => 'nyuukinsyoukaieditdo']]) ?>
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
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">取引先名</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金予定日</strong></td>
          </tr>
          <tr>
            <td align="center"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($this->request->getData('customer')) ?></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($this->request->getData('nyuukinyotei')."日") ?></div></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">請求年月日</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">請求額（円）</strong></td>
          </tr>
          <tr>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($date_seikyuu) ?></div></td>
            <td align="center" bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= h($totalseikyuu) ?></div></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">種別</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">集金・振込</strong></td>
          </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input("syubetu", ["type"=>"select","empty"=>"選択してください", "options"=>$arrSyubetu, 'label'=>false, 'value'=>$this->request->getData('syubetu'), 'required'=>true]) ?></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input("syuukinfurikomi", ["type"=>"select","empty"=>"選択してください", "options"=>$arrSyuukinfurikomi, 'label'=>false, 'value'=>$this->request->getData('syuukinfurikomi'), 'required'=>true]) ?></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金額（円）</strong></td>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">入金日</strong></td>
          </tr>
          <tr>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('nyuukinngaku', array('type'=>'text', 'label'=>false, 'size'=>38)) ?></td>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><div align="center"><?= $this->Form->input("datenyuukin", array('type' => 'date', "empty"=>" ", 'monthNames' => false, 'label'=>false, 'value'=>$datenyuukin)); ?></div></td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="560" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">備考</strong></td>
          </tr>
          <tr>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('bik', array('type'=>'text', 'label'=>false, 'size'=>80)) ?></td>
          </tr>
        </table>
        <br>


        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
        <tr bgcolor="#E6FFFF" >
          <td align="left" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('編集確定'), array('name' => 'confirm')); ?></div></td>
        </tr>
        </table>


    </td>
  </tr>
</table>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
<?= $this->Form->control('customer', array('customer'=>'hidden', 'value'=>$this->request->getData('customer'), 'label'=>false)) ?>
