<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/PlayerProperties.php');

include_once(__DIR__.'/../export/modules/BGA/DatabaseInterface.php');

class PlayerPropertiesTest extends TestCase{
    public function setup() : void {
        $this->mock = $this->createMock(\NieuwenhovenGames\BGA\DatabaseInterface::class);
        $this->sut = PlayerProperties::create($this->mock);
    }
    public function arrange($number_players, $number_colors) {
        $this->players = [];
        for ($i=0; $i<$number_players; $i++) {
            $this->players[$i] = ['player_canal' => 0, 'player_name' => 'robot_' . 0, 'player_avatar' => ''];
        }
        $this->colors = ['green', 'red'];
        $this->mock->expects($this->exactly(1))->method('query')->with($this->equalTo(PlayerProperties::CREATE_PLAYERS . "('0','green','0','robot_0','','0'),('1','red','0','robot_0','','0')"));
    }

    public function defaultAct() {
        $this->sut->setupNewGame($this->players, $this->colors);
    }

    public function testNewGame_2Players_Query() {
        // Arrange
        $this->arrange(2, 0);
        // Act
        $this->defaultAct();
        // Assert
    }

}
?>
