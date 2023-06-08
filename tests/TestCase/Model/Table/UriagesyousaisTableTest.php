<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UriagesyousaisTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UriagesyousaisTable Test Case
 */
class UriagesyousaisTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UriagesyousaisTable
     */
    public $Uriagesyousais;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.uriagesyousais'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Uriagesyousais') ? [] : ['className' => UriagesyousaisTable::class];
        $this->Uriagesyousais = TableRegistry::getTableLocator()->get('Uriagesyousais', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Uriagesyousais);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
