<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/Game.php');
include_once(__DIR__.'/../export/modules/PlayerProperties.php');
include_once(__DIR__.'/../export/modules/Robot.php');

include_once(__DIR__.'/../export/modules/BGA/CardsInterface.php');
include_once(__DIR__.'/../export/modules/BGA/DatabaseInterface.php');
include_once(__DIR__.'/../export/modules/BGA/NotifyInterface.php');

class GameTest extends TestCase{
    public function setup() : void {
        $this->mock = $this->createMock(\NieuwenhovenGames\BGA\DatabaseInterface::class);
        $this->sut = Game::create($this->mock);

        $this->mockNotifyInterface = $this->createMock(\NieuwenhovenGames\BGA\NotifyInterface::class);
        $this->sut->setNotifyInterface($this->mockNotifyInterface);

        $this->mockPlayerProperties = $this->createMock(PlayerProperties::class);
        $this->sut->setPlayerProperties($this->mockPlayerProperties);
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
        return ['id' => $cardID];
    }

    public function testRobotsSelectCard_OneCard2Robots_Select2Cards() {
        // Arrange
        $robot_id = 2;
        $robot_list = [0 => [PlayerProperties::KEY_ID => $robot_id, PlayerProperties::KEY_POSITION => 0], 
        1 => [PlayerProperties::KEY_ID => $robot_id + 1, PlayerProperties::KEY_POSITION => 0]];
        $this->mockPlayerProperties->expects($this->exactly(1))->method('getRobotProperties')->will($this->returnValue($robot_list));

        $this->mockCards = $this->createMock(\NieuwenhovenGames\BGA\CardsInterface::class);
        $this->mockCards->expects($this->exactly(4))->method('getCardsInLocation')
        ->withConsecutive([
            $this->equalTo(Game::CARDS_HAND), $this->equalTo($robot_id)]
            , [$this->equalTo(Game::CARDS_SELECTED_HAND), $this->equalTo($robot_id)]
            , [$this->equalTo(Game::CARDS_HAND), $this->equalTo($robot_id + 1)]
            , [$this->equalTo(Game::CARDS_SELECTED_HAND), $this->equalTo($robot_id + 1)])
        ->willReturnOnConsecutiveCalls([$this->createCard(1)], [], [$this->createCard(2)], []);

        //$this->mockCards->expects($this->exactly(2))->method('moveCard')
        //->withConsecutive([$this->equalTo('one'), $this->equalTo(Game::CARDS_SELECTED_HAND), $this->equalTo($robot_id)], [$this->equalTo('two'), $this->equalTo(Game::CARDS_SELECTED_HAND), $this->equalTo($robot_id + 1)]);
        // Act
        $this->sut->setCards($this->mockCards);
        $this->sut->allRobotsSelectCard();
        // Assert
    }

}
?>
