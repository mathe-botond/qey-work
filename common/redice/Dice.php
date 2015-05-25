<?php

/* Original Dice
 * @description 		Dice - A minimal Dependency Injection Container for PHP  
 * @author				Tom Butler tom@r.je
 * @copyright			2012-2014 Tom Butler <tom@r.je>
 * @link				http://r.je/dice.html
 * @license				http://www.opensource.org/licenses/bsd-license.php  BSD License 
 * @version				1.1.1
 * 
 * Edits:
 * @author Dexx
 */

namespace Dice;

class Dice implements IoC {
    const MATCH_ALL = '*';
    
    private $verbose = false;
    private $rules = array();
    private $instances = array();
    
    public function setVerbose($verbose) {
        $this->verbose = $verbose;
    }

    private function exists($name) {
        return array_key_exists($name, $this->instances);
    }
    
    public function assign($object) {
        $class = get_class($object);
        $this->instances[strtolower($class)] = $object; //this one can overwrite
        
        //add instance under the name of it's interfaces
        //$reflectionClass = new \ReflectionClass($class);
        //$interfaces = $reflectionClass->getInterfaceNames();
        //foreach ($interfaces as $interface) {
        //        $this->instances[$lower] = $object;
        //}
        
        //add instance under the names of its parent classes
        while (($class = get_parent_class($class)) !== null) {
            $lower = strtolower($class);
            if (! $this->exists($lower)) {
                $this->instances[$lower] = $object;
            } else {
                return; //there's already a lineage, should not overwrite
            }
        }
    }

    public function addRule($name, Rule $rule) {
        $rule->substitutions = array_change_key_case($rule->substitutions);
        $this->rules[strtolower(trim($name, '\\'))] = $rule;
    }
    
    public function getRule($name) {
        $tname = strtolower(ltrim($name, '\\'));
        if (isset($this->rules[$tname])) {
            return $this->rules[$tname];
        }

        if (strstr($name, '$') === false) {
            foreach ($this->rules as $key => $rule) {
                if ($rule->instanceOf === null && $key !== '*' && is_subclass_of($name, $key) && $rule->inherit === true) {
                    return $rule;
                }
            }
        }
        return isset($this->rules['*']) ? $this->rules['*'] : new Rule;
    }
    
    public function getRuleBuilder($name) {
        return new RuleBuilder($this, $name);
    }

    public function addRules(array $rules) {
        foreach ($rules as $name => $rule) {
            $this->addRule($name, $rule);
        }
    }

    public function create($component, array $args = array(), $forceNewInstance = false, $callback = null) {
        if ($component instanceof Instance) {
            $component = $component->name;
        }
        $component = trim($component, '\\');
        
        if (!isset($this->rules[strtolower($component)])
                && strstr($component, '$') === false
                && !class_exists($component)) {
            throw new \Exception('Class does not exist for creation: ' . $component);
        }

        if (!$forceNewInstance && isset($this->instances[strtolower($component)])) {
            return $this->instances[strtolower($component)];
        }

        $rule = $this->getRule($component);
        $className = (!empty($rule->instanceOf)) ? $rule->instanceOf : $component;
        
        if ($this->verbose) {
            echo "$className(";
        }
        $share = $this->getParams($rule->shareInstances);
        $params = $this->getMethodParams($className, '__construct', $rule, array_merge($share, $args, $this->getParams($rule->constructParams)), $share);
        if ($this->verbose) {
            echo ") ";
        }
        
        if (is_callable($callback, true)) {
            call_user_func_array($callback, array($params));
        }
        
        if (count($params) > 0) {
            $reflectionClass = new \ReflectionClass($className);
            $object = $reflectionClass->newInstanceArgs($params);
        } else {
            $object = new $className;
        }
            
        if ($rule->shared == true) {
            $this->assign($object);
        }
        foreach ($rule->call as $call) {
            call_user_func_array(array($object, $call[0]), $this->getMethodParams($className, $call[0], $rule, array_merge($this->getParams($call[1]), $args)));
        }
        return $object;
    }

    private function getParams(array $params = array(), array $newInstances = array()) {
        for ($i = 0; $i < count($params); $i++) {
            if ($params[$i] instanceof Instance) {
                $params[$i] = $this->create($params[$i]->name, array(), in_array(strtolower($params[$i]->name), array_map('strtolower', $newInstances)), null);
            }
        }
        return $params;
    }

