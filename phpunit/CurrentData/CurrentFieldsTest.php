<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentFields.php');
include_once(__DIR__.'/../../export/modules/BGA/Storage.php');

class CurrentFieldsTest extends TestCase{
    const BUCKET_KEYS = ['field_id', 'player_id'];
    const PLAYER_DATA = [55 => 'TEST'];
    const ROBOT_DATA = [5 => 'TESTR'];
    const PLAYER_BUCKET_INPUT_DATA = ['field', CurrentFieldsTest::BUCKET_KEYS];

    protected CurrentFields $sut;

    protected function setUp(): void {
        $this->mock_storage = $this->createMock(\NieuwenhovenGames\BGA\Storage::class);
        $this->sut = CurrentFields::create($this->mock_storage);
    }

    public function testProperties_GetPlayer_GetBucket() {
        // Arrange
        // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        $this->mock_storage->expects($this->exactly(1))
        ->method('getBucket')
        ->withConsecutive(CurrentFieldsTest::PLAYER_BUCKET_INPUT_DATA)
        ->will($this->returnValue(CurrentFieldsTest::PLAYER_DATA));
        // Act
        $data = $this->sut->getFields();
        // Assert
        $this->assertEquals(CurrentFieldsTest::PLAYER_DATA, $data);
    }
}
?>
