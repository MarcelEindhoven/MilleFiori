<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameView/ViewCategories.php');

class ViewCategoriesTest extends TestCase{
    public function setup() : void {
        $this->sut = ViewCategories::create();
    }

    public function testFields_Generate_CombinedFields() {
        // Arrange
        // Act
        $fields = $this->sut->generateFields();
        // Assert
        $this->assertCount(21 + 20, $fields);
    }
}
?>
