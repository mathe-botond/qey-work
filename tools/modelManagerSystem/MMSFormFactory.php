<?php
namespace qeywork;
/**
 * @author Dexx
 */
class MMSFormFactory {
    protected $paths;
    protected $resources;
    protected $meta;
    
    public function __construct(MMSPathCollection $paths,
            ResourceCollection $resources,
            QeyMeta $meta) {
        $this->paths = $paths;
        $this->resources = $resources;
        $this->meta = $meta;
    }
    
    public function getForm(Model $model, PostRedirectGetUrls $prg, $id = '-1') {
        $formFactory = new PostFormFactory($this->resources->getSession());
        
        if ($id != -1) {
            $persistance = new ModelDbController($model, $this->resources->getDb());
            $persistance->load($id);
        }
        
        return $formFactory->createForm($this->resources, $this->meta, $model, $prg);
    }
}
