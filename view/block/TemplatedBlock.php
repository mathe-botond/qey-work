<?php
namespace qeywork;

/**
 * @author Dexx
 */
class TemplatedBlock extends AbstractTemplatedBlock {
    /**
     * @var Path
     */
    private $templateFile;

    public function __construct(Path $templateFile) {
        $this->templateFile = $templateFile;
    }

    protected function provideTemplateFile() {
        return $this->templateFile;
    }
}
