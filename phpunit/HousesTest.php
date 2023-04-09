<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/Houses.php');

include_once(__DIR__.'/../export/modules/PlayerProperties.php');

class HousesTest extends TestCase{
    public function setup() : void {
        $this->mock = $this->createMock(PlayerProperties::class);
        $this->sut = new Houses($this->mock);
    }

    public function testReward_Zero_2Points() {
        // Arrange
        // Act
        $reward = $this->sut->getReward(2, 0);
        // Assert
        $this->assertEquals(2, $reward['points']);
    }
}
?>
