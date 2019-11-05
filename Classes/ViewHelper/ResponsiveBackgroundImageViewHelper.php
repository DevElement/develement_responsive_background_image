<?php
namespace DevElement\DevelementResponsiveBackgroundImage\ViewHelper;

use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Service\ImageService;

/**
 * View helper: Responsive background image
 *
 * @package DevElement\DevelementResponsiveBackgroundImage\ViewHelper
 */
class ResponsiveBackgroundImageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{
    /**
     * @var array
     */
    protected $breakPointConfig = [
        'lg'        => 1920,
        'md'        => 1199,
        'sm'        => 991,
        'xs'        => 767,
        'low-res'   => 50
    ];

    /**
     * @var ImageService
     */
    protected $imageService;

    /**
     * @param ImageService $imageService
     * @return void
     */
    public function injectImageService(ImageService $imageService): void
    {
        $this->imageService = $imageService;
    }

    /**
     * @var ResourceFactory
     */
    protected $resourceFactory;

    /**
     * @param ResourceFactory $resourceFactory
     * @return void
     */
    public function injectResourceFactory(ResourceFactory $resourceFactory): void
    {
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * @var string
     */
    protected $tagName = 'div';

    /**
     * Initialize arguments.
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerArgument('image', 'mixed', 'Image', true);
        $this->registerArgument('includedBreakPoints', 'array', 'Included break points', false, ['lg', 'md', 'sm', 'xs', 'low-res']);
    }

    /**
     * @return string
     */
    public function render()
    {
        if (!($this->arguments['image'] instanceof \TYPO3\CMS\Core\Resource\FileReference)) {
            // FlexForm compatibility
            $this->arguments['image'] = $this->resourceFactory->retrieveFileOrFolderObject($this->arguments['image']);
        }

        if ($this->arguments['image'] instanceof \TYPO3\CMS\Core\Resource\FileInterface) {
            foreach ($this->arguments['includedBreakPoints'] as $breakPoint) {
                if (array_key_exists($breakPoint, $this->breakPointConfig)) {
                    $processingInstructions = [
                        'width' => (int)$this->breakPointConfig[$breakPoint]
                    ];

                    $processedImage = $this->imageService->applyProcessingInstructions($this->arguments['image'], $processingInstructions);
                    if ($processedImage instanceof \TYPO3\CMS\Core\Resource\ProcessedFile) {
                        if ($breakPoint === 'low-res') {
                            // Low res will be rendered inline as base64
                            $base64 = 'data:' . $this->arguments['image']->getMimeType() . ';base64,' . base64_encode($processedImage->getContents());
                            $this->tag->addAttribute('style', 'background-image: url(' . $base64 . ')');
                        } else {
                            // All others will be data attributes
                            $this->tag->addAttribute('data-' . $breakPoint, '/' . $processedImage->getPublicUrl());
                        }
                    }
                }
            }
        }

        $this->tag->forceClosingTag(true);
        $this->tag->setContent($this->renderChildren());
        return $this->tag->render();
    }
}
