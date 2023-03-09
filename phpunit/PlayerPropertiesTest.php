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
    const COLORS = ['green', 'red', 'blue', 'yellow'];
    public function setup() : void {
        $this->mock = $this->createMock(\NieuwenhovenGames\BGA\DatabaseInterface::class);
        $this->sut = PlayerProperties::create($this->mock);
    }
    public function arrange($number_players, $number_colors) {
        $this->players = [];
        $query = PlayerProperties::CREATE_PLAYERS;
        for ($i=0; $i<$number_players; $i++) {
            if ($i > 0) {
                $query .= ",";
            }
            $this->players[$i] = ['player_canal' => 0, 'player_name' => 'player_' . $i, 'player_avatar' => ''];
            $query .= "('$i','" . PlayerPropertiesTest::COLORS[$i] . "','0','player_$i','','0')";
        }
        if ($number_players < 4) {
            $query_robot = PlayerProperties::CREATE_ROBOTS;
            for ($i=$number_players; $i<4; $i++) {
                if ($i > $number_players) {
                    $query_robot .= ",";
                }
                $query_robot .= "('$i','" . PlayerPropertiesTest::COLORS[$i] . "','robot_$i','0')";
            }
            $this->mock->expects($this->exactly(2))->method('query')->withConsecutive([$this->equalTo($query)], [$this->equalTo($query_robot)]);
        } else {
            $this->mock->expects($this->exactly(1))->method('query')->with($this->equalTo($query));
        }
    }

    public function defaultAct() {
        $this->sut->setupNewGame($this->players, PlayerPropertiesTest::COLORS);
    }

    public function testNewGame_2Players_Query() {
        // Arrange
        $this->arrange(2, 0);
        // Act
        $this->defaultAct();
        // Assert
    }

    public function testNewGame_4Players_Query() {
        // Arrange
        $this->arrange(4, 0);
        // Act
        $this->defaultAct();
        // Assert
    }

}
?>
