<?php
namespace Dice;

/**
 * @author Dexx
 */
interface IoC {
    /**
     * 
     * @param string $verbose The container outputs the dependency
     * tree it creates
     */
    public function setVerbose($verbose);
    
    /**
     * @param Object $object Assign an instance
     * that can be used in the dependency tree
     */
    public function assign($object);
    
    /**
     * @param string $name Class name to assign rule to 
     * You can also use '*' to assign  a single rule to every component
     * @param Rule $rule
     */
    public function addRule($name, Rule $rule);
    
    /**
     * @param string $name Name of the rule to return
     * @return Rule Description
     */
    public function getRule($name);
    
    /**
     * @param string $name Name of the rule to return
     * @return RuleBuilder Description
     */
    public function getRuleBuilder($name);
    
    /**
     * Create a component from a class name
     * @param string $component Name of the class to instantiate
     * @param array $args
     * @param bool $forceNewInstance forces a new instance if there is a rule
     * @param callable $callback
     * for that component to be shared and there is already an instance in the
     * dependency tree
     */
    public function create($component, array $args = array(), $forceNewInstance = false, $callback = null);
}
