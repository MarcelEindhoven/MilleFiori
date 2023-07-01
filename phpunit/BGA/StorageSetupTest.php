<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/StorageSetup.php');
include_once(__DIR__.'/../../export/modules/BGA/Database.php');

class StorageSetupTest extends TestCase{
    protected StorageSetup $sut;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(Database::class);
        $this->sut = StorageSetup::create($this->mock_database);
    }

    protected function arrangeQuery($expected_query) {
        $this->mock_database->expects($this->exactly(1))
        ->method('query')
        ->with($this->equalTo($expected_query));
    }

    public function testCreate_NoField_SimpleQuery() {
        // Arrange
        $bucket_name = 'fields';
        $this->arrangeQuery("INSERT INTO $bucket_name () VALUES ");
        // Act
        $this->sut->createBucket($bucket_name, [], []);
        // Assert
    }

    public function testCreate_SingleFieldNoValues_Query() {
        // Arrange
        $bucket_name = 'fields';
        $field_id = 'field';
        $this->arrangeQuery("INSERT INTO $bucket_name ($field_id) VALUES ");
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
        $this->arrangeQuery("INSERT INTO $bucket_name ($field_id,$field_id2) VALUES ('$value_1_field_id','$value_1_field_id2'),('$value_2_field_id','$value_2_field_id2')");
        // Act
        $this->sut->createBucket($bucket_name, [$field_id, $field_id2], [[$value_1_field_id, $value_1_field_id2], [$value_2_field_id, $value_2_field_id2]]);
        // Assert
    }

    public function testCreate_Prefix_UpdatedFields() {
        // Arrange
        $bucket_name = 'fields';
        $field_id = 'field';
        $prefix = 'player_';
        $field_id_with_prefix = $prefix . 'field';
        $this->arrangeQuery("INSERT INTO $bucket_name ($field_id_with_prefix) VALUES ");
        // Act
        $this->sut->createBucket($bucket_name, [$field_id], [], $prefix);
        // Assert
    }
}
?>
