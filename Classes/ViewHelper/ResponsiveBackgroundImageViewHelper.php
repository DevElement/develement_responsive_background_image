<?php
namespace DevElement\DevelementResponsiveBackgroundImage\ViewHelper;

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
        'lg'        => null,
        'md'        => 1199,
        'sm'        => 991,
        'xs'        => 767,
        'low-res'   => 50
    ];

    /**
     * @var \TYPO3\CMS\Extbase\Service\ImageService
     * @inject
     */
    protected $imageService;

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
    }

    /**
     * @param \TYPO3\CMS\Core\Resource\FileInterface $image
     * @param array $includedBreakPoints
     * @return string
     */
    public function render($image, $includedBreakPoints = ['lg', 'md', 'sm', 'xs', 'low-res'])
    {
        if ($image instanceof \TYPO3\CMS\Core\Resource\FileReference) {
            foreach ($includedBreakPoints as $breakPoint) {
                if (array_key_exists($breakPoint, $this->breakPointConfig)) {
                    if ($breakPoint === 'lg') {
                        // Original is lg
                        $this->tag->addAttribute('data-lg', '/' . $image->getPublicUrl());
                    } else {
                        $processingInstructions = [
                            'width' => (int)$this->breakPointConfig[$breakPoint]
                        ];

                        $processedImage = $this->imageService->applyProcessingInstructions($image, $processingInstructions);
                        if ($processedImage instanceof \TYPO3\CMS\Core\Resource\ProcessedFile) {
                            if ($breakPoint === 'low-res') {
                                // Low res will be rendered inline as base64
                                $base64 = 'data:' . $image->getMimeType() . ';base64,' . base64_encode($processedImage->getContents());
                                $this->tag->addAttribute('style', 'background-image: url(' . $base64 . ')');
                            } else {
                                // All others will be data attributes
                                $this->tag->addAttribute('data-' . $breakPoint, '/' . $processedImage->getPublicUrl());
                            }
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
