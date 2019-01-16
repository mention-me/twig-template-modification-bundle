<?php

namespace Maba\Tests\Fixtures;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Maba\Bundle\TwigTemplateModificationBundle\Entity\TemplateContext;
use Maba\Bundle\TwigTemplateModificationBundle\Service\NodeReplaceHelper;
use Maba\Bundle\TwigTemplateModificationBundle\Service\TwigNodeReplacerInterface;
use Symfony\Bundle\AsseticBundle\Twig\AsseticNode;
use Twig_Node as Node;

class JavascriptBlockReplacer implements TwigNodeReplacerInterface
{
    private $nodeReplaceHelper;

    public function __construct(NodeReplaceHelper $nodeReplaceHelper)
    {
        $this->nodeReplaceHelper = $nodeReplaceHelper;
    }

    public function replace(Node $node, TemplateContext $context)
    {
        if ( ! $node instanceof AsseticNode) {
            return null;
        }

        /** @var AssetCollection $asset */
        $asset = $node->getAttribute('asset');

        // get the source path (the path from the bundle root directory) for each asset in the javascripts tag
        $sourcePaths = array_map(
            function (FileAsset $fileAsset) {
                return $fileAsset->getSourcePath();
            },
            $asset->all()
        );

        $sourcePath = implode(', ', $sourcePaths);

        return <<<HTML
<script type="text/javascript" src="{$sourcePath}"></script>

HTML;
    }
}
