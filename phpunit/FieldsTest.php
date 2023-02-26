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

class FieldsTest extends TestCase{
    protected Fields $sut;

    protected function setUp(): void {
        $this->sut = new Fields();
    }

    protected function arrangeDefault() {
        $this->category = 'Category';
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