    private function getMethodParams($className, $method, Rule $rule, array $args = array(), array $share = array()) {
        if (!method_exists($className, $method)) {
            return array();
        }
        $reflectionMethod = new \ReflectionMethod($className, $method);
        $params = $reflectionMethod->getParameters();
        $parameters = array();
        foreach ($params as $param) {
            foreach ($args as $argName => $arg) {
                $class = $param->getClass();
                if ($class && is_object($arg) && $arg instanceof $class->name) {
                    $parameters[] = $arg;
                    unset($args[$argName]);
                    continue 2;
                }
            }
            $paramClassName = $param->getClass() ? strtolower($param->getClass()->name) : false;

            if ($paramClassName && isset($rule->substitutions[$paramClassName])) {
                $parameters[] = is_string($rule->substitutions[$paramClassName]) ? new Instance($rule->substitutions[$paramClassName]) : $rule->substitutions[$paramClassName];
            } else if ($paramClassName && strstr($paramClassName, '$') === false && class_exists($paramClassName)) {
                $parameters[] = $this->create($paramClassName, $share, in_array($paramClassName, array_map('strtolower', $rule->newInstances)), null);
            } else if (is_array($args) && count($args) > 0) {
                $parameters[] = array_shift($args);
            } else {
                $parameters[] = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
            }
        }
        return $this->getParams($parameters, $rule->newInstances);
    }

    public function getExistingInstances() {
        return $this->instances;
    }
}

class Rule {
    public $ioc;
    public $key;
    
    public $shared = false;
    public $constructParams = array();
    public $substitutions = array();
    public $newInstances = array();
    public $instanceOf;
    public $call = array();
    public $inherit = true;
    public $shareInstances = array();
}

class RuleBuilder {

    /** @var Dice */
    private $ioc;
    private $key;
    /** @var Rule */
    private $rule;

    public function __construct(Dice $ioc, $key) {
        $this->ioc = $ioc;
        $this->key = $key;
        $this->rule = clone($ioc->getRule($key));
    }
    
    /**
     * @param bool $shared Makes this instance shared
     * @return \Dice\Rule
     */
    public function setShared($shared) {
        $this->rule->shared = $shared;
        return $this;
    }

    /**
     * @param array $params List of constructor parameters
     * @return \Dice\Rule
     */
    public function setConstructParams(array $params) {
        $this->rule->constructParams = $params;
        return $this;
    }
    
    /**
     * @param array $params List of constructor parameters
     * @return \Dice\Rule
     */
    public function setCall($function, array $arguments) {
        $this->rule->call[] = array($function, $arguments);
        return $this;
    }
    
    public function addSubstitution($class, $instance) {
        $class = strtolower( trim($class, '\\') );
        $this->rule->substitutions[$class] = $instance;
        return $this;
    }
    
    public function addDecoratingSubstitution($class, $decorator) {
        $class = strtolower( trim($class, '\\') );
        if (! isset($this->rule->substitutions[$class])) {
            throw new \qeywork\ArgumentException("$class doesn't have a substitution yet, can't decorate.");
        }
        $seed = $this->rule->substitutions[$class];
        $this->addSubstitution($class, $decorator);
        
        $this->ioc->getRuleBuilder($decorator)
            ->addSubstitution($class, $seed)
            ->save();
        return $this;
    }
    
    public function addSubstitutions($substitutions) {
        array_merge($this->rule->substitutions, $substitutions);
        return $this;
    }
    
    public function addNewInstances($instances) {
        array_merge($this->rule->newInstances, $instances);
        return $this;
    }
    
    public function setInstanceOf($instance) {
        $this->rule->instanceOf = $instance;
        return $this;
    }
    
    public function setShareInstances(array $sharedInstances) {
        $this->rule->shareInstances = $sharedInstances;
        return $this;
    }
    
    public function getRule() {
        return $this->rule;
    }
    
    public function save() {
        $this->ioc->addRule($this->key, $this->rule);
    }
}

class Instance {
    public $name;

    public function __construct($instance) {
        $this->name = $instance;
    }
}
