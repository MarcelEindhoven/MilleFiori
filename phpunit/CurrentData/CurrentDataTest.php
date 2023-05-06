<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentData.php');
include_once(__DIR__.'/../../export/modules/CurrentData/CurrentCards.php');
include_once(__DIR__.'/../../export/modules/CurrentData/CurrentPlayerRobotProperties.php');

include_once(__DIR__.'/../../export/modules/CardsHandler.php');
include_once(__DIR__.'/../../export/modules/Game.php');

include_once(__DIR__.'/../../export/modules/BGA/CardsInterface.php');
include_once(__DIR__.'/../../export/modules/BGA/Storage.php');
include_once(__DIR__.'/../../export/modules/BGA/DatabaseInterface.php');

class CurrentDataTest extends TestCase{
    const COLORS = ['green', 'red', 'blue', 'yellow'];

    protected CurrentData $sut;

    protected function setUp(): void {
        $this->sut = new CurrentData();

        $this->mock_properties = $this->createMock(CurrentPlayerRobotProperties::class);

        $this->mock_cards = $this->createMock(\NieuwenhovenGames\BGA\CardsInterface::class);
        $this->sut->setCards($this->mock_cards);
        $this->player_id = 7;
    }

    protected function actDefault() {
        return $this->sut->setPlayerRobotProperties($this->mock_properties)->getAllData($this->player_id);
    }

    public function testGet_Integration_CardsInLocation() {
        // Arrange
        $this->mock_cards->expects($this->exactly(4))->method('getCardsInLocation')->will($this->returnValue(['x']));
        // Act
        $this->actDefault();
        // Assert
    }

    public function testGet_Integration_PlayerData() {
        // Arrange
        $expected_player_data = [1 => 'x'];
        $this->mock_properties->expects($this->exactly(1))->method('getPlayerData')->will($this->returnValue($expected_player_data));
        $this->mock_properties->expects($this->exactly(1))->method('getRobotData')->will($this->returnValue([]));
        // Act
        $result = $this->actDefault();
        // Assert
        $this->assertEquals($expected_player_data, $result[CurrentData::RESULT_KEY_PLAYERS]);
        $this->assertEquals($expected_player_data, $result[CurrentData::RESULT_KEY_PLAYERSROBOTS]);
    }

    public function testGet_IntegrationActivePlayer_CollectionAndCards() {
        // Arrange
        $this->mock_properties->expects($this->exactly(1))->method('getPlayerData')->will($this->returnValue([$this->player_id => [Ocean::KEY_PLAYER_POSITION => 5]]));
        $this->mock_cards->expects($this->exactly(5))->method('getCardsInLocation')->will($this->returnValue([[Game::CARD_KEY_TYPE => 9]]));
        // Act
        $selectable_fieldids = $this->sut->setPlayerRobotProperties($this->mock_properties)->getAllDataActivePlayerPlayingCard($this->player_id)[CurrentData::RESULT_KEY_SELECTABLE_FIELDS];
        // Assert
        $this->assertCount(1, $selectable_fieldids);
        $this->assertEquals('field_ocean_10', current($selectable_fieldids));
    }

    public function testTooltips_Integration_Array() {
        // Act
        $result = $this->actDefault();
        // Assert
        $this->assertCount(count(Ocean::PLACES_PER_CARD), $result[CurrentData::RESULT_KEY_TOOLTIPS_CARDS]);
    }

    public function testPlayerIDs_Integration_Array() {
        $this->mock_properties->expects($this->exactly(1))->method('getPlayerData')->will($this->returnValue([$this->player_id => [Ocean::KEY_PLAYER_POSITION => 5]]));
        // Act
        $ids = $this->sut->setPlayerRobotProperties($this->mock_properties)->getPlayerIDs();
        // Assert
        $this->assertEquals([$this->player_id], $ids);
    }

    public function testPlayerRobotIDs_Integration_Array() {
        $this->robot_id = 5;
        $this->mock_properties->expects($this->exactly(1))->method('getPlayerData')->will($this->returnValue([$this->player_id => [Ocean::KEY_PLAYER_POSITION => 5]]));
        $this->mock_properties->expects($this->exactly(1))->method('getRobotData')->will($this->returnValue([$this->robot_id => [Ocean::KEY_PLAYER_POSITION => 5]]));
        // Act
        $ids = $this->sut->setPlayerRobotProperties($this->mock_properties)->getPlayerRobotIDs();
        // Assert
        $this->assertEquals([$this->player_id, $this->robot_id], $ids);
    }

    public function testRobotIDs_Integration_Array() {
        $this->robot_id = 5;
        $this->mock_properties->expects($this->exactly(1))->method('getPlayerData')->will($this->returnValue([$this->player_id => [Ocean::KEY_PLAYER_POSITION => 5]]));
        $this->mock_properties->expects($this->exactly(1))->method('getRobotData')->will($this->returnValue([$this->robot_id => [Ocean::KEY_PLAYER_POSITION => 5]]));
        // Act
        $ids = $this->sut->setPlayerRobotProperties($this->mock_properties)->getRobotIDs();
        // Assert
        $this->assertEquals([$this->robot_id], array_values($ids));
    }

    public function testFieldIDs_Integration_ArrayEmpty() {
        $this->mock_cards->expects($this->exactly(1))->method('getCardsInLocation')->will($this->returnValue([[Game::CARD_KEY_TYPE => 9]]));
        $this->mock_properties->expects($this->exactly(1))->method('getPlayerData')->will($this->returnValue([$this->player_id => [Ocean::KEY_PLAYER_POSITION => 5]]));
        // Act
        $ids = $this->sut->setPlayerRobotProperties($this->mock_properties)->getSelectableFieldIDs($this->player_id);
        // Assert
        $this->assertEquals(['field_ocean_10'], array_values($ids));
    }
}
?>
