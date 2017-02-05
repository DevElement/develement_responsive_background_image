<?php
namespace DevElement\DevelementResponsiveBackgroundImg\ViewHelper;

/**
 * View helper: Responsive background image
 *
 * @package KoninklijkeCollective\KoningResponsiveBackgroundImg\ViewHelper
 */
class ResponsiveBackgroundImageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @var array
     */
    protected $breakPointConfig = [
        1199       => ['min-width' => '992px', 'max-width' => '1199px'],
        991        => ['min-width' => '768px', 'max-width' => '991px'],
        767        => ['min-width' => '480px', 'max-width' => '767px'],
        479        => ['max-width' => '479px'],
    ];

    /**
     * @var \TYPO3\CMS\Extbase\Service\ImageService
     * @inject
     */
    protected $imageService;

    /**
     * @param \TYPO3\CMS\Core\Resource\FileInterface $image
     * @param string $id
     * @param array $includedBreakPoints
     * @return string
     */
    public function render($image, $id, $includedBreakPoints = [1199, 991, 767, 479])
    {
        if ($image instanceof \TYPO3\CMS\Core\Resource\FileReference) {
            $css = '<style type="text/css">';

            // Render the default
            $css .= $this->renderCssTag($image, $id);

            foreach ($includedBreakPoints as $breakPoint) {
                if (isset($this->breakPointConfig[$breakPoint])) {
                    $processingInstructions = [
                        'width' => (int)$breakPoint
                    ];

                    $processedImage = $this->imageService->applyProcessingInstructions($image, $processingInstructions);
                    if ($processedImage instanceof \TYPO3\CMS\Core\Resource\ProcessedFile) {
                        $mediaQuery = '';
                        if (isset($this->breakPointConfig[$breakPoint]['min-width']) && isset($this->breakPointConfig[$breakPoint]['max-width'])) {
                            $mediaQuery = '@media (min-width: ' . $this->breakPointConfig[$breakPoint]['min-width'] . ') and (max-width: ' . $this->breakPointConfig[$breakPoint]['max-width'] . ') {';
                        } elseif (isset($this->breakPointConfig[$breakPoint]['max-width'])) {
                            $mediaQuery = '@media (max-width: ' . $this->breakPointConfig[$breakPoint]['max-width']. ') {';
                        }

                        $css .= $mediaQuery;
                        $css .= $this->renderCssTag($processedImage, $id);
                        $css .= '}';
                    }
                }
            }
            $css .= '</style>';
            $this->getTypoScriptFrontendController()->additionalHeaderData[] .= $css;
        }
        return $this->renderChildren();
    }

    /**
     * @param \TYPO3\CMS\Core\Resource\FileInterface $image
     * @param int $id
     * @return string
     */
    protected function renderCssTag(\TYPO3\CMS\Core\Resource\FileInterface $image, $id)
    {
        $css = '#' . $id . '{';
        $css .= 'background-image: url(/' . $image->getPublicUrl() . ');';
        $css .= '}';
        return $css;
    }

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}
