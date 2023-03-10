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
            $query .= $this->optionalSeparator($i);
            $query .= "('$i','" . PlayerPropertiesTest::COLORS[$i] . "','0','player_$i','','0')";

            $this->players[$i] = ['player_canal' => 0, 'player_name' => 'player_' . $i, 'player_avatar' => ''];
        }
        if ($number_players < 4) {
            $query_robot = PlayerProperties::CREATE_ROBOTS;
            for ($index = 0; $index < 4 - $number_players; $index++) {
                $query_robot .= $this->optionalSeparator($index);
                $query_robot .= "('$index','" . PlayerPropertiesTest::COLORS[$number_players + $index] . "','robot_$index','0')";
            }
            $this->mock->expects($this->exactly(2))->method('query')->withConsecutive([$this->equalTo($query)], [$this->equalTo($query_robot)]);
        } else {
            $this->mock->expects($this->exactly(1))->method('query')->with($this->equalTo($query));
        }
    }
    private function optionalSeparator(int $index) : string {
        if ($index > 0) {
            return ",";
        }
        return "";
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
