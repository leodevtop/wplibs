<?php

namespace Vustech\Wplibs;

class TermMeta {

    public $metaKey;
    /**
     * @var int a counter used to generate [[id]] for meta.
     * @internal
     */
    public static $counter = 0;
    /**
     * @var string the prefix to the automatically generated meta IDs.
     * @see getId()
     */
    public static $autoIdPrefix = 'tm';

    private $_id;

    public function __construct() {
        
    }
    /**
     * Returns the ID of the widget.
     * @param bool $autoGenerate whether to generate an ID if it is not set previously
     * @return string ID of the widget.
     */
    public function getId($autoGenerate = true)
    {
        if ($autoGenerate && $this->_id === null) {
            $this->_id = static::$autoIdPrefix . static::$counter++;
        }

        return $this->_id;
    }

    /**
     * Sets the ID of the widget.
     * @param string $value id of the widget.
     */
    public function setId($value)
    {
        $this->_id = $value;
    }

    function addImage($term = false)
    {
        wp_enqueue_media();
        ?>
        <div class="form-field term_thumbnail" id="<?= $this->getId() ?>">
            <label for="_term_thumbnail_id"><?php _e('Featured Image'); ?></label>
            <?php
            $term_thumbnail_id = -1;
            $html = __('Set featured image', 'leo_plg_restaurant');
            if($term && is_object($term))
            {
                if($_term_thumbnail_id = get_term_meta($term->term_id, '_term_thumbnail_id', true))
                {
                    $term_thumbnail_id = $_term_thumbnail_id;
                    $html = wp_get_attachment_image($term_thumbnail_id, 'thumbnail', false);
                }
            }
            ?>
            <p class="hide-if-no-js">
                <a href="#" class="set_term_thumbnail"><?php echo $html ?></a>
            </p>
            <p class="hide-if-no-js">
                <span <?php if($term_thumbnail_id==-1) echo 'style="display: none"' ?> class="remove_term_thumbnail">
                    <span class="howto" id="set-term-thumbnail-desc"><?php _e('Click the image to edit or update'); ?></span>
                    <a href="#"><?php _e('Remove featured image'); ?></a>
                </span>
                <input type="hidden" class="_term_thumbnail_id" name="term_meta[_term_thumbnail_id]" value="<?php echo $term_thumbnail_id ?>" />
            </p>
        </div>
        <?php
        echo mediaJs();
    }

    function mediaJs()
    { ?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('.set_term_thumbnail').click(function(e){
                    let frame;
                    e.preventDefault();
                    if(frame) {
                        frame.open();
                        return;
                    }
                    frame = wp.media();
                    frame.on("select", function() {
                        // Grab the selected attachment.
                        let attachment = frame.state().get('selection').first().toJSON();
                        id = $(this).closest('.term_thumbnail').attr('id');
                        $('#' + id + ' .set_term_thumbnail').html('<img src="'+attachment.sizes.thumbnail.url+'" alt="image" />');
                        $('#' + id + ' ._term_thumbnail_id').val(attachment.id);
                        $('#' + id + ' .remove_term_thumbnail').show();
                        frame.close();
                    });
                    frame.open();
                });
                jQuery('.remove_term_thumbnail a').click(function(e){
                    e.preventDefault();
                    id = $(this).closest('.term_thumbnail').attr('id');
                    jQuery('#' + id + ' .set_term_thumbnail').html('<?= esc_attr(__('Set featured image')) ?>');
                    jQuery('#' + id + ' ._term_thumbnail_id').val('');
                    jQuery('#' + id + ' .remove_term_thumbnail').hide();
                });
            });
        </script>
    <?php
    }
}