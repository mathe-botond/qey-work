<?php
namespace app;

/**
 * Description of DIIntegrationtest
 *
 * @author Dexx
 */

class DiTestBar {
    public static function getClass() {
        return get_called_class();
    }
}

class DiTestFoo extends DiTestBar {    
    /**
     * @var DiTestBar
     */
    public $dependency;

    public function __construct(DiTestBar $dependency) {
        $this->dependency = $dependency;
    }
}

class DiTestFooBar extends DiTestFoo {}

class DIIntegrationTest extends \PHPUnit_Framework_TestCase {
    public function testContainer() {
        $container = new \Dice\Dice();
        $loadedObject = $container->create(DiTestFoo::getClass());
        
        $this->assertEquals(DiTestBar::getClass(), 'app\DiTestBar');
        $this->assertEquals(get_class($loadedObject->dependency), DiTestBar::getClass());
    }
    
    public function testSameDependency() {
        $container = new \Dice\Dice();
        
        //Create a rule that all objects are shared by default
        $rule = new \Dice\Rule();
        $rule->shared = true;
        $container->addRule('*', $rule);
        
        //test the rule
        $loadedObject1 = $container->create(DiTestFoo::getClass());
        $loadedObject2 = $container->create(DiTestFooBar::getClass());
        
        $this->assertSame($loadedObject1->dependency, $loadedObject2->dependency);
    }
}
