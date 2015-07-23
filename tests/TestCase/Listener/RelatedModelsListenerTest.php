<?php
namespace Crud\Test\TestCase\Listener;

use Cake\Event\Event;
use Cake\ORM\Query;
use Crud\Event\Subject;
use Crud\TestSuite\TestCase;

/**
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 */
class RelatedModelListenerTest extends TestCase
{

    public $fixtures = ['plugin.crud.blogs', 'plugin.crud.tags'];
    public $autoFixtures = false;

    /**
     * testModels
     *
     * @return void
     */
    public function testModels()
    {
        $listener = $this
            ->getMockBuilder('\Crud\Listener\RelatedModelsListener')
            ->disableOriginalConstructor()
            ->setMethods(['relatedModels'])
            ->getMock();

        $listener
            ->expects($this->once())
            ->method('relatedModels')
            ->with(null, null)
            ->will($this->returnValue(null));

        $result = $listener->models();
        $expected = [];
        $this->assertEquals($expected, $result);
    }

    /**
     * testModelsEmpty
     *
     * Test behavior when 'relatedModels' is empty
     *
     * @return void
     */
    public function testModelsEmpty()
    {
        $listener = $this
            ->getMockBuilder('\Crud\Listener\RelatedModelsListener')
            ->disableOriginalConstructor()
            ->setMethods(['relatedModels'])
            ->getMock();

        $listener
            ->expects($this->once())
            ->method('relatedModels')
            ->with(null, null)
            ->will($this->returnValue([]));

        $result = $listener->models();
        $expected = [];
        $this->assertEquals($expected, $result);
    }

    /**
     * testModelsEmpty
     *
     * Test behavior when 'relatedModels' is a string
     *
     * @return void
     */
    public function testModelsString()
    {
        $listener = $this
            ->getMockBuilder('\Crud\Listener\RelatedModelsListener')
            ->disableOriginalConstructor()
            ->setMethods(['relatedModels', 'getAssociatedByName'])
            ->getMock();
        $listener
            ->expects($this->once())
            ->method('relatedModels')
            ->with(null)
            ->will($this->returnValue(['posts']));
        $listener
            ->expects($this->once())
            ->method('getAssociatedByName')
            ->with(['posts']);

        $listener->models();
    }

    /**
     * testModelsTrue
     *
     * Test behavior when 'relatedModels' is true
     *
     * @return void
     */
    public function testModelsTrue()
    {
        $listener = $this
            ->getMockBuilder('\Crud\Listener\RelatedModelsListener')
            ->disableOriginalConstructor()
            ->setMethods(['relatedModels', 'getAssociatedByType'])
            ->getMock();
        $listener
            ->expects($this->once())
            ->method('relatedModels')
            ->with(null, null)
            ->will($this->returnValue(true));
        $listener
            ->expects($this->once())
            ->method('getAssociatedByType')
            ->with(['oneToOne', 'manyToMany', 'manyToOne']);

        $result = $listener->models();
    }

    /**
     * testGetAssociatedByTypeReturnValue
     *
     * Test return value of `getAssociatedByType`
     *
     * @return void
     */
    public function testGetAssociatedByTypeReturnValue()
    {
        $listener = $this
            ->getMockBuilder('\Crud\Listener\RelatedModelsListener')
            ->disableOriginalConstructor()
            ->setMethods(['relatedModels', '_table'])
            ->getMock();
        $table = $this
            ->getMockBuilder('\Cake\ORM\Table')
            ->disableOriginalConstructor()
            ->setMethods(['associations'])
            ->getMock();
        $associationCollection = $this
            ->getMockBuilder('\Cake\ORM\AssociationCollection')
            ->disableOriginalConstructor()
            ->setMethods(['get', 'keys'])
            ->getMock();
        $association = $this
            ->getMockBuilder('\Cake\ORM\Association')
            ->disableOriginalConstructor()
            ->setMethods(['type', 'name', 'eagerLoader', 'cascadeDelete', 'isOwningSide', 'saveAssociated'])
            ->getMock();

        $listener
            ->expects($this->once())
            ->method('_table')
            ->withAnyParameters()
            ->will($this->returnValue($table));
        $table
            ->expects($this->atLeastOnce())
            ->method('associations')
            ->will($this->returnValue($associationCollection));
        $associationCollection
            ->expects($this->once())
            ->method('keys')
            ->will($this->returnValue(['posts']));
        $associationCollection
            ->expects($this->once())
            ->method('get')
            ->with('posts')
            ->will($this->returnValue($association));
        $association
            ->expects($this->once())
            ->method('name')
            ->will($this->returnValue('Posts'));
        $association
            ->expects($this->once())
            ->method('type')
            ->will($this->returnValue('oneToOne'));

        $expected = ['Posts' => $association];
        $result = $listener->getAssociatedByType(['oneToOne', 'manyToMany', 'manyToOne']);

        $this->assertEquals($expected, $result);
    }

