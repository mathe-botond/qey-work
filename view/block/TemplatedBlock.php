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
        foreach ($this->getChildren() as $key => $child) {
            $renderedChild = $child->render();
            if ($renderedChild instanceof IHtmlEntity) {
                $renderedChild .= '';
            }
            $templateKey = "{{$key}}";
            $count = 0;
            $template = str_replace($templateKey, $renderedChild, $template, $count);
            if ($count == 0) {
                throw new TemplateException("Template must contain '{{$key}}'");
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
