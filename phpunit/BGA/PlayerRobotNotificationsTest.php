<?php
namespace NieuwenhovenGames\BGA;
/**
 * Check event subscription and unsubscription
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/PlayerRobotNotifications.php');

include_once(__DIR__.'/../../export/modules/BGA/Notifications.php');

class TestPlayerRobotNotifications extends PlayerRobotNotifications {
}

class PlayerRobotNotificationsTest extends TestCase{
    protected PlayerRobotNotifications $sut;

    protected function setUp(): void {
        $this->sut = new PlayerRobotNotifications();

        $this->mock_notifications = $this->createMock(Notifications::class);
        $this->sut->setNotificationsHandler($this->mock_notifications);

        $this->notification_type = 'notification_type';
        $this->notification_log = 'notification_log';
        $this->notification_args = ['notification_args'];
    }

    public function testnotifyAllPlayers_Always_PassArguments() {
        // Arrange
        $this->mock_notifications->expects($this->exactly(1))->method('notifyAllPlayers')->with($this->notification_type, $this->notification_log, $this->notification_args);
        // Act
        $this->sut->notifyAllPlayers($this->notification_type, $this->notification_log, $this->notification_args);
        // Assert
    }
}
?>
