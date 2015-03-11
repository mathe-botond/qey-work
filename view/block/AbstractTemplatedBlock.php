<?php
namespace qeywork;

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
    
    protected function processTemplates($template) {
        foreach ($this->getChildren() as $key => $child) {
            if ($child instanceof IRenderable) {
                $child = $child->render();
                if ($child instanceof IHtmlEntity) {
                    $child .= '';
                }
            }
            $templateKey = '{' . $key . '}';
            $template = str_replace($templateKey, $child, $template);
        }
        return $template;
    }

    public function render() {
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
        $processed = $this->processTemplates($template);
        
        $newTemplate = $this->afterRender($processed);
        return new HtmlWrapperNode($newTemplate);
    }
    
    protected function afterRender($html) {
        return $html;
    } 
}
