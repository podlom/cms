<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 19.12.2016
 */
namespace skeeks\cms\widgets;
/**
 * Class Pjax
 *
 * @package skeeks\cms\widgets
 */
class Pjax extends \yii\widgets\Pjax
{
    /**
     * Block container Pjax
     * @var bool
     */
    public $isBlock     = true;

    /**
     * Block other container
     * @var string
     */
    public $blockContainer          = '';

    /**
     * @var int
     */
    public $timeout = 30000;


    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        parent::registerClientScript();

        $errorMessage = \Yii::t('skeeks/admin', 'An unexpected error occurred. Refer to the developers.');

        if ($this->isBlock === true)
        {
            $this->getView()->registerJs(<<<JS
                (function(sx, $, _)
                {
                    var blockerPanel = new sx.classes.Blocker('.sx-panel');

                    $(document).on('pjax:send', function(e)
                    {
                        blockerPanel = new sx.classes.Blocker($(e.target));
                        blockerPanel.block();
                    });

                    $(document).on('pjax:complete', function(e) {
                        blockerPanel.unblock();
                    });

                    $(document).on('pjax:error', function(e, data) {
                        sx.notify.error('{$errorMessage}');
                        blockerPanel.unblock();
                    });

                })(sx, sx.$, sx._);
JS
            );
        }

        if ($this->blockContainer)
        {
            $this->getView()->registerJs(<<<JS
                (function(sx, $, _)
                {
                    var blockerPanel = new sx.classes.Blocker($("{$this->blockContainer}"));

                    $(document).on('pjax:send', function(e)
                    {
                        var blockerPanel = new sx.classes.Blocker($("{$this->blockContainer}"));
                        blockerPanel.block();
                    });

                    $(document).on('pjax:complete', function(e) {
                        blockerPanel.unblock();
                    });

                    $(document).on('pjax:error', function(e, data) {
                        sx.notify.error('{$errorMessage}');
                        blockerPanel.unblock();
                    });

                })(sx, sx.$, sx._);
JS
            );
        }

    }
}