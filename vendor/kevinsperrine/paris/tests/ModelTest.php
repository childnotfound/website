<?php

class ModelTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // Enable logging
        ORM::configure('logging', true);
        ORM::configure('caching', false);

        // Set up the dummy database connection
        $db = new DummyPDO('sqlite::memory:');
        ORM::setDatabase($db);
    }

    public function tearDown()
    {
        // your code here
    }

    public function testHydrateAndAsArray()
    {
        $book = Model::factory('Simple');

        $model = $book->create();

        $model->hydrate(array(
            'title' => 'Harry Potter'
        ));

        $model->set('publisher', 'Scholastic');

        $this->assertTrue($model->isDirty('publisher'));

        $model->save();

        $bookData = $model->asArray();
        $this->assertTrue(is_array($bookData));
        $this->assertFalse($model->isDirty('publisher'));
        $this->assertContains('Harry Potter', $bookData);
    }

    public function testSettersAndGetters()
    {
        $book = Model::factory('Simple');

        $model = $book->create(array(
            'name' => 'The Hunger Games',
        ));

        $this->assertTrue($model->isDirty('name'));

        $model->save();

        $this->assertEquals($model->get('name'), 'The Hunger Games');
        $model->set('name', 'The Wizard of Oz');
        $this->assertEquals($model->get('name'), 'The Wizard of Oz');
        $this->assertTrue(isset($model->name));
    }

    public function testSimpleModel()
    {
        Model::factory('Simple')->findMany();
        $expected = 'SELECT * FROM `simple`';
        $this->assertEquals($expected, ORM::getLastQuery());
    }

    public function testCreateModelNonExistentId()
    {
        // hack built into the Mock PDO Statement to automatically return false
        // ie. not found, when searching for IDs equal to 9999
        $models = Model::factory('Simple')->findOne(9999);
        
        $this->assertFalse($models);
    }

    public function testComplexModel()
    {
        Model::factory('ComplexModelClassName')->findMany();
        $expected = 'SELECT * FROM `complex_model_class_name`';
        $this->assertEquals($expected, ORM::getLastQuery());
    }

    public function testModelWithCustomTable()
    {
        Model::factory('ModelWithCustomTable')->findMany();
        $expected = 'SELECT * FROM `custom_table`';
        $this->assertEquals($expected, ORM::getLastQuery());
    }

    public function testModelWithCustomTableAndCustomIDColumn()
    {
        Model::factory('ModelWithCustomTableAndCustomIdColumn')->findOne(5);
        $expected = "SELECT * FROM `custom_table` WHERE `custom_id_column` = '5' LIMIT 1";
        $this->assertEquals($expected, ORM::getLastQuery());
    }

    public function testModelWithFilters()
    {
        Model::factory('ModelWithFilters')->filter('name_is_fred')->findMany();
        $expected = "SELECT * FROM `model_with_filters` WHERE `name` = 'Fred'";
        $this->assertEquals($expected, ORM::getLastQuery());

        Model::factory('ModelWithFilters')->filter('name_is', 'Bob')->findMany();
        $expected = "SELECT * FROM `model_with_filters` WHERE `name` = 'Bob'";
        $this->assertEquals($expected, ORM::getLastQuery());
    }

    public function testModelWithNonExistentFilters()
    {
        // This attempts to filter with an invalid function.
        // An exception should be caught, because the method doesn't exist.
        try {
            Model::factory('ModelWithFilters')->filter('name_is_george')->findMany();
        } catch (Exception $e) {
            return;
        }

        // If an exception isn't caught and the method returns
        // then this should be considered a failure.
        $this->fail();
    }

    public function testModelInsertUpdateDelete()
    {
        $widget = Model::factory('Widget')->create();
        $widget->name = "Fred";
        $widget->age = 10;
        $widget->save();
        $expected = "INSERT INTO `widget` (`name`, `age`) VALUES ('Fred', '10')";
        $this->assertEquals($expected, ORM::getLastQuery());

        $widget = Model::factory('Widget')->findOne(1);
        $widget->name = "Fred";
        $widget->age = 10;
        $widget->save();
        $expected = "UPDATE `widget` SET `name` = 'Fred', `age` = '10' WHERE `id` = '1'";
        $this->assertEquals($expected, ORM::getLastQuery());

        $widget = Model::factory('Widget')->findOne(1);
        $widget->delete();
        $expected = "DELETE FROM `widget` WHERE `id` = '1'";
        $this->assertEquals($expected, ORM::getLastQuery());
    }

    public function testRelationships()
    {

        $user = Model::factory('User')->findOne(1);
        $profile = $user->profile()->findOne();
        $expected = "SELECT * FROM `profile` WHERE `user_id` = '1' LIMIT 1";
        $this->assertEquals($expected, ORM::getLastQuery());

        $user2 = Model::factory('UserTwo')->findOne(1);
        $profile = $user2->profile()->findOne();
        $expected = "SELECT * FROM `profile` WHERE `my_custom_fk_column` = '1' LIMIT 1";
        $this->assertEquals($expected, ORM::getLastQuery());

        $profile->user_id = 1;
        $user3 = $profile->user()->findOne();
        $expected = "SELECT * FROM `user` WHERE `id` = '1' LIMIT 1";
        $this->assertEquals($expected, ORM::getLastQuery());

        $profile2 = Model::factory('ProfileTwo')->findOne(1);
        $profile2->custom_user_fk_column = 5;
        $user4 = $profile2->user()->findOne();
        $expected = "SELECT * FROM `user` WHERE `id` = '5' LIMIT 1";
        $this->assertEquals($expected, ORM::getLastQuery());

        $user4 = Model::factory('UserThree')->findOne(1);
        $posts = $user4->posts()->findMany();
        $expected = "SELECT * FROM `post` WHERE `user_three_id` = '1'";
        $this->assertEquals($expected, ORM::getLastQuery());

        $user5 = Model::factory('UserFour')->findOne(1);
        $posts = $user5->posts()->findMany();
        $expected = "SELECT * FROM `post` WHERE `my_custom_fk_column` = '1'";
        $this->assertEquals($expected, ORM::getLastQuery());
    }

    public function testComplexRelationships()
    {
        $book = Model::factory('Book')->findOne(1);
        $authors = $book->authors()->findMany();
        $expected = "SELECT `author`.* FROM `author` JOIN `author_book` ON `author`.`id` = `author_book`.`author_id` WHERE `author_book`.`book_id` = '1'";
        $this->assertEquals($expected, ORM::getLastQuery());

        $book2 = Model::factory('BookTwo')->findOne(1);
        $authors2 = $book2->authors()->findMany();
        $expected = "SELECT `author_two`.* FROM `author_two` JOIN `wrote_the_book` ON `author_two`.`id` = `wrote_the_book`.`custom_author_id` WHERE `wrote_the_book`.`custom_book_id` = '1'";
        $this->assertEquals($expected, ORM::getLastQuery());

        $this->assertEquals($expected, ORM::getLastQuery());
    }


}
