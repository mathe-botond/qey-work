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
        
        $matches = array();
        preg_match_all('/\{this\.([a-zA-Z0-9]*)\}/', $template, $matches);
        
        if (isset($matches[1])) {
            foreach ($matches[1] as $key => $child) {
                if (property_exists($this, $child)) {
                    if ($this->$child != null) {
                        $this->beforeChildRender($this->$child);
                        $renderedChild = $this->$child->render();
                        $template = str_replace(
                            $matches[0][$key],
                            $renderedChild,
                            $template
                        );
                    } else {
                        $template = str_replace(
                            $matches[0][$key],
                            '',
                            $template
                        );
                    }
                } else {
                    throw new TemplateException(
                        'Class doesn\'t have a child: ' . $child
                    );
                }
            }
        }
        $newTemplate = $this->afterRender($template);
        return $newTemplate;
    }
    
    protected function afterRender($html) {
        return $html;
    } 
}

?>