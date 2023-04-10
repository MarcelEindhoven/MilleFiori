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

    public function testFields_Single_CreateBucket() {
        // Arrange
        $bucket_name = 'field';
        $field_id = 'field_ocean_1';
        $this->mock_database->expects($this->exactly(1))
        ->method('createBucket')
        ->with($this->equalTo($bucket_name), $this->equalTo([Fields::FIELD_ID_NAME, Fields::PLAYER_ID_NAME]), [[$field_id, Fields::NOT_OCCUPIED]]);

        // Act
        $this->sut->createFields([$field_id]);
        // Assert
    }

    public function testGet_SingleField_ReturnSingleField() {
        // Arrange
        $bucket_name = 'field';
        $field_id = 'field_ocean_1';
        $expected_list = [$field_id];
        $this->mock_database->expects($this->exactly(1))
        ->method('getBucket')
        ->with($this->equalTo($bucket_name), $this->equalTo([Fields::FIELD_ID_NAME, Fields::PLAYER_ID_NAME]))
        ->will($this->returnValue($expected_list));

        // Act
        $fields = $this->sut->getFields();
        // Assert
        $this->assertEquals($expected_list, $fields);
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
