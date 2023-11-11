<?php
namespace NieuwenhovenGames\BGA;
/**
 * Check event subscription and unsubscription
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../../export/modules/BGA/EventEmitter.php');
include_once(__DIR__.'/../../../export/modules/BGA/RewardHandler.php');
include_once(__DIR__.'/../../../export/modules/BGA/SubscribedAction.php');

class TestActionHandleReward extends \NieuwenhovenGames\BGA\SubscribedAction {
    protected $field_selection_handler = null;
    protected bool $select_extra_card = false;

    public function setHandler($field_selection_handler) : TestActionHandleReward {
        $this->field_selection_handler = $field_selection_handler;
        return $this;
    }

    public function selectExtraCard() {
        $this->select_extra_card = true;
    }

    public function execute() : TestActionHandleReward {
        $this->subscribe(IntegrationTestSubscribedAction::REWARD_EVENT_NAME, 'selectExtraCard');
        $this->field_selection_handler->handle();

        return $this;
    }

    public function getTransitionName() : string {
        return $this->select_extra_card ? IntegrationTestSubscribedAction::TRANSITION_AFTER_REWARD : 'turnEnded';
    }
}

class TestGameState {
    public string $transition_name = '';
    public function nextState($transition_name) {
        $this->transition_name = $transition_name;
    }
}

class TestDomainWithReward {
    protected ?RewardHandler $reward_handler = null;

    public function setRewardHandler($reward_handler) : TestDomainWithReward {
        $this->reward_handler = $reward_handler;
        return $this;
    }

    public function handle() {
        $this->reward_handler->gainedAdditionalReward(1, IntegrationTestSubscribedAction::REWARD_EVENT_NAME);
    }
}

class IntegrationTestSubscribedAction extends TestCase{
    const REWARD_EVENT_NAME = 'select_extra_card';
    const TRANSITION_AFTER_REWARD = 'selectExtraCard';
    public function testAction_Event_CorrespondingTransition() {
        // Arrange
        $game_state = new TestGameState();
        $sut_action = new TestActionHandleReward($game_state);

        $sut_emitter = new EventEmitter();
        $sut_action->setEventEmitter($sut_emitter);

        $sut_reward_handler = new RewardHandler();
        $sut_reward_handler->setEventEmitter($sut_emitter);

        $handler = new TestDomainWithReward();
        $handler->setRewardHandler($sut_reward_handler);
        $sut_action->setHandler($handler);

        // Act
        $sut_action->execute();
        $sut_action->nextState();

        // Assert
        $this->assertEquals(IntegrationTestSubscribedAction::TRANSITION_AFTER_REWARD, $game_state->transition_name);
    }
}
?>
