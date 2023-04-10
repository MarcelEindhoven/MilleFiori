<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/BGA/Storage.php');
include_once(__DIR__.'/../export/modules/BGA/DatabaseInterface.php');

class StorageTest extends TestCase{
    protected \NieuwenhovenGames\BGA\Storage $sut;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(\NieuwenhovenGames\BGA\DatabaseInterface::class);
        $this->sut = \NieuwenhovenGames\BGA\Storage::create($this->mock_database);
    }

    public function testGet_2Fields_getObjectList() {
        // Arrange
        $bucket_name = 'fields';
        $field_name_1 = 'field';
        $field_name_2 = 'player';
        $bucket_fields = [$field_name_1, $field_name_2];
        $this->mock_database->expects($this->exactly(1))->method('getObjectList')
            ->with($this->equalTo("SELECT $field_name_1 $field_name_1, $field_name_2 $field_name_2 FROM $bucket_name"))
            ->will($this->returnValue([]));
        // Act
        $object_list = $this->sut->getBucket($bucket_name, $bucket_fields);
        // Assert
        $this->assertEquals([], $object_list);
    }

    protected function arrangeCreate($expected_query) {
        $this->mock_database->expects($this->exactly(1))
        ->method('query')
        ->with($this->equalTo($expected_query));
    }

    public function testCreate_NoField_SimpleQuery() {
        // Arrange
        $bucket_name = 'fields';
        $this->arrangeCreate("INSERT INTO $bucket_name () VALUES ");
        // Act
        $this->sut->createBucket($bucket_name, [], []);
        // Assert
    }

    public function testCreate_SingleFieldNoValues_Query() {
        // Arrange
        $bucket_name = 'fields';
        $field_id = 'field';
        $this->arrangeCreate("INSERT INTO $bucket_name ($field_id) VALUES ");
        // Act
        $this->sut->createBucket($bucket_name, [$field_id], []);
        // Assert
    }

    public function testCreate_MultipleFieldMultipleValues_Query() {
        // Arrange
        $bucket_name = 'fields';
        $field_id = 'field';
        $field_id2 = 'field2';
        $value_1_field_id = 'a';
        $value_1_field_id2 = 'b';
        $value_2_field_id = 'c';
        $value_2_field_id2 = 'd';
        $this->arrangeCreate("INSERT INTO $bucket_name ($field_id,$field_id2) VALUES ('$value_1_field_id','$value_1_field_id2'),('$value_2_field_id','$value_2_field_id2')");
        // Act
        $this->sut->createBucket($bucket_name, [$field_id, $field_id2], [[$value_1_field_id, $value_1_field_id2], [$value_2_field_id, $value_2_field_id2]]);
        // Assert
    }
}
?>
