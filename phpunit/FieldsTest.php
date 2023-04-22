<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/Fields.php');
include_once(__DIR__.'/../export/modules/BGA/Storage.php');

class FieldsTest extends TestCase{
    protected Fields $sut;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(\NieuwenhovenGames\BGA\Storage::class);
        $this->sut = Fields::create($this->mock_database);
    }

    public function testSet_Value_Update() {
        // Arrange
        $bucket_name = 'field';
        $field_id = 'field_ocean_1';
        $player_id = '3';
        $this->mock_database->expects($this->exactly(1))
        ->method('updateValueForField')
        ->with($this->equalTo($bucket_name), $this->equalTo(Fields::PLAYER_ID_NAME), $this->equalTo($player_id), $this->equalTo(Fields::FIELD_ID_NAME), $this->equalTo($field_id));

        // Act
        $this->sut->occupyField($field_id, $player_id);
        // Assert
    }

    protected function arrangeDefault() {
        $this->category = 'Category';
    }

    public function testAnalyseFieldGetID() {
        // Arrange
        $this->arrangeDefault();
        $expected_id = 'ID1';
        // Act
        $id = $this->sut->getID(Fields::FIELD_PREFIX . $this->category . '_' . $expected_id);
        // Assert
        $this->assertEquals($expected_id, $id);
    }

    public function testCompleteIDsEmptyListReturnsEmptyList() {
        // Arrange
        $this->arrangeDefault();
        // Act
        $ids = $this->sut->completeIDs($this->category, []);
        // Assert
        $this->assertEquals([], $ids);
    }

    public function testCompleteIDsListReturnsCompletedList() {
        // Arrange
        $this->arrangeDefault();
        $expectedList = [Fields::FIELD_PREFIX . $this->category . '_' . 'ID1', Fields::FIELD_PREFIX . $this->category . '_'];
        // Act
        $ids = $this->sut->completeIDs($this->category, ['ID1', '']);
        // Assert
        $this->assertEquals($expectedList, $ids);
    }
}
?>
