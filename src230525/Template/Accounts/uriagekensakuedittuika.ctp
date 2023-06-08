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
        <?= $this->Form->create($uriages, ['url' => ['action' => 'uriagekensakuedittuika']]) ?>

        <br>
        <legend align="center"><strong style="font-size: 14pt"><?= __("編集") ?></strong></legend>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="280" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">宛先</strong></td>
            <td align="center" width="180" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">日付</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">分類</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">税別・内税</strong></td>
        </tr>
          <tr>
            <td align="center" width="280"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($customer) ?></td>
            <td align="center" width="180"  bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input("syutsuryokubi", array('type' => 'date', 'monthNames' => false, 'value'=>$syutsuryokubisyousai, 'label'=>false)); ?></td>
            <td align="center" width="100"  bgcolor="#FFFFCC" style="padding: 0.2rem">
              <?= $this->Form->input("bunrui", ["type"=>"select", "options"=>$arrBunrui, 'value'=>$bunrui, 'label'=>false]) ?>
            </td>
            <td align="center" width="100"  bgcolor="#FFFFCC" style="padding: 0.2rem">
              <?= $this->Form->input("tax_include_flag", ["type"=>"select", "options"=>$arrTax, 'value'=>$tax_include_flag, 'label'=>false]) ?>
            </td>
          </tr>
        </table>
        <br>
        <table align="center">
          <tr>
            <td align="center" width="200" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">郵便番号</strong></td>
            <td align="center" width="400" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">住所</strong></td>
            <td align="center" width="80" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">敬称</strong></td>
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
            <td align="center" width="50" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">伝票番号</strong></td>
            <td align="center" width="50" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">修正前合計金額（円）</strong></td>
          </tr>
          <tr>
            <td  align="center" width="280" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h($denpyou_num) ?></td>
            <td  align="center" width="280" bgcolor="#FFFFCC" style="padding: 0.2rem"><?= h(number_format($totalprice_moto)) ?></td>
          </tr>
        </table>

        <br>
        <legend align="center"><strong style="font-size: 10pt"><?= "※単価は税抜きで入力してください。　　　　　　　　　　　　　　　　　　　　　" ?></strong></legend>
        <legend align="center"><strong style="font-size: 10pt"><?= "※空の行を入れたい場合は、品名に全角スペースを入力してください。　　　　　　" ?></strong></legend>
        <legend align="center"><strong style="font-size: 10pt"><?= "※不要な行は、全データを入力後に「削除」にチェックを入れてください。　　　　" ?></strong></legend>
        <legend align="center"><strong style="font-size: 10pt"><?= "※２０文字以上の品名は請求書のエクセルのセル内に入りきりません。　　　　　　" ?></strong></legend>
        <legend align="center"><strong style="font-size: 10pt"><?= "※「順番」を変更する場合は全データを入力後に変更してください。（小数入力可）" ?></strong></legend>
        <legend align="center"><strong style="font-size: 10pt"><?= "例…「3」を「1.5」に変更すれば「1」と「2」の間に順番を変えられます。　　　  " ?></strong></legend>
        <br>

        <table align="center">
          <tr>
            <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->control('delete_flag_all', array('type'=>'checkbox', 'label'=>false)) ?></td>
            <td align="center" width="450" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">以下のデータをすべて削除する場合はチェックを入れてください</strong></td>
          </tr>
        </table>
        <br>

        <table align="center">
          <tr>
            <td align="center" width="20" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">順番</strong></td>
            <td align="center" width="150" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">品名（現場名）</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">数量</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">単位</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">単価</strong></td>
            <td align="center" width="100" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">日付（備考）</strong></td>
            <td align="center" width="30" bgcolor="#FFFFCC" style="font-size: 12pt;padding: 0.2rem"><strong style="font-size: 11pt">削除</strong></td>
          </tr>

      <?php for ($i=0;$i<count($Uriagesyousais);$i++): ?>

              <tr>
                <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('num_'.$i, array('type'=>'text', 'value'=>$i+1, 'label'=>false, 'size'=>1, 'pattern'=>"^[0-9.]+$", 'title'=>"半角数字で入力して下さい。", 'required'=>true)) ?></td>
                <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('pro_'.$i, array('type'=>'text', 'label'=>false, 'value'=>$Uriagesyousais[$i]["pro"])) ?></td>
                <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('amount_'.$i, array('type'=>'text', 'label'=>false, 'size'=>3, 'value'=>$Uriagesyousais[$i]["amount"])) ?></td>
                <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('tani_'.$i, array('type'=>'text', 'label'=>false, 'size'=>3, 'value'=>$Uriagesyousais[$i]["tani"])) ?></td>
                <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('tanka_'.$i, array('type'=>'text', 'label'=>false, 'size'=>3, 'value'=>$Uriagesyousais[$i]["tanka"])) ?></td>
                <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->input('bik_'.$i, array('type'=>'text', 'label'=>false, 'value'=>$Uriagesyousais[$i]["bik"])) ?></td>
                <td bgcolor="#FFFFCC" style="padding: 0.2rem"><?= $this->Form->control('delete_flag'.$i, array('type'=>'checkbox', 'label'=>false)) ?></td>
              </tr>

              <?= $this->Form->control('num', array('type'=>'hidden', 'value'=>$i, 'label'=>false)) ?>
              <?= $this->Form->control('uriagesyousaiId'.$i, array('type'=>'hidden', 'value'=>$Uriagesyousais[$i]["id"], 'label'=>false)) ?>
              <?= $this->Form->control('Uriagetotalmoto', array('type'=>'hidden', 'value'=>$Uriagetotalmoto, 'label'=>false)) ?>

      <?php endfor;?>

    </table>
    <br>
    <table align="right" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
    <tr bgcolor="#E6FFFF" >
      <td style="border-style: none;"><div align="center"><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td align="right" rowspan="2" width="20" bgcolor="#E6FFFF" style="border: none">　　</td>
      <td align="right" rowspan="2" width="50" bgcolor="#E6FFFF" style="border: none"><div align="right"><?= $this->Form->submit(__('前回個別検索'), array('name' => 'zenkai1')); ?></div></td>
      <td align="right" rowspan="2" width="20" bgcolor="#E6FFFF" style="border: none">　　</td>
      <td align="right" rowspan="2" width="50" bgcolor="#E6FFFF" style="border: none"><div align="right"><?= $this->Form->submit(__('行追加'), array('name' => 'zenkai4')); ?></div></td>
      <td align="right" rowspan="2" width="20" bgcolor="#E6FFFF" style="border: none">　　　　</td>
      <td width="200"  style="border-style: none;"></td>
    </tr>
    </table>

    <br><br><br>

    <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
    <tr bgcolor="#E6FFFF" >
      <td align="left" rowspan="2" width="30" bgcolor="#E6FFFF" style="border: none"><div align="center"><?= $this->Form->submit(__('確認'), array('name' => 'confirm')); ?></div></td>
    </tr>
    </table>
    <br>
    <br>
    <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
