<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

abstract class Pkr_Thumbmaker_Abstract implements \Pkr_Thumbmaker_Interface
{
    const FIT_CONTENT_TO_FRAME       = 'fctf';
    const FIT_CONTENT_PROPORTIONALLY = 'fcp';
    const FIT_FRAME_PROPORTIONALLY   = 'ffp';

    protected $_config = array();

    protected $_imageHeight       = null;
    protected $_imageLastModified = null;
    protected $_imageMimeType     = null;
    protected $_imageRawData      = null;
    protected $_imageResource     = null;
    protected $_imageWidth        = null;

    public function __construct() {

        $this->_config['quality'] = 80;
    }

    protected function _imagecreatetruecoloralpha($x,$y) {
        $resource = imagecreatetruecolor($x,$y);

        if (!imagealphablending($resource, false) || !imagesavealpha($resource, true)) {
            // needed for alpha channel

            throw new \Pkr_Thumbmaker_Abstract_Exception('imagealphablending or imagesavealpha failed');
        }

        return $resource;
    }

    protected function _alter() {

        if (!isset($this->_config['method'])) {

            throw new \Pkr_Thumbmaker_Abstract_Exception('method missing');
        }

        switch ($this->_config['method']) {
            case self::FIT_CONTENT_TO_FRAME:
                $this->_methodFitContentToFrame();
                break;
            case self::FIT_CONTENT_PROPORTIONALLY:
                $this->_methodFitContentProportionally();
                break;
            case self::FIT_FRAME_PROPORTIONALLY:
                $this->_methodFitFrameProportionally();
                break;
        }
    }

    abstract protected function _setInputData();

    protected function _setOutputData() {

        if ($this->_imageMimeType == 'image/png') {
            ob_start();
                imagepng($this->_imageResource);
            $this->_imageRawData = ob_get_clean();
        } else {
            ob_start();
                imagejpeg($this->_imageResource, false, $this->_config['quality']);
            $this->_imageRawData = ob_get_clean();
        }
    }

