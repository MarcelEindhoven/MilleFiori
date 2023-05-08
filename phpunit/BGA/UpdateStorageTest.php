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

        $this->mock_emitter = $this->createMock(\NieuwenhovenGames\BGA\EventEmitter::class);

        $this->sut = \NieuwenhovenGames\BGA\UpdateStorage::create($this->mock_database);
    }

    public function testUpdate_Value_Query() {
        // Arrange
        $bucket_name = 'field';
        $field_name_value = 'player_id';
        $value = '3';
        $field_name_selector = 'field_id';
        $value_selector = 'field_ocean_3';
        $this->arrangeQuery("UPDATE $bucket_name SET $field_name_value=$value WHERE $field_name_selector=$value_selector");
        // Act
        $this->sut->updateValueForField($bucket_name, $field_name_value, $value, $field_name_selector, $value_selector);
        // Assert
    }

    protected function arrangeQuery($expected_query) {
        $this->mock_database->expects($this->exactly(1))
        ->method('query')
        ->with($this->equalTo($expected_query));
    }
}
?>
