<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/ActionsAndStates/ActionRobotSelectsCard.php');

include_once(__DIR__.'/../../export/modules/ActionsAndStates/Robot.php');

include_once(__DIR__.'/../../export/modules/BGA/GameStateInterface.php');
include_once(__DIR__.'/../../export/modules/BGA/CurrentPlayerOrRobot.php');

class ActionRobotSelectsCardTest extends TestCase{
    const DEFAULT_CARD_ID = 3;

    protected ActionRobotSelectsCard $sut;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\GameStateInterface::class);
        $this->sut = ActionRobotSelectsCard::create($this->mock_gamestate);

        $this->mock_cards = $this->createMock(UpdateCards::class);
        $this->sut->setCardsHandler($this->mock_cards);

        $this->mock_robot = $this->createMock(Robot::class);
        $this->sut->setRobot($this->mock_robot);
    }

    public function testExecute_Always_SelectCard() {
        // Arrange
        $this->player_id = 5;
        $this->card = [CurrentData::CARD_KEY_ID => ActionRobotSelectsCardTest::DEFAULT_CARD_ID];

        $this->mock_robot->expects($this->exactly(1))->method('selectCard')->will($this->returnValue($this->card));
        $this->mock_robot->expects($this->exactly(1))->method('getPlayerID')->will($this->returnValue($this->player_id));

        $this->mock_cards->expects($this->exactly(1))->method('moveFromHandToSelected')->with(ActionRobotSelectsCardTest::DEFAULT_CARD_ID, $this->player_id);
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testNextState_Always_NextState() {
        // Arrange
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState');
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>

