<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

use Cake\Http\CallbackStream;
use Cake\Http\Response;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
/**
 * Excel component
 */
class ExcelComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

        public function download(Spreadsheet $spreadsheet, string $filename)
    {
      $writer = new Xlsx($spreadsheet);
      $stream = new CallbackStream(function () use ($writer) {
        $writer->save('php://output');
      });
      $response = new Response();
      $encodedName = rawurlencode("{$filename}.xlsx");
      return $response->withType('xlsx')
        ->withHeader('Content-Disposition', "attachment;filename*=UTF-8''{$encodedName}")
        ->withBody($stream);
    }

}
