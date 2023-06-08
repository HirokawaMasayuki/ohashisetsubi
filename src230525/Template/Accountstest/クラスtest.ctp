<legend align="center"><font color="red"><?= __($mes) ?></font></legend>

<?php
use App\myClass\Shinkimenus\htmlShinkimenu;//myClassフォルダに配置したクラスを使用
$htmlShinkimenu = new htmlShinkimenu();
$htmlaccountmenus = $htmlShinkimenu->topmenus();
?>
<hr size="5" style="margin: 0.5rem">
<table style="margin-bottom:0px" width="750" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
  <?php
       echo $htmlaccountmenus;
  ?>
</table>
<hr size="5" style="margin: 0.5rem">

<?php
/*
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

$reader = new XlsxReader();
$spreadsheet = $reader->load('奥さん.xlsx');
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'xxx');
$sheet->setCellValue('A2', 'yyy');

$writer = new XlsxWriter($spreadsheet);
$writer->save('奥さん.xlsx');
*/
?>