    /**
     * testModelReturnsAssociationsByName
     *
     * Test return value of `getAssociatedByName`
     *
     * @return void
     */
    public function testGetAssociatedByNameReturnValue()
    {
        $listener = $this
            ->getMockBuilder('\Crud\Listener\RelatedModelsListener')
            ->disableOriginalConstructor()
            ->setMethods(['relatedModels', '_table'])
            ->getMock();
        $table = $this
            ->getMockBuilder('\Cake\ORM\Table')
            ->disableOriginalConstructor()
            ->setMethods(['associations'])
            ->getMock();
        $associationCollection = $this
            ->getMockBuilder('\Cake\ORM\AssociationCollection')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $association = $this
            ->getMockBuilder('\Cake\ORM\Association')
            ->disableOriginalConstructor()
            ->setMethods(['type', 'name', 'eagerLoader', 'cascadeDelete', 'isOwningSide', 'saveAssociated'])
            ->getMock();

        $listener
            ->expects($this->once())
            ->method('_table')
            ->withAnyParameters()
            ->will($this->returnValue($table));
        $table
            ->expects($this->once())
            ->method('associations')
            ->will($this->returnValue($associationCollection));
        $associationCollection
            ->expects($this->once())
            ->method('get')
            ->with('posts')
            ->will($this->returnValue($association));
        $association
            ->expects($this->once())
            ->method('name')
            ->with(null)
            ->will($this->returnValue('Posts'));

        $expected = ['Posts' => $association];
        $result = $listener->getAssociatedByName(['posts']);

        $this->assertEquals($expected, $result);
    }

    /**
     * Ensure that when related table are configured and Crud uses paginate that the tables are contained
     *
     */
    public function testContainingRelatedTables()
    {
        $this->markTestIncomplete('This test relies on having a return in the event.');

        $listener = $this
            ->getMockBuilder('\Crud\Listener\RelatedModelsListener')
            ->disableOriginalConstructor()
            ->setMethods(['relatedModels'])
            ->getMock();

        $listener
            ->expects($this->once())
            ->method('relatedModels')
            ->will($this->returnValue([
                'Users' => [
                    'Posts'
                ],
                'Tags'
            ]));

        $db = $this->getMockBuilder('\Cake\Database\Connection')
            ->disableOriginalConstructor()
            ->getMock();

        $query = new Query($db, null);

        $subject = new Subject(['query' => $query]);
        $event = new Event('beforePaginate', $subject);

        $result = $listener->beforePaginate($event);
        $contain = $result->query->contain();

        $this->assertNotNull($contain);
        $this->assertArrayHasKey('Users', $contain);
        $this->assertArrayHasKey('Tags', $contain);
        $this->assertArrayHasKey('Posts', $contain['Users']);
    }

    public function testRelatedTableLoading()
    {
        $controller = new \Crud\Test\App\Controller\BlogsController();
        $controller->Blogs->hasMany('Tags');

        $controller->Crud->config('actions.index', ['className' => 'Crud.Index', 'relatedModels' => ['Tags']]);

        $controller->eventManager()->on('Crud.beforePaginate', new \Crud\Listener\RelatedModelsListener($controller));

        $this->loadFixtures('Blogs', 'Tags');
        $q = $controller->paginate($controller->Blogs);

//        $reflect = new \ReflectionClass($q);
//        $property = $reflect->getProperty('_query');
//        $property->setAccessible(true);
//
//        var_dump($property->getValue($reflect));

//        var_dump($q->toArray());

    }
}
