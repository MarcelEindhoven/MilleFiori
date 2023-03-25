<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/Fields.php');
include_once(__DIR__.'/../export/modules/Game.php');
include_once(__DIR__.'/../export/modules/PlayerProperties.php');
include_once(__DIR__.'/../export/modules/Robot.php');
include_once(__DIR__.'/../export/modules/Ocean.php');

include_once(__DIR__.'/../export/modules/BGA/CardsInterface.php');
include_once(__DIR__.'/../export/modules/BGA/DatabaseInterface.php');
include_once(__DIR__.'/../export/modules/BGA/NotifyInterface.php');

class GameTest extends TestCase{
    public function setup() : void {
        $this->mock = $this->createMock(\NieuwenhovenGames\BGA\DatabaseInterface::class);
        $this->sut = Game::create($this->mock);

        $this->mockNotifyInterface = $this->createMock(\NieuwenhovenGames\BGA\NotifyInterface::class);
        $this->sut->setNotifyInterface($this->mockNotifyInterface);

        $this->mockCards = $this->createMock(\NieuwenhovenGames\BGA\CardsInterface::class);
        $this->sut->setCards($this->mockCards);

        $this->mockPlayerProperties = $this->createMock(PlayerProperties::class);
        $this->sut->setPlayerProperties($this->mockPlayerProperties);

        $this->mockFields = $this->createMock(Fields::class);
        $this->sut->setFields($this->mockFields);

        $this->mockOcean = $this->createMock(Ocean::class);
        $this->sut->setOcean($this->mockOcean);
    }

    public function testNotify_Robot_NoNotify() {
        // Arrange
        $this->mockPlayerProperties->expects($this->exactly(1))->method('isPlayerARobot')->will($this->returnValue(true));
        $this->mockNotifyInterface->expects($this->exactly(0))->method('notifyPlayer');
        // Act
        $this->sut->notifyPlayerIfNotRobot(2, '', '', []);
        // Assert
    }

    public function testNotify_Player_Notify() {
        // Arrange
        $this->mockPlayerProperties->expects($this->exactly(1))->method('isPlayerARobot')->will($this->returnValue(false));

        $player_id = 11;
        $notification_type = 'notification_type';
        $notification_log = 'notification_log';
        $notification_args = ['notification_args' => 'notification_args'];
        $this->mockNotifyInterface->expects($this->exactly(1))->method('notifyPlayer')->with($player_id, $notification_type, $notification_log, $notification_args);
        // Act
        $this->sut->notifyPlayerIfNotRobot($player_id, $notification_type, $notification_log, $notification_args);
        // Assert
    }

    public function testRobotsSelectCard_NoRobots_NoSelection() {
        // Arrange
        $this->mockPlayerProperties->expects($this->exactly(1))->method('getRobotProperties')->will($this->returnValue([]));
        // Act
        $this->sut->allRobotsSelectCard();
        // Assert
    }

    private function createCard(int $cardID) {
        return [Game::CARD_KEY_ID => $cardID + 100, Game::CARD_KEY_TYPE => $cardID];
    }

    public function testRobotsSelectCard_OneCard2Robots_Select2Cards() {
        // Arrange
        $this->arrange2Robots();

        $this->mockCards->expects($this->exactly(4))->method('getCardsInLocation')
        ->withConsecutive(
            [$this->equalTo(Game::CARDS_HAND), $this->equalTo($this->robot_id)]
            , [$this->equalTo(Game::CARDS_SELECTED_HAND), $this->equalTo($this->robot_id)]
            , [$this->equalTo(Game::CARDS_HAND), $this->equalTo($this->robot_id + 1)]
            , [$this->equalTo(Game::CARDS_SELECTED_HAND), $this->equalTo($this->robot_id + 1)])
        ->willReturnOnConsecutiveCalls([$this->createCard(1)], [], [$this->createCard(2)], []);

        //$this->mockCards->expects($this->exactly(2))->method('moveCard')
        //->withConsecutive([$this->equalTo('one'), $this->equalTo(Game::CARDS_SELECTED_HAND), $this->equalTo($robot_id)], [$this->equalTo('two'), $this->equalTo(Game::CARDS_SELECTED_HAND), $this->equalTo($robot_id + 1)]);
        // Act
        $this->sut->allRobotsSelectCard();
        // Assert
    }

