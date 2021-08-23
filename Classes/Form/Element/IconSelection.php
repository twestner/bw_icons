<?php

namespace Blueways\BwIcons\Form\Element;

use Blueways\BwIcons\Utility\HelperUtility;
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class IconSelection extends AbstractFormElement
{

    public function render()
    {
        $resultArray = $this->initializeResultArray();

        $fieldWizardResult = $this->renderFieldWizard();
        $fieldWizardHtml = $fieldWizardResult['html'];
        $resultArray = $this->mergeChildReturnIntoExistingResult($resultArray, $fieldWizardResult, false);
        $parameterArray = $this->data['parameterArray'];
        $pid = $this->data['tableName'] === 'pages' ? $this->data['vanillaUid'] : $this->data['databaseRow']['pid'];
        $config = $parameterArray['fieldConf']['config'];

        /** @var HelperUtility $helperUtil */
        $helperUtil = GeneralUtility::makeInstance(HelperUtility::class, $pid);
        $styleSheets = $helperUtil->getStyleSheets();
        $resultArray['stylesheetFiles'] = $styleSheets;
        $jsSheetArray = implode(',', array_map(function ($sheet) {
            return '"' . $sheet . '"';
        }, $styleSheets));

        $resultArray['requireJsModules'][] = ['TYPO3/CMS/BwIcons/IconSelection' => 'function(IconSelection){top.require([], function() { IconSelection.init(' . $pid . ', "' . $parameterArray['itemFormElName'] . '", [' . $jsSheetArray . ']); }); }'];

        $resultArray['additionalHiddenFields'][] = '<input type="hidden" name="' . $parameterArray['itemFormElName'] . '" value="' . htmlspecialchars($parameterArray['itemFormElValue']) . '" />';

        $defaultInputWidth = 10;
        $size = MathUtility::forceIntegerInRange($config['size'] ?? $defaultInputWidth, $this->minimumInputWidth,
            $this->maxInputWidth);
        $width = (int)$this->formMaxWidth($size);

        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $templateView */
        $templateView = GeneralUtility::makeInstance(StandaloneView::class);
        $templateView->setTemplatePathAndFilename('EXT:bw_icons/Resources/Private/Template/FormElement.html');
        $templateView->assignMultiple([
            'itemFormElName' => $parameterArray['itemFormElName'],
            'itemFormElValue' => $parameterArray['itemFormElValue'],
            'width' => $width,
            'fieldWizardHtml' => $fieldWizardHtml
        ]);

        $resultArray['html'] = $templateView->render();
        return $resultArray;
    }
}
