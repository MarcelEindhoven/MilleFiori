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

    public function testSetup_4Players_CreatePlayerBucket() {
        // Arrange
        $number_players = 4;
        $this->players = [];
        $this->values = [];
        for ($i=0; $i<$number_players; $i++) {
            $this->players[$i] = ['player_canal' => 0, 'player_name' => 'player_' . $i, 'player_avatar' => ''];
            $this->values[] = [$i, PlayerPropertiesTest::COLORS[$i], 0, 'player_' . $i, '', 0];
        }

        $bucket_name = 'player';
        $this->mock_database->expects($this->exactly(1))
        ->method('createBucket')
        ->with($this->equalTo($bucket_name), $this->equalTo(PlayerRobotSetup::FIELDS_PLAYER), $this->values);

        // Act
        $this->sut->setup($this->players, PlayerPropertiesTest::COLORS);
        // Assert
    }
}
?>
