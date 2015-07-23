<?php
namespace Crud\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class TagsFixture extends TestFixture
{

    public $fields = [
        'id' => ['type' => 'integer'],
        'blog_id' => ['type' => 'integer', 'null' => false],
        'name' => ['type' => 'string', 'length' => 255, 'null' => false],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
    ];

    public $records = [
        ['blog_id' => '1', 'name' => 'Cat'],
        ['blog_id' => '1', 'name' => 'Dog'],
        ['blog_id' => '1', 'name' => 'Fish'],
        ['blog_id' => '2', 'name' => 'Cat'],
        ['blog_id' => '3', 'name' => 'Fish'],
    ];
}
