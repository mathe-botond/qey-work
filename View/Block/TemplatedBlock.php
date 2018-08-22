<?php
namespace QeyWork\View\Block;
use QeyWork\Common\Addresses\Path;

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
