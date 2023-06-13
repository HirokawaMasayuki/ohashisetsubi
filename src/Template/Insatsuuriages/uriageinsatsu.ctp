<?php
$this->layout = '';
?>

<table width="1400" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#E6FFFF">
  <tr style="background-color: #E6FFFF">
    <td>
      <table width="1400" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
        <tr style="border-style: none; background-color: #E6FFFF">
          <td bgcolor="#E6FFFF">
            </body>
          </td>
        </tr>
      </table>
      <br>
      <table style="margin-bottom:0px" width="1400" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
        <tr style="border-style: none; background-color: #E6FFFF">
          <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/top.png', array('width' => '105', 'height' => '36', 'url' => array('controller' => 'Accounts', 'action' => 'index'))); ?></td>
          <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/shinki.png', array('width' => '105', 'height' => '36', 'url' => array('controller' => 'Shinkies', 'action' => 'index'))); ?></td>
        </tr>
      </table>

      <hr size="2" style="margin: 0.5rem">

      <table style="margin-bottom:0px" width="1200" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
        <tr style="border-style: none; background-color: #E6FFFF">
          <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/uriagesyoukai.png', array('width' => '105', 'height' => '36')); ?></td>
        </tr>
      </table>

      <hr size="2" style="margin: 0.5rem">

      <table style="margin-bottom:0px" width="1400" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
        <tr style="border-style: none; background-color: #E6FFFF">
          <td style="padding: 0.1rem 0.1rem;text-align : center"><?php echo $this->Html->image('menu/uriagesyuturyoku.png', array('width' => '105', 'height' => '36')); ?></td>
        </tr>
      </table>

      <hr size="1" style="margin: 0.5rem">

      <?= $this->Form->create($uriages, ['url' => ['action' => 'uriageinsatsu']]) ?>
      <br>
      <legend align="center"><strong style="font-size: 10pt;color: red"><?= h($mess) ?></strong></legend>
      <br>

      <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
        <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: 0px;border-width: 1px">
          <tr style="border-bottom: 0px;border-width: 0px">
            <td style='border-bottom: 0px;border-width: 1px' width="200" height="40" colspan="40" nowrap="nowrap">
              <div align="center"><strong style="font-size: 15pt;">伝票番号</strong></div>
            </td>
            <td style='border-bottom: 0px;border-width: 1px' width="250" colspan="40" nowrap="nowrap">
              <div align="center"><strong style="font-size: 15pt">売上日</strong></div>
            </td>
          </tr>

          <?php
          echo "<tr style='border-bottom: 0px;border-width: 0px'>\n";
          ?>

          <?php
          echo "<td width='50' colspan='3' style='border-bottom: 0px'><div align='center'><strong style='font-size: 11pt; color:blue'>\n";
          echo "開始";
          echo "</strong></div></td>\n";
          ?>
          <td width="150" colspan="37" style="border-bottom: 0px;border-width: 1px">
            <div align="center">
              <?= h($denpyou_num_sta) ?>
            </div>
          </td>

          <?php
          echo "<td width='50' colspan='3' style='border-bottom: 0px'><div align='center'><strong style='font-size: 11pt; color:blue'>\n";
          echo "開始";
          echo "</strong></div></td>\n";
          ?>

          <td colspan="37" style="border-bottom: 0px;border-width: 1px">
            <div align="center">
              <?= h($date_sta) ?>
            </div>
          </td>

          <?php
          echo "<tr style='border-bottom: 0px;border-width: 0px'>\n";
          echo "<td colspan='3' style='border-bottom: 0px;border-width: 1px'><div align='center'><strong style='font-size: 11pt; color:blue'>\n";
          echo "終了";
          echo "</strong></div></td>\n";
          ?>
          <td width="150" colspan="37" style="border-bottom: 0px;border-width: 1px">
            <div align="center">
              <?= h($denpyou_num_fin) ?>
            </div>
          </td>

          <?php
          echo "<td colspan='3' style='border-bottom: 0px;border-width: 1px'><div align='center'><strong style='font-size: 11pt; color:blue'>\n";
          echo "終了";
          echo "</strong></div></td>\n";
          ?>
          <td colspan="37" style="border-bottom: 0px;border-width: 1px">
            <div align="center">
              <?= h($date_fin) ?>
            </div>
          </td>
          <?php
          echo "</tr>\n";
          ?>

      </table>
      <br>
      <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
        <tr bgcolor="#E6FFFF">
          <td style="border-style: none;">
            <div align="center"><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div>
          </td>
          <td width="40"></td>
          <td style="border-style: none;">
            <div align="center"><?= $this->Form->submit('エクセル出力', array('name' => 'output')); ?></div>
          </td>
        </tr>
      </table>
      </fieldset>
      <br>

      <?= $this->Form->control('denpyou_num_sta', array('type' => 'hidden', 'value' => $denpyou_num_sta, 'label' => false)) ?>
      <?= $this->Form->control('denpyou_num_fin', array('type' => 'hidden', 'value' => $denpyou_num_fin, 'label' => false)) ?>
      <?= $this->Form->control('date_sta', array('type' => 'hidden', 'value' => $date_sta, 'label' => false)) ?>
      <?= $this->Form->control('date_fin', array('type' => 'hidden', 'value' => $date_fin, 'label' => false)) ?>

      <br>

      <table align="center" border="2" bordercolor="#E6FFFF" cellpadding="0" cellspacing="0">
        <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC" style="border-bottom: 0px;border-width: 1px">
          <thead>
            <tr border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
              <td width="80" height="30" colspan="20" nowrap="nowrap">
                <div align="center"><strong style="font-size: 11pt">伝票番号</strong></div>
              </td>
              <td width="120" height="30" colspan="20" nowrap="nowrap">
                <div align="center"><strong style="font-size: 11pt">売上日</strong></div>
              </td>
              <td width="160" height="30" colspan="20" nowrap="nowrap">
                <div align="center"><strong style="font-size: 11pt">顧客</strong></div>
              </td>
              <td width="80" height="30" colspan="20" nowrap="nowrap">
                <div align="center"><strong style="font-size: 11pt">行No.</strong></div>
              </td>
              <td height="30" colspan="20" nowrap="nowrap">
                <div align="center"><strong style="font-size: 11pt">品名（現場名）</strong></div>
              </td>
              <td width="50" height="30" colspan="20" nowrap="nowrap">
                <div align="center"><strong style="font-size: 11pt">分類</strong></div>
              </td>
              <td width="50" height="30" colspan="20" nowrap="nowrap">
                <div align="center"><strong style="font-size: 13pt">数量</strong></div>
              </td>
              <td width="50" height="30" colspan="20" nowrap="nowrap">
                <div align="center"><strong style="font-size: 13pt">単位</strong></div>
              </td>
              <td width="100" height="30" colspan="20" nowrap="nowrap">
                <div align="center"><strong style="font-size: 13pt">単価</strong></div>
              </td>
              <td width="100" height="30" colspan="20" nowrap="nowrap">
                <div align="center"><strong style="font-size: 13pt">金額</strong></div>
              </td>
              <td width="150" height="30" colspan="20" nowrap="nowrap">
                <div align="center"><strong style="font-size: 11pt">日付（備考）</strong></div>
              </td>
            </tr>
          </thead>
        <tbody border="2" bordercolor="#E6FFFF" bgcolor="#FFFFCC">
          <?php foreach ($Uriages as $Uriages) : ?>
            <tr>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                <font><?= h($Uriages->delete_flag) ?></font>
              </td>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                <font><?= h($Uriages->uriagebi->format('Y-m-d')) ?></font>
              </td>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                <font><?= h($Uriages->created_at) ?></font>
              </td>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                <font><?= h($Uriages->num) ?></font>
              </td>
              <?php if ($Uriages->zeiritu == 8) : ?>
                <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                  <font><?= h($Uriages->pro) ?>※</font>
                </td>
              <?php else : ?>
                <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                  <font><?= h($Uriages->pro) ?></font>
                </td>
              <?php endif; ?>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                <font><?= h($Uriages->id) ?></font>
              </td>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                <font><?= h($Uriages->amount) ?></font>
              </td>
              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                <font><?= h($Uriages->tani) ?></font>
              </td>

              <?php if ($Uriages->tanka > 0) : ?>
                <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                  <font><?= h(number_format($Uriages->tanka)) ?></font>
                </td>
              <?php else : ?>
                <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                  <font></font>
                </td>
              <?php endif; ?>

              <?php if ($Uriages->price > 0) : ?>
                <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                  <font><?= h(number_format($Uriages->price)) ?></font>
                </td>
              <?php else : ?>
                <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                  <font></font>
                </td>
              <?php endif; ?>

              <td style="border-bottom: 0px;border-width: 1px" align="center" colspan="20" nowrap="nowrap">
                <font><?= h($Uriages->bik) ?></font>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <br>

      <?= $this->Form->end() ?>