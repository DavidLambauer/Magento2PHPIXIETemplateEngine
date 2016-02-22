<?php

namespace N98\Phpixie\TemplateEngine;

use Magento\Framework\View\TemplateEngineInterface;
use PHPixie\Filesystem;
use PHPixie\Slice;
use PHPixie\Template;

class Phpixie implements TemplateEngineInterface
{
    /**
     * @var Slice
     */
    private $slice;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Phpixie constructor.
     *
     * @param Slice $slice
     * @param Filesystem $filesystem
     */
    public function __construct(
        Slice $slice,
        Filesystem $filesystem
    ) {
        $this->slice = $slice;
        $this->filesystem = $filesystem;
    }

    /**
     * Render template
     *
     * Render the named template in the context of a particular block and with
     * the data provided in $vars.
     *
     * @param \Magento\Framework\View\Element\BlockInterface $block
     * @param string $templateFile
     * @param array $dictionary
     * @return string rendered template
     */
    public function render(
        \Magento\Framework\View\Element\BlockInterface $block,
        $templateFile,
        array $dictionary = []
    ) {
        //Configuration
        $locatorConfig = $this->slice->arrayData(array(
            'directory' => ''
        ));
        $templateConfig = $this->slice->arrayData(array(
            //Let's just use defaults
        ));

        //Build dependencies
        $root = $this->filesystem->root(dirname($templateFile));
        $locator = $this->filesystem->buildlocator($locatorConfig, $root);

        //And the Template library itself
        $template = new Template($this->slice, $locator, $templateConfig);

        return $template->render(basename($templateFile), $block->getData());
    }
}

