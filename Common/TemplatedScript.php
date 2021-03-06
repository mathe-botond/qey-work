<?php
namespace QeyWork\Common;
use QeyWork\Common\Addresses\Path;

/**
 * Create a script using a template system
 * This will output a costumiseble JavaScript file
 *
 * @author Dexx
 */
abstract class TemplatedScript implements IAction {
    protected abstract function getTemplateFile();

    protected abstract function getTemplateValue($token);
    
    protected function getTemplateRegexString() {
        return '/0\/\*\{([a-zA-Z0-9]*)\}\*\//';
    }
    
    protected function processTemplates($template) {
        $matches = array();
        preg_match_all($this->getTemplateRegexString(), $template, $matches);
        
        if (isset($matches[1])) {
            foreach ($matches[1] as $key => $token) {
                $value = $this->getTemplateValue($token);
                $template = str_replace(
                    $matches[0][$key],
                    $value,
                    $template
                );
            }
        }
        return $template;
    }
    
    public function execute() {
        $templateFile = $this->getTemplateFile();
        if (! $templateFile instanceof Path) {
            throw new ArgumentException(
               'Overload of provideTemaplteFile must return a Path type'
            );
        }
        
        if (! file_exists($templateFile)) {
            throw  new \BadMethodCallException(
                'Template file "' . $templateFile . '" was not found'
            );
        }
        
        $template = file_get_contents($templateFile);
        return $this->processTemplates($template);
    }
}
