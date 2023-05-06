<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/ActionsAndStates/ActionPlayerPlaysCard.php');
include_once(__DIR__.'/../../export/modules/ActionsAndStates/UpdateCards.php');
include_once(__DIR__.'/../../export/modules/CurrentData/CurrentData.php');
include_once(__DIR__.'/../../export/modules/BGA/GameStateInterface.php');

class ActionPlayerPlaysCardTest extends TestCase{

    protected ActionPlayerPlaysCard $sut;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\GameStateInterface::class);
        $this->sut = ActionPlayerPlaysCard::create($this->mock_gamestate);

        $this->mock_cards = $this->createMock(UpdateCards::class);
        $this->sut->setCardsHandler($this->mock_cards);

        $this->mock_data_handler = $this->createMock(CurrentData::class);
        $this->sut->setDataHandler($this->mock_data_handler);

        $this->player_id = 55;
        $this->sut->setCurrentPlayerID($this->player_id);
    }

    public function testExecute_PlayerID_PlaySelectedCard() {
        // Arrange
        $this->mock_cards->expects($this->exactly(1))->method('playSelectedCard')->with($this->player_id);
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute_PlayerID_SelectableFieldIDs() {
        // Arrange
        $this->mock_data_handler->expects($this->exactly(1))->method('getSelectableFieldIDs')->with($this->player_id);
        // Act
        $this->sut->execute();
        // Assert
    }
}
?>