    private function arrange2Robots() {
        $this->robot_id = 2;
        $robot_list = [0 => [PlayerProperties::KEY_ID => $this->robot_id, PlayerProperties::KEY_POSITION => 0], 
        1 => [PlayerProperties::KEY_ID => $this->robot_id + 1, PlayerProperties::KEY_POSITION => 0]];
        $this->mockPlayerProperties->expects($this->exactly(1))->method('getRobotProperties')->will($this->returnValue($robot_list));
    }

    public function testRobotsPlayCard_NoRobots_NoPlay() {
        // Arrange
        $this->mockPlayerProperties->expects($this->exactly(1))->method('getRobotProperties')->will($this->returnValue([]));
        $this->mockCards->expects($this->exactly(0))->method('getCardsInLocation');
        // Act
        $this->sut->allRobotsPlayCard();
        // Assert
    }

    public function testRobotsPlayCard_2Robots_2Play() {
        // Arrange
        $this->arrange2Robots();
        $cards = [$this->createCard(1), $this->createCard(2)];

        $this->mockCards->expects($this->exactly(2))
        ->method('getCardsInLocation')
        ->withConsecutive([$this->equalTo(Game::CARDS_SELECTED_HAND), $this->equalTo($this->robot_id)], [$this->equalTo(Game::CARDS_SELECTED_HAND), $this->equalTo($this->robot_id + 1)])
        ->willReturnOnConsecutiveCalls([$this->createCard(1)], [$this->createCard(2)]);

        $this->mockCards->expects($this->exactly(4))
        ->method('moveCard')
        ->withConsecutive(
            [$this->equalTo($cards[0][Game::CARD_KEY_ID]), $this->equalTo(Game::CARDS_PLAYED_HAND)]
          , [$this->equalTo($cards[0][Game::CARD_KEY_ID]), $this->equalTo(Game::CARDS_HAND), $this->equalTo(-2)]
          , [$this->equalTo($cards[1][Game::CARD_KEY_ID]), $this->equalTo(Game::CARDS_PLAYED_HAND)]
          , [$this->equalTo($cards[1][Game::CARD_KEY_ID]), $this->equalTo(Game::CARDS_HAND), $this->equalTo(-2)]
        );

        $this->mock->expects($this->exactly(2))
        ->method('getObjectFromDB')
        ->willReturnOnConsecutiveCalls(['player_score' => 7], ['player_score' => 8]);

        $this->mockFields->expects($this->exactly(2))
        ->method('getID')
        ->willReturnOnConsecutiveCalls('1', '2');

        $this->mockOcean->expects($this->exactly(2))
        ->method('getSelectableFields')
        ->withConsecutive([$this->equalTo($this->robot_id), $this->equalTo(1)], [$this->equalTo($this->robot_id + 1), $this->equalTo(2)])
        ->willReturnOnConsecutiveCalls(['field_ocean_1'], ['field_ocean_2']);

        $this->mockOcean->expects($this->exactly(2))
        ->method('getReward')
        ->withConsecutive([$this->equalTo($this->robot_id), $this->equalTo(1)], [$this->equalTo($this->robot_id + 1), $this->equalTo(2)])
        ->willReturnOnConsecutiveCalls(['points' => 3], ['points' => 5]);

        $this->mockOcean->expects($this->exactly(2))
        ->method('setPlayerPosition')
        ->withConsecutive([$this->equalTo($this->robot_id), $this->equalTo(1)], [$this->equalTo($this->robot_id + 1), $this->equalTo(2)]);
        // Act
        $this->sut->allRobotsPlayCard();
        // Assert
    }

}
?>
