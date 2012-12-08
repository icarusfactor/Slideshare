<?php
 
/*******************************************************************************
* $Id$
*
* This extension allow adding SlideShare slideshows to wiki pages
*
*   {{#slideshare:<slideshare_document_id>|<width>|<height}}
*
* @slideshare_document_id   - slideshare document id
*                             (doc parameter in the URL)
* @width                    - (optional) width parameter, default is 425 pixels
* @height                   - (optional) height parameter, default is 348 pixels
*
*
********************************************************************************
* Copyright (C) 2007 Sergey Chernyshev
* Copyright (C) 2012 Daniel Yount - update to work with the new Slideshare
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU Lesser General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU Lesser General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
********************************************************************************/ 
 
$wgExtensionFunctions[] = 'wfSlideShare';
$wgHooks['LanguageGetMagic'][]       = 'wfSlideShare_Magic';
$wgExtensionCredits['parserhook'][] = array(
        'name' => 'SlideShare',
        'description' => 'Extension to add SlideShare slideshows to wiki pages.',
        'author' => '[http://www.mediawiki.org/wiki/User:Sergey_Chernyshev Sergey Chernyshev]',
        'url' => 'http://www.mediawiki.org/wiki/Extension:SlideShare',
        'version' => '0.1',
        'type' => 'parserhook'
);
 
function wfSlideShare() {
        global $wgParser;
        $wgParser->setFunctionHook('slideshare', 'renderSlideShare');
}
 
function wfSlideShare_Magic( &$magicWords, $langCode ) {
        # Add the magic word
        # The first array element is case sensitive, in this case it is not case sensitive
        # All remaining elements are synonyms for our parser function
        $magicWords['slideshare'] = array( 0, 'slideshare' );
        # unless we return true, other parser functions extensions won't get loaded.
        return true;
}
 
# The callback function for converting the input text to HTML output
function renderSlideShare(&$parser, $id, $width=425, $height=348)
{
        // id must be a URL encoded string
        $id = filter_var($id, FILTER_SANITIZE_ENCODED);
 
        // width must be more then 0
        $width = filter_var($width, FILTER_VALIDATE_INT,
                        array("options" => array("min_range"=>1))
                );
        // height must be more then 0
        $height = filter_var($height, FILTER_VALIDATE_INT,
                        array("options" => array("min_range"=>1))
                );
 
        $output = '';
 
        if ($id)
        {
                $output = '<iframe src="http://www.slideshare.net/slideshow/embed_code/'.$id.'" width="'.$width.'" height="'.$height.'" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" style="border:1px solid #CCC;border-width:1px 1px 0" allowfullscreen></iframe>';
 
        }
 
        return array($output, 'noparse' => true, 'isHTML' => true, 'noargs' => true);
}
