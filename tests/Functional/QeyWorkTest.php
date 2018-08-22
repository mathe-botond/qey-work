<?php
namespace qeyworktest;
use qeywork as q;

/**
 * @author Dexx
 */
class TestPage extends q\Page {
    const NAME = 'test';
    
    public function getTitle() {
        return 'Test Page';
    }

    public function render(q\HtmlBuilder $h) {
        return new q\TextNode('Hello World');
    }
}

class TestAction implements q\IAction {
    const NAME = 'test';
    const OUTPUT = 'Action executed';
    public function execute() {
        return 'Action executed';
    }
}

/**
 * @author Dexx
 */
class QeyWorkTest extends \PHPUnit_Framework_TestCase {
    private $qeywork;
    public function setUp() {
        $loc = getLocations();
        $this->qeywork = new \qeywork\QeyWork($loc, TestPage::class, true);
        $this->qeywork->getAssembler()->getIoC()->setVerbose(true);
        $this->qeywork->setGlobals(getTestGlobals(''));
        $this->qeywork->build();
    }
    
    public function testpage() {
        $output = $this->qeywork->render();
        $outputString = $output->toString();
        $this->assertContains('Hello World', $outputString);
    }
    
    public function testAction() {
        /* @var $globals TestGlobals */
        $globals = $this->qeywork->getGlobals();
        $globals->editValue(\qeywork\Globals::KEY_REQUEST, '_target', TestAction::NAME);
        
        $this->qeywork->registerActionClass(TestAction::NAME, TestAction::class);
        $result = $this->qeywork->run();
        $this->assertEquals($result, TestAction::OUTPUT);
    }
}
