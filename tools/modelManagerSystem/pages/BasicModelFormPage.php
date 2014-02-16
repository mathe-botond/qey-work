<?php
namespace qeywork;

/**
 * Page part of ModelManager used to insert new data
 *
 * @author Dexx
 */
class BasicModelFormPage extends Page{
    /** @var PostForm */
    protected $form;
    
    public function __construct(
            IModelManagerFactory $factory,
            MMSFormFactory $formFactory,
            $mode, 
            $id = '-1') {
        
        $paths = $factory->getPathCollection();
        switch ($mode) {
            case ModelManagerSystem::MODE_ADD:
                $model = $factory->getInsertModel();
                $prg = new PostRedirectGetUrls(
                    $paths->additionOperation,
                    $paths->additionPage, 
                    $paths->listingPage
                );
                break;
            case ModelManagerSystem::MODE_EDIT:
                $model = $factory->getModifyModel();
                $prg = new PostRedirectGetUrls(
                    $paths->editOperation,
                    $paths->editPage, 
                    $paths->listingPage
                );
                break;
        }
        
        $this->pagename = $mode . ' ' . get_class($model);
        
        $this->form = $formFactory->getForm($model, $prg, $id);
    }
    
    public function render() {
        return $this->form->render(); 
    }
}

?>
