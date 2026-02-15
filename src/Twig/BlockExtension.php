<?php

namespace App\Twig;

use App\Entity\Block;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BlockExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_block', [$this, 'renderBlock'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function renderBlock(Environment $env, Block $block): string
    {
        $templatePath = sprintf('blocks/%s.html.twig', $block->getType());
        $defaultTemplate = 'blocks/default.html.twig';

        // Vérifier si le template existe
        $templateExists = $env->getLoader()->exists($templatePath);

        if (!$templateExists) {
            $templatePath = $defaultTemplate;
        }

        return $env->render($templatePath, ['block' => $block]);
    }
}
