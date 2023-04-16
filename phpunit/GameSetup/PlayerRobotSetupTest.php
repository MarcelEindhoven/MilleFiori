<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameSetup/PlayerRobotSetup.php');
include_once(__DIR__.'/../../export/modules/BGA/Storage.php');

class PlayerRobotSetupTest extends TestCase{
    const COLORS = ['green', 'red', 'blue', 'yellow'];

    protected PlayerRobotSetup $sut;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(\NieuwenhovenGames\BGA\Storage::class);
        $this->sut = PlayerRobotSetup::create($this->mock_database);
    }

    protected function getExpectedRobotValues($number_robots) {
        $values = [];
        for ($i = 1; $i <= $number_robots; $i++) {
            $player_number = 4 - 1 - $number_robots + $i;
            $values[] = [$player_number + 1, $i, PlayerPropertiesTest::COLORS[$player_number], 'robot_' . $i, 0];
        }
        return $values;
    }

    protected function getExpectedRobotBucket($number_robots) {
        return ['robot', PlayerRobotSetup::FIELDS_ROBOT, $this->getExpectedRobotValues($number_robots)];
    }

    protected function arrangePlayers($number_players) {
        $this->players = [];
        $this->values = [];
        for ($i=0; $i<$number_players; $i++) {
            $this->players[$i] = ['player_canal' => 0, 'player_name' => 'player_' . $i, 'player_avatar' => ''];
            $this->values[] = [$i, PlayerPropertiesTest::COLORS[$i], 0, 'player_' . $i, '', 0];
        }
        $expected_player_bucket = ['player', PlayerRobotSetup::FIELDS_PLAYER, $this->values];

        if ($number_players < 4) {
            $this->mock_database->expects($this->exactly(2))
            ->method('createBucket')
            ->withConsecutive($expected_player_bucket, $this->getExpectedRobotBucket(4 - $number_players));
        } else {
            $this->mock_database->expects($this->exactly(1))
            ->method('createBucket')
            ->withConsecutive($expected_player_bucket);
        }
    }

    protected function actSetup() {
        $this->sut->setup($this->players, PlayerPropertiesTest::COLORS);
    }

    public function testSetup_4Players_CreatePlayerBucket() {
        // Arrange
        $this->arrangePlayers(4);

        // Act
        $this->actSetup();
        // Assert
    }

    public function testSetup_2Players2Robots_CreatePlayerBucketRobotBucket() {
        // Arrange
        $this->arrangePlayers(2);

        // Act
        $this->actSetup();
        // Assert
    }
}
?>
