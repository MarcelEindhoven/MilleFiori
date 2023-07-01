<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/UpdateStorage.php');
include_once(__DIR__.'/../../export/modules/BGA/Database.php');
include_once(__DIR__.'/../../export/modules/BGA/EventEmitter.php');

class UpdateStorageTest extends TestCase{
    protected UpdateStorage $sut;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(Database::class);
        $this->sut = UpdateStorage::create($this->mock_database);

        $this->mock_emitter = $this->createMock(EventEmitter::class);
        $this->mock_emitter->expects($this->exactly(1))->method('on');
        $this->sut->setEventEmitter($this->mock_emitter);
    }

    protected function arrangeDefault() {
        $this->bucket_name = 'field';
        $this->field_name_value = 'player_id';
        $this->value = '3';
        $this->field_name_selector = 'field_id';
        $this->value_selector = 'field_ocean_3';
        $this->arrangeQuery("UPDATE $this->bucket_name SET $this->field_name_value=$this->value WHERE $this->field_name_selector=$this->value_selector");

        $this->event = [
            UpdateStorage::EVENT_KEY_BUCKET => $this->bucket_name,
            UpdateStorage::EVENT_KEY_NAME_VALUE => $this->field_name_value,
            UpdateStorage::EVENT_KEY_UPDATED_VALUE => $this->value,
            UpdateStorage::EVENT_KEY_NAME_SELECTOR => $this->field_name_selector,
            UpdateStorage::EVENT_KEY_SELECTED => $this->value_selector
        ];

        $this->mock_emitter->expects($this->exactly(1))->method('emit');
    }

    protected function arrangeQuery($expected_query) {
        $this->mock_database->expects($this->exactly(1))
        ->method('query')
        ->with($this->equalTo($expected_query));
    }

    public function testUpdate_Value_Query() {
        // Arrange
        $this->arrangeDefault();
        // Act
        $this->sut->updateValueForField($this->bucket_name, $this->field_name_value, $this->value, $this->field_name_selector, $this->value_selector);
        // Assert
    }

    public function testBucketUpdated_EmptyEvent_Warning() {
        // Arrange
        $this->expectWarning();
        // Act
        $this->sut->bucketUpdated([]);
        // Assert
    }

    public function testBucketUpdated_NormalEvent_DatabaseUpdate() {
        // Arrange
        $this->arrangeDefault();
        // Act
        $this->sut->bucketUpdated($this->event);
        // Assert
    }
}
?>
