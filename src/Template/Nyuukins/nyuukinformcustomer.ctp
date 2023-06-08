<?php
$this->layout = '';
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
$arrCustomer_list = json_encode($arrCustomer_list);//jsに配列を受け渡すために変換
?>

<script>

$(function() {
      // 入力補完候補の単語リスト
      let wordlist = <?php echo $arrCustomer_list; ?>
      // 入力補完を実施する要素に単語リストを設定
      $("#Customer_list").autocomplete({
        source: wordlist
      });
  });

</script>

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
            <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/nyuukinnyuuryoku.png',array('width'=>'105','height'=>'36'));?></td>
          </tr>
        </table>

        <hr size="1" style="margin: 0.5rem">

        <?= $this->Form->create($Nyuukins, ['url' => ['action' => 'nyuukinform']]) ?>
        <br>
        <legend align="center"><strong style="font-size: 12pt;color: black"><?= __("顧客名を選択または入力してください。") ?></strong></legend>
        <br>

        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
          <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: 0px;border-width: 1px">
            <tr style="border-bottom: 0px;border-width: 0px">
              <td style='border-bottom: 0px;border-width: 1px'  bgcolor="#FFFFCC" colspan="80"  height='50'  nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">顧客選択</strong></div></td>
            </tr>
          <td align="center"  width="300"  hight="160" colspan="30"  height='50' style="border-bottom: 0px;border-width: 1px"><?= $this->Form->input("customer_id", ["type"=>"select","empty"=>"選択してください", "options"=>$arrCustomernames,'value'=>date('Y'),'label'=>false]) ?></td>
        </table>
        <br>
        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
          <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: 0px;border-width: 1px">
            <tr style="border-bottom: 0px;border-width: 0px">
              <td style='border-bottom: 0px;border-width: 1px'  bgcolor="#FFFFCC" colspan="80"  height='50'  nowrap="nowrap"><div align="center"><strong style="font-size: 12pt">顧客入力（※選択した月に請求をしていない場合は入力）</strong></div></td>
            </tr>
            <td align="center"  width="300"  hight="160" colspan="30"  height='50' style="border-bottom: 0px;border-width: 1px"><?= $this->Form->input('customer_name', array('type'=>'text', 'label'=>false, 'id'=>"Customer_list", 'size'=>70)) ?></td>
        </table>
        <br>

        <?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
        <?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>

        <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
          <tr bgcolor="#E6FFFF" >
            <td align="center" rowspan="2"  colspan="20" width="250" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('次へ'), array('name' => 'kensaku')); ?></div></td>
          </tr>
        </table>
        </fieldset>

        <?=$this->Form->end() ?>
