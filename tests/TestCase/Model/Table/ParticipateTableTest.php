<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ParticipateTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ParticipateTable Test Case
 */
class ParticipateTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ParticipateTable
     */
    public $Participate;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.participate'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Participate') ? [] : ['className' => ParticipateTable::class];
        $this->Participate = TableRegistry::get('Participate', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Participate);

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
