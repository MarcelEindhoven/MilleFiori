<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentPlayerRobotProperties.php');
include_once(__DIR__.'/../../export/modules/BGA/Storage.php');

class CurrentPlayerRobotPropertiesTest extends TestCase{
    protected CurrentPlayerRobotProperties $sut;

    protected function setUp(): void {
        $this->mock_storage = $this->createMock(\NieuwenhovenGames\BGA\Storage::class);
        $this->sut = CurrentPlayerRobotProperties::create($this->mock_storage);
    }

    public function testProperties_GetPlayer_GetBucket() {
        // Arrange
        // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        $this->mock_storage->expects($this->exactly(1))
        ->method('getBucket')
        ->with('player', ['id', 'score', 'no', 'color', 'ocean_position'], 'player_')
        ->will($this->returnValue([5 => 'TEST']));
        // Act
        $this->sut->getPlayerData();
        // Assert
    }
}
?>
