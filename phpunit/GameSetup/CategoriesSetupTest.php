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

    public function testGet_OneCategories_FieldIncludesCategory() {
        // Arrange
        $this->mockCategory = $this->createMock(CategorySetupInterface::class);
        $this->sut->setCategories([$this->mockCategory]);
        $id_list = ['a'];
        $category_id = 'houses';

        $this->mockCategory->expects($this->exactly(1))
        ->method('getAllFieldIDsForOccupation')
        ->will($this->returnValue($id_list));

        $this->mockCategory->expects($this->exactly(1))
        ->method('getCategoryID')
        ->will($this->returnValue($category_id));

        // Act
        $field_ids = $this->sut->getAllCompleteFieldIDsForOccupation();
        // Assert
        $this->assertCount(1, $field_ids);
        $this->assertEquals('field_houses_a', array_shift($field_ids));
    }

    public function testGet_OneCategoriesNoOccupation_NoFields() {
        // Arrange
        $this->mockCategoryNoOccupation = $this->createMock(CategoriesSetup::class);
        $this->sut->setCategories([$this]);
        $id_list = ['a'];

        // Act
        $field_ids = $this->sut->getAllCompleteFieldIDsForOccupation();
        // Assert
        $this->assertCount(0, $field_ids);
    }

    public function testGet_3Categories2Occupation_4Fields() {
        // Arrange
        $this->mockCategoryNoOccupation = $this->createMock(CategoriesSetup::class);
        $this->mockCategory = $this->createMock(CategorySetupInterface::class);
        $this->sut->setCategories([$this->mockCategory, $this->mockCategoryNoOccupation, $this->mockCategory]);

        $single_category_list = ['a', 'b'];
        $this->mockCategory->expects($this->exactly(2))
        ->method('getAllFieldIDsForOccupation')
        ->will($this->returnValue($single_category_list));

        // Act
        $field_ids = $this->sut->getAllCompleteFieldIDsForOccupation();
        // Assert
        $this->assertCount(4, $field_ids);
    }
}
?>
