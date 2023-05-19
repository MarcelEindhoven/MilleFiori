<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/Storage.php');
include_once(__DIR__.'/../../export/modules/BGA/DatabaseInterface.php');

class StorageTest extends TestCase{
    protected Storage $sut;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(DatabaseInterface::class);
        $this->sut = Storage::create($this->mock_database);
    }

    public function testGet_2Fields_getCollection() {
        // Arrange
        $bucket_name = 'fields';
        $field_name_1 = 'field';
        $field_name_2 = 'player';
        $bucket_fields = [$field_name_1, $field_name_2];
        $this->mock_database->expects($this->exactly(1))->method('getCollection')
            ->with($this->equalTo("SELECT $field_name_1 $field_name_1, $field_name_2 $field_name_2 FROM $bucket_name"))
            ->will($this->returnValue([]));
        // Act
        $object_list = $this->sut->getBucket($bucket_name, $bucket_fields);
        // Assert
        $this->assertEquals([], $object_list);
    }

    public function testGet_Prefix_FieldsWithAndWithoutPrefix() {
        // Arrange
        $bucket_name = 'player';
        $prefix = 'player_';

        $field_name_without_prefix = 'no';
        $field_name_with_prefix = $prefix . $field_name_without_prefix;

        $bucket_fields = [$field_name_without_prefix];

        $this->mock_database->expects($this->exactly(1))->method('getCollection')
            ->with($this->equalTo("SELECT $field_name_with_prefix $field_name_without_prefix FROM $bucket_name"))
            ->will($this->returnValue([]));
        // Act
        $object_list = $this->sut->getBucket($bucket_name, $bucket_fields, $prefix);
        // Assert
        $this->assertEquals([], $object_list);
    }
}
?>
