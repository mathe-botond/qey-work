<?php
namespace qeywork;

/**
 * @author Dexx
 */
abstract class TemplatedBlock extends Container {
    /**
     * Provide a local Path to the template file
     * @return Path
     */
    
    protected abstract function provideTemplateFile();
    
    protected function beforeRender() {}
    
    protected function beforeChildRender(IRenderable $child) {}
    
    protected function processTemplates($template) {
        $matches = array();
        preg_match_all('/\{([a-zA-Z0-9]*)\}/', $template, $matches);
        $children = $this->getChildren();
        if (isset($matches[1])) {
            foreach ($matches[1] as $key => $childName) {
                if (array_key_exists($childName, $children)) {
                    $childBlock = $children[$childName];
                    $this->beforeChildRender($childBlock);
                    
                    $renderedChild = $childBlock->render();
                    $template = str_replace(
                        $matches[0][$key],
                        $renderedChild,
                        $template
                    );
                } else {
                    throw new TemplateException(
                        'Class doesn\'t have a child: ' . $childName
                    );
                }
            }
        }
        return $template;
    }

    public function render() {
        $this->beforeRender();
        $templateFile = $this->provideTemplateFile();
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
        $precessed = $this->processTemplates($template);
        
        $newTemplate = $this->afterRender($precessed);
        $htmledTemplate = new HtmlWrapperNode($newTemplate);
        return $htmledTemplate;
    }
    
    protected function afterRender($html) {
        return $html;
    } 
}
