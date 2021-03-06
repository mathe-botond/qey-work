<?php
namespace QeyWork\View\Block;
use QeyWork\Common\Addresses\Path;
use QeyWork\Common\ArgumentException;
use QeyWork\View\Html\HtmlBuilder;
use QeyWork\View\Html\HtmlWrapperNode;
use QeyWork\View\Html\IHtmlObject;
use QeyWork\View\IRenderable;

/**
 * @author Dexx
 */
abstract class AbstractTemplatedBlock extends Container {
    /**
     * Provide a local Path to the template file
     * @return Path
     */
    
    public function addString($key, $value) {
        $this->children[$key] = $value;
    }
    
    protected abstract function provideTemplateFile();
    
    protected function beforeRender() {}
    
    protected function beforeChildRender(IRenderable $child) {}
    
    protected function processTemplates(HtmlBuilder $h, $template) {
        foreach ($this->getChildren() as $key => $child) {
            if ($child instanceof IRenderable) {
                $child = $child->render($h);
                if ($child instanceof IHtmlObject) {
                    $child .= '';
                }
            }
            $templateKey = '{' . $key . '}';
            $template = str_replace($templateKey, $child, $template);
        }
        return $template;
    }

    public function render(HtmlBuilder $h) {
        $this->beforeRender();
        $templateFile = $this->provideTemplateFile();
        if (! $templateFile instanceof Path) {
            throw new ArgumentException(
               'Overload of provideTemplateFile must return a Path type'
            );
        }
        
        if (! file_exists($templateFile)) {
            throw  new \BadMethodCallException(
                'Template file "' . $templateFile . '" was not found'
            );
        }
        
        $template = file_get_contents($templateFile);
        $processed = $this->processTemplates($h, $template);
        
        $newTemplate = $this->afterRender($processed);
        return new HtmlWrapperNode($newTemplate);
    }
    
    protected function afterRender($html) {
        return $html;
    } 
}
