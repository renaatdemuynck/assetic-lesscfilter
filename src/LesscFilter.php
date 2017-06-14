<?php
namespace RDM\Assetic\Filter;

use Assetic\Filter\BaseProcessFilter;
use Assetic\Asset\AssetInterface;
use Assetic\Exception\FilterException;

/**
 * Lessc filter
 */
class LesscFilter extends BaseProcessFilter
{

    private $lesscBin;

    public function __construct($lesscBin = '/usr/bin/lessc')
    {
        $this->lesscBin = $lesscBin;
    }

    public function filterLoad(AssetInterface $asset)
    {}

    public function filterDump(AssetInterface $asset)
    {
        $pb = $this->createProcessBuilder([$this->lesscBin, '-']);
        $pb->add('--include-path=' . $asset->getSourceDirectory());
        $proc = $pb->getProcess();
        $proc->setInput($asset->getContent());
        
        $code = $proc->run();
        
        if ($code !== 0) {
            throw FilterException::fromProcess($proc)->setInput($asset->getContent());
        }
        
        $asset->setContent($proc->getOutput());
    }
}
