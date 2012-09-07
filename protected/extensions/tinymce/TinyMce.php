<?php

Yii::import('ext.tinymce.ETinyMce');

class TinyMce extends ETinyMce
{
    public function init()
    {
        $this->setOptions(array(
                               'theme' => 'advanced',
                               'theme_advanced_toolbar_location' => 'top',
                               'theme_advanced_toolbar_align' => 'left',
                               'theme_advanced_path_location' => 'bottom',
                          ));

        $this->setPlugins(CMap::mergeArray($this->getPlugins(), array('example')));

        $this->setMode('html');
        $this->setUseSwitch(false);
        $this->setEditorTemplate('full');
    }

    protected function makeFullEditor($url = '')
    {
        $options = array();

        $this->setPlugins(array('safari', 'pagebreak', 'style', 'layer', 'table', 'save', 'advhr', 'advimage', 'advlink', 'emotions', 'spellchecker', 'inlinepopups', 'insertdatetime', 'preview', 'media', 'searchreplace', 'print', 'contextmenu', 'paste', 'directionality', 'fullscreen', 'noneditable', 'visualchars', 'nonbreaking', 'xhtmlxtras', 'template'));

        if ($this->getContentCSS() !== '') {
            $sc = "styleselect,";
            $c1 = '';
            $c2 = 'cite,abbr,acronym,|,';
        }
        else {
            $sc = '';
            $c1 = ',|,cite,abbr,acronym';
            $c2 = '';
        }

        if ($this->getFontFamilies()) {
            $options['theme_advanced_fonts'] = implode(',', $this->getFontFamilies());
        }
        if ($this->getFontSizes()) {
            $options['theme_advanced_font_sizes'] = implode(',', $this->getFontSizes());
        }

        $options['theme'] = 'advanced';
        $options['theme_advanced_toolbar_location'] = 'top';
        $options['theme_advanced_toolbar_align'] = 'left';
        $options['theme_advanced_path_location'] = 'bottom';
        $options['theme_advanced_buttons1'] = "save,newdocument,print,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,undo,redo,|,removeformat,cleanup,|,spellchecker,|,visualaid,visualchars,|,ltr,rtl,|,code,preview,fullscreen,|,help";
        $options['theme_advanced_buttons2'] = "{$sc}formatselect,fontselect,fontsizeselect,|,forecolor,backcolor,|,bold,italic,underline,strikethrough,|,sub,sup{$c1}";
        $options['theme_advanced_buttons3'] = "justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,hr,advhr,nonbreaking,pagebreak,blockquote,|,charmap,emotions,media,image,|,link,unlink,anchor,|,insertdate,inserttime";
        $options['theme_advanced_buttons4'] = "{$c2}tablecontrols,|,insertlayer,moveforward,movebackward,absolute,|,styleprops,del,ins,attribs,|,template";
        $options['theme_advanced_toolbar_location'] = "'top'";
        $options['theme_advanced_toolbar_align'] = "'left'";
        $options['theme_advanced_statusbar_location'] = "bottom";
        $options['theme_advanced_toolbar_location'] = 'top';
        $options['theme_advanced_toolbar_align'] = 'left';
        $options['theme_advanced_path_location'] = 'bottom';
        $options['theme_advanced_resize_horizontal'] = true;
        $options['theme_advanced_resizing'] = true;
        $options['spellchecker_languages'] = '+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv';
        if ($url !== '') {
            $options['spellchecker_rpc_url'] = $url . '/plugins/spellchecker/rpc.php';
        }

        return $options;
    }
}
