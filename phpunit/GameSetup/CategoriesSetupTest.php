<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameSetup/CategorySetupInterface.php');
include_once(__DIR__.'/../../export/modules/GameSetup/CategoriesSetup.php');

class CategoriesSetupTest extends TestCase{
    protected CategoriesSetup $sut;

    protected function setUp(): void {
        $this->sut = new CategoriesSetup();
    }

    public function testGet_NoCategories_NoFields() {
        // Arrange
        $this->sut->setCategories([]);

        // Act
        $field_ids = $this->sut->getAllCompleteFieldIDsForOccupation();
        // Assert
        $this->assertCount(0, $field_ids);
    }

    public function testGet_OneCategories_1Fields() {
        // Arrange
        $this->mockCategory = $this->createMock(CategorySetupInterface::class);
        $this->sut->setCategories([$this->mockCategory]);
        $expected_list = ['a'];
        $this->mockCategory->expects($this->exactly(1))
        ->method('getAllFieldIDsForOccupation')
        ->will($this->returnValue($expected_list));

        // Act
        $field_ids = $this->sut->getAllCompleteFieldIDsForOccupation();
        // Assert
        $this->assertCount(1, $field_ids);
    }

    public function testGet_OneCategoriesNoOccupation_NoFields() {
        // Arrange
        $this->mockCategory = $this->createMock(CategoriesSetup::class);
        $this->sut->setCategories([$this]);
        $expected_list = ['a'];

        // Act
        $field_ids = $this->sut->getAllCompleteFieldIDsForOccupation();
        // Assert
        $this->assertCount(0, $field_ids);
    }
}
?>
