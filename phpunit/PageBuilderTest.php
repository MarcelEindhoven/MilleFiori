<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/PageBuilder.php');

class PageInterface {
    public function begin_block() {}
    public function reset_subblocks() {}
    public function insert_block() {}
}

class PageBuilderTest extends TestCase{
    protected PageBuilder $builder;

    protected function setUp(): void {
        $this->mock = $this->getMockBuilder(PageInterface::class)->getMock();
        $this->builder = new PageBuilder();
        $this->builder->setPage($this->mock);
    }

    public function testEmptyHeaderOnly() {
        // Arrange
        // Act
        // Assert
    }
}
?>
