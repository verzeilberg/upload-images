<?php

namespace verzeilberg\UploadImagesBundle\Service;


class Crop
{
    /**
     * @param null $sOriLocation
     * @param null $sDestinationFolder
     * @param null $iImgWidth
     * @param null $iImgHeight
     * @param string $imageType
     * @param null $imageObject
     * @return string
     */
    public function resizeAndCropImage($sOriLocation = null, $sDestinationFolder = null, $iImgWidth = null, $iImgHeight = null, $imageType = 'original', $imageObject = null)
    {


        $sPathParts = pathinfo($sOriLocation);
        $sFileName = $sPathParts['basename'];
        $sMimeType = mime_content_type($sOriLocation);

        // Depending on wich file type is uploaded create a image
        if ($sMimeType == "image/jpeg") {
            $oSourceImage = imagecreatefromjpeg($sOriLocation);
        } else if ($sMimeType == "image/png") {
            $oSourceImage = imagecreatefrompng($sOriLocation);
        } else if ($sMimeType == "image/gif") {
            $oSourceImage = imagecreatefromgif($sOriLocation);
        } else {
            return 'The file is not a image';
        }

        // Get the widht and height of the uploade image
        $aFileProps = getimagesize($sOriLocation);

        $iWidth = $aFileProps[0];
        $iHeight = $aFileProps[1];

        $original_aspect = $iWidth / $iHeight;
        $thumb_aspect = $iImgWidth / $iImgHeight;

        if ($original_aspect >= $thumb_aspect) {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $iImgHeight;
            $new_width = $iWidth / ($iHeight / $iImgHeight);
        } else {
            // If the thumbnail is wider than the image
            $new_width = $iImgWidth;
            $new_height = $iHeight / ($iWidth / $iImgWidth);
        }

        # Create Temporary image with new Width and height
        # iNewWidth -> integer
        # iNewHeight -> integer
        $oTempImage = imagecreatetruecolor($iImgWidth, $iImgHeight);


        if ($sMimeType == "image/png" || $sMimeType == "image/gif") {

            $oTransparentIndex = imagecolortransparent($oSourceImage);
            if ($oTransparentIndex >= 0) { // GIF
                imagepalettecopy($oSourceImage, $oTempImage);
                imagefill($oTempImage, 0, 0, $oTransparentIndex);
                imagecolortransparent($oTempImage, $oTransparentIndex);
                imagetruecolortopalette($oTempImage, true, 256);
            } else { // PNG
                imagealphablending($oTempImage, false);
                imagesavealpha($oTempImage, true);
                $oTransparent = imagecolorallocatealpha($oTempImage, 255, 255, 255, 127);
                imagefilledrectangle($oTempImage, 0, 0, $iImgWidth, $iImgHeight, $oTransparent);
            }
        }

        // Resize and crop
        imagecopyresampled($oTempImage, $oSourceImage, 0 - ($new_width - $iImgWidth) / 2, 0 - ($new_height - $iImgHeight) / 2, 0, 0, $new_width, $new_height, $iWidth, $iHeight);

        $sPathToFile = $sDestinationFolder . '/' . $sFileName;

        //Check MimeType to create image
        if ($sMimeType == "image/jpeg") {
            imagejpeg($oTempImage, $sPathToFile, 80);
        } else if ($sMimeType == "image/png") {
            imagepng($oTempImage, $sPathToFile, 9);
        } else if ($sMimeType == "image/gif") {
            imagegif($oTempImage, $sPathToFile);
        } else {
            return 'Image could not be resized';
        }
        imagedestroy($oSourceImage);
        imagedestroy($oTempImage);

        //Create Image type
        return true;

    }


}