<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/UpdateStorage.php');
include_once(__DIR__.'/../../export/modules/BGA/DatabaseInterface.php');
include_once(__DIR__.'/../../export/modules/BGA/EventEmitter.php');

class UpdateStorageTest extends TestCase{
    protected \NieuwenhovenGames\BGA\UpdateStorage $sut;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(\NieuwenhovenGames\BGA\DatabaseInterface::class);
        $this->sut = \NieuwenhovenGames\BGA\UpdateStorage::create($this->mock_database);

        $this->mock_emitter = $this->createMock(\NieuwenhovenGames\BGA\EventEmitter::class);
        $this->sut->setEventEmitter($this->mock_emitter);
    }

    protected function arrangeDefault() {
        $this->bucket_name = 'field';
        $this->field_name_value = 'player_id';
        $this->value = '3';
        $this->field_name_selector = 'field_id';
        $this->value_selector = 'field_ocean_3';
        $this->arrangeQuery("UPDATE $this->bucket_name SET $this->field_name_value=$this->value WHERE $this->field_name_selector=$this->value_selector");

        $this->mock_emitter->expects($this->exactly(1))
        ->method('emit');
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
}
?>
