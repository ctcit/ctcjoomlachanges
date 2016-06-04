<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_wrapper
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
?>


<div class="contentpane<?php echo $this->pageclass_sfx; ?>">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1>
                <?php if ($this->escape($this->params->get('page_heading'))) : ?>
                    <?php echo $this->escape($this->params->get('page_heading')); ?>
                <?php else : ?>
                    <?php echo $this->escape($this->params->get('page_title')); ?>
                <?php endif; ?>
            </h1>
        </div>
    <?php endif; ?>
<script type="text/javascript" src="<?php echo JFactory::getConfig()->get('live_site');?>/db/scripts/iframeResizer/js/iframeResizer.js"></script>
<script>
    jQuery(document).ready(function(){
        iFrameResize( [{log:false}] );
    });
</script>
    <iframe <?php echo $this->wrapper->load; ?>
        id="blockrandom"
        name="iframe"
        src="<?php
            $config = JFactory::getConfig();
            $live_site = $config->get('live_site');
            $url = $this->wrapper->url;
            
            $location = isset($_GET['goto']) ? $_GET['goto'] : '';
            if ($location) {
                $url = $live_site.'/tripreports/index.html#/'.$location;
            }
            if (substr($url, 0, 1) == '/') {
                // Use live site to resolve relative url
                $url = $live_site . $url;
            }
            

            
            echo $this->escape($url);
        ?>"
        width="<?php echo $this->escape($this->params->get('width')); ?>"
        height="<?php echo $this->escape($this->params->get('height')); ?>"
        scrolling="<?php echo $this->escape($this->params->get('scrolling')); ?>"
        frameborder="<?php echo $this->escape($this->params->get('frameborder', 1)); ?>"
        class="iframewrapper">
        <?php echo JText::_('COM_WRAPPER_NO_IFRAMES'); ?>
    </iframe>
</div>

