<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameSetup/CardsSetup.php');
include_once(__DIR__.'/../../export/modules/BGA/CardsInterface.php');

class CardsSetupTest extends TestCase{
    const COLORS = ['green', 'red', 'blue', 'yellow'];

    protected CardsSetup $sut;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(\NieuwenhovenGames\BGA\CardsInterface::class);
        $this->sut = CardsSetup::create($this->mock_database);
    }

    protected function actSetup() {
//        $this->sut->setup($this->players, PlayerPropertiesTest::COLORS);
    }

    public function testSetup_2Players2Robots_CreatePlayerBucketRobotBucket() {
        // Arrange

        // Act
        $this->actSetup();
        // Assert
    }
}
?>
