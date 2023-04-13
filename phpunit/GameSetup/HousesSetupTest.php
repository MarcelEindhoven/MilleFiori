<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameSetup/HousesSetup.php');

class HousesSetupTest extends TestCase{
    protected HousesSetup $sut;

    protected function setUp(): void {
        $this->sut = new HousesSetup();
    }

    public function testFields_Single_CreateBucket() {
        // Arrange

        // Act
        $field_ids = $this->sut->getAllFieldIDsForOccupation();
        // Assert
        $this->assertCount(20, $field_ids);
    }
}
?>