    protected function _dump() {

        $lastModified = gmdate('r', strtotime($this->_imageLastModified));
        $etag = md5($this->_imageRawData);

        header('Last-Modified: ' . $lastModified);
        header('ETag: "' . $etag . '"');

        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $lastModified ||
            isset($_SERVER['HTTP_IF_NONE_MATCH']) && str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == $etag) {

            header('HTTP/1.1 304 Not Modified');
        } else {

            if ($this->_imageMimeType == 'image/png') {
                header('Content-Type: image/png');
            } else {
                header('Content-Type: image/jpeg');
            }

            header('Content-Length: ' . strlen($this->_imageRawData));

            print $this->_imageRawData;
        }
    }

    protected function _methodFitContentToFrame() {
        /* Fit Content to Frame */

        if (!isset($this->_config['width']) || !isset($this->_config['height'])) {

            throw new \Pkr_Thumbmaker_Abstract_Exception('width and height needed');
        }

        $newResource = $this->_imagecreatetruecoloralpha($this->_config['width'], $this->_config['height']);

        if (imagecopyresampled($newResource, $this->_imageResource,
                               0,0,0,0,
                               $this->_config['width'], $this->_config['height'],
                               $this->_imageWidth, $this->_imageHeight)) {

            $this->_imageResource = $newResource;
            $this->_imageWidth = $this->_config['width'];
            $this->_imageHeight = $this->_config['height'];
        }
    }

    protected function _methodFitContentProportionally() {
        /* Fit Content Proportionally */

        if (!isset($this->_config['width']) || !isset($this->_config['height'])) {

            throw new \Pkr_Thumbmaker_Abstract_Exception('width and height needed');
        }

        $ratio = $this->_imageWidth / $this->_imageHeight;

        if ($this->_config['width'] / $this->_config['height'] > $ratio) {
            $this->_config['width'] = $this->_config['height'] * $ratio;
        } else {
            $this->_config['height'] = $this->_config['width'] / $ratio;
        }

        $newResource = $this->_imagecreatetruecoloralpha($this->_config['width'], $this->_config['height']);

        if (imagecopyresampled($newResource, $this->_imageResource,
                               0,0,0,0,
                               $this->_config['width'], $this->_config['height'],
                               $this->_imageWidth, $this->_imageHeight)) {

            $this->_imageResource = $newResource;
            $this->_imageWidth = $this->_config['width'];
            $this->_imageHeight = $this->_config['height'];
        }
    }

    protected function _methodFitFrameProportionally() {
        /* Fit Frame Proportionally */

        if (!isset($this->_config['width']) || !isset($this->_config['height'])) {

            throw new \Pkr_Thumbmaker_Abstract_Exception('width and height needed');
        }

        $ratioImage = $this->_imageWidth / $this->_imageHeight;
        $ratioFrame = $this->_config['width'] / $this->_config['height'];

        if ($ratioFrame < $ratioImage) {
            $newImageHeight = $this->_imageHeight;
            $newImageWidth = $this->_imageHeight * $ratioFrame;

            $offsetX = (int) ($this->_imageWidth - $newImageWidth) / 2;
            $offsetY = 0;
        } else if ($ratioFrame > $ratioImage) {
            $newImageHeight = $this->_imageWidth / $ratioFrame;
            $newImageWidth = $this->_imageWidth;

            $offsetX = 0;
            $offsetY = (int) ($this->_imageHeight - $newImageHeight) / 2;
        } else {
            $newImageHeight = $this->_imageHeight;
            $newImageWidth = $this->_imageWidth;

            $offsetX = 0;
            $offsetY = 0;
        }

        $newResource = $this->_imagecreatetruecoloralpha($this->_config['width'], $this->_config['height']);

        if (imagecopyresampled($newResource, $this->_imageResource,
                               0, 0,
                               $offsetX, $offsetY,
                               $this->_config['width'], $this->_config['height'],
                               $newImageWidth, $newImageHeight)) {

            $this->_imageResource = $newResource;
            $this->_imageWidth = $this->_config['width'];
            $this->_imageHeight = $this->_config['height'];
        }
    }

    public function get($key) {

        if (isset($this->_config[$key])) {
            return $this->_config[$key];
        }

        return null;
    }

    public function set($key, $value) {

        $this->_config[$key] = $value;

        return $this;
    }

    public function dump() {

        if (isset($this->_config['cache']) && !($this->_config['cache'] instanceof Zend_Cache_Core)) {

            throw new \Pkr_Thumbmaker_Abstract_Exception('cache musst be an instance of Zend_Cache_Core');
        }

        $key = 'thumb_'.md5($this->_config['id'].'_'.$this->_config['method'].'_'.$this->_config['width'].'x'.$this->_config['height']);

        if (!isset($this->_config['cache']) || !$cacheData = $this->_config['cache']->load($key)) {

            $this->_setInputData();

            if (empty($this->_imageResource) || empty($this->_imageMimeType) || empty($this->_imageWidth) || empty($this->_imageHeight) || empty($this->_imageLastModified)) {

                throw new \Pkr_Thumbmaker_Abstract_Exception('image data needed');
            }

            if ($this->_config['width'] > 5000 || $this->_config['height'] > 5000) {

                throw new \Pkr_Thumbmaker_Abstract_Exception('dimensions to large');
            }

            $this->_alter();
            $this->_setOutputData();

            if (isset($this->_config['cache'])) {

                $cacheData = array(
                    'imageMimeType'     => $this->_imageMimeType,
                    'imageRawData'      => $this->_imageRawData,
                    'imageLastModified' => $this->_imageLastModified
                );

                $this->_config['cache']->save($cacheData, $key);
            }
        } else {
            $this->_imageMimeType       = $cacheData['imageMimeType'];
            $this->_imageRawData        = $cacheData['imageRawData'];
            $this->_imageLastModified   = $cacheData['imageLastModified'];
        }

        $this->_dump();
    }
}

class Pkr_Thumbmaker_Abstract_Exception extends \Exception
{
}
